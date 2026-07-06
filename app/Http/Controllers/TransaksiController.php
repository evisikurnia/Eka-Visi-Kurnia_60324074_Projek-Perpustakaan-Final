<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
 
class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['anggota', 'buku'])
                               ->latest()
                               ->get();
        
        return view('transaksi.index', compact('transaksis'));
    }
 
    public function create()
    {
        // Ambil anggota aktif
        $anggotas = Anggota::where('status', 'Aktif')->orderBy('nama')->get();
        
        // Ambil buku yang tersedia (stok > 0)
        $bukus = Buku::where('stok', '>', 0)->orderBy('judul')->get();
        
        return view('transaksi.create', compact('anggotas', 'bukus'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'keterangan' => 'nullable|string',
        ], [
            'anggota_id.required' => 'Anggota wajib dipilih.',
            'buku_id.required' => 'Buku wajib dipilih.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
        ]);
        
        try {
            DB::transaction(function () use ($request) {
                // 1. Check stok buku
                $buku = Buku::findOrFail($request->buku_id);
                if ($buku->stok <= 0) {
                    throw new \Exception('Stok buku habis!');
                }
                
                // 2. Generate kode transaksi
                $kodeTransaksi = $this->generateKodeTransaksi();
                
                // 3. Calculate tanggal kembali (7 hari dari tanggal pinjam)
                $tanggalKembali = Carbon::parse($request->tanggal_pinjam)->addDays(7);
                
                // 4. Create transaksi
                Transaksi::create([
                    'kode_transaksi' => $kodeTransaksi,
                    'anggota_id' => $request->anggota_id,
                    'buku_id' => $request->buku_id,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => $tanggalKembali,
                    'status' => 'Dipinjam',
                    'keterangan' => $request->keterangan,
                ]);
                
                // 5. Update stok buku (kurang 1)
                $buku->decrement('stok');
            });
            
            return redirect()->route('transaksi.index')
                             ->with('success', 'Transaksi peminjaman berhasil dibuat!');
                             
        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }
 
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['anggota', 'buku'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }
 
   public function kembalikan(string $id)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($id) {
                $transaksi = Transaksi::findOrFail($id);
    
                // Validasi double-return
                if ($transaksi->status === 'Dikembalikan') {
                    throw new \Exception('Buku sudah dikembalikan sebelumnya.');
                }
    
                $tanggalDikembalikan = now();
                $denda = $this->hitungDenda($transaksi, $tanggalDikembalikan);
    
                // Update status transaksi
                $transaksi->update([
                    'status' => 'Dikembalikan',
                    'tanggal_dikembalikan' => $tanggalDikembalikan,
                    'denda' => $denda,
                ]);
    
                // Kembalikan ketersediaan stok buku
                $transaksi->buku->increment('stok');
            });
    
            return redirect()->route('transaksi.show', $id)
                            ->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }
 
    private function generateKodeTransaksi()
    {
        $lastTransaksi = Transaksi::latest()->first();
        
        if ($lastTransaksi) {
            $lastNumber = intval(substr($lastTransaksi->kode_transaksi, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'TRX-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
 
    private function hitungDenda($transaksi, $tanggalDikembalikan)
    {
    $tanggalKembaliSeharusnya = \Carbon\Carbon::parse($transaksi->tanggal_kembali);
    $tanggalAktifKembali = \Carbon\Carbon::parse($tanggalDikembalikan);

    // Jika tanggal dikembalikan melewati batas tanggal seharusnya kembali
    if ($tanggalAktifKembali->greaterThan($tanggalKembaliSeharusnya)) {
        $selisihHari = $tanggalAktifKembali->diffInDays($tanggalKembaliSeharusnya);
        return $selisihHari * 5000; // Tarif Rp 5.000/hari
    }

    return 0;
    }

    public function laporan(Request $request)
    {
        // 1. Ambil data transaksi berdasarkan filter
        $query = Transaksi::query();
        
        // ... (kode filter tanggal/anggota kamu yang sudah ada) ...

        $transaksi = $query->get();
        $total_denda = 0;

        // 2. HITUNG DENDA BERJALAN SECARA REAL-TIME UNTUK LAPORAN
        foreach ($transaksi as $t) {
            if ($t->status == 'Dipinjam' && now()->gt($t->tanggal_kembali)) {
                // Jika masih dipinjam dan sudah melewati tanggal kembali
                $hari_terlambat = floor(now()->diffInDays($t->tanggal_kembali));
                $t->hitung_denda = $hari_terlambat * 5000;
                $t->status_laporan = 'Terlambat'; // Ubah status khusus untuk tampilan laporan
            } elseif ($t->status == 'Dikembalikan') {
                // Jika sudah dikembalikan, pakai denda asli yang tersimpan di DB
                $t->hitung_denda = $t->denda;
                $t->status_laporan = 'Dikembalikan';
            } else {
                $t->hitung_denda = 0;
                $t->status_laporan = 'Dipinjam';
            }

            // Akumulasikan ke total box merah
            $total_denda += $t->hitung_denda;
        }

        return view('transaksi.laporan', compact('transaksi', 'total_denda'));
    }

    public function cetakLaporan(Request $request)
    {
        $query = Transaksi::with(['anggota', 'buku']);

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        $transaksis = $query->latest()->get();
        $total_denda = $transaksis->sum('denda');

        // Menggunakan window.print() bawaan browser agar tidak perlu install package tambahan yang rumit
        return view('transaksi.cetak_laporan', compact('transaksis', 'total_denda'));
    }
}