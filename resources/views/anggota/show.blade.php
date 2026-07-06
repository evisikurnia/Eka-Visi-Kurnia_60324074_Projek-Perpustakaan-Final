@extends('layouts.app')
 
@section('title', $anggota->nama)
 
@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
                <li class="breadcrumb-item active">{{ $anggota->nama }}</li>
            </ol>
        </nav>
    </div>
</div>
 
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-person"></i>
                    Detail Anggota
                </h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if ($anggota->jenis_kelamin == 'Laki-laki')
                        <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                    @else
                        <i class="bi bi-person-circle text-danger" style="font-size: 5rem;"></i>
                    @endif
                    <h3 class="mt-2">{{ $anggota->nama }}</h3>
                    @if ($anggota->status == 'Aktif')
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle"></i> Anggota Aktif
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            <i class="bi bi-x-circle"></i> Nonaktif
                        </span>
                    @endif
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td width="200" class="fw-bold">
                            <i class="bi bi-upc text-success"></i> Kode Anggota
                        </td>
                        <td>: <code>{{ $anggota->kode_anggota }}</code></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-envelope text-success"></i> Email
                        </td>
                        <td>: {{ $anggota->email }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-telephone text-success"></i> Telepon
                        </td>
                        <td>: {{ $anggota->telepon }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-geo-alt text-success"></i> Alamat
                        </td>
                        <td>: {{ $anggota->alamat }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-calendar text-success"></i> Tanggal Lahir
                        </td>
                        <td>: {{ $anggota->tanggal_lahir->format('d F Y') }} ({{ $anggota->umur }} tahun)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-gender-ambiguous text-success"></i> Jenis Kelamin
                        </td>
                        <td>: {{ $anggota->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-briefcase text-success"></i> Pekerjaan
                        </td>
                        <td>: {{ $anggota->pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-calendar-check text-success"></i> Tanggal Daftar
                        </td>
                        <td>: {{ $anggota->tanggal_daftar->format('d F Y') }} ({{ $anggota->lama_anggota }} hari)</td>
                    </tr>
                </table>
                
                <hr>
                <div class="row text-muted small">
                    <div class="col-md-6">
                        <i class="bi bi-clock"></i> 
                        Ditambahkan: {{ $anggota->created_at->format('d M Y H:i') }}
                    </div>
                    <div class="col-md-6 text-end">
                        <i class="bi bi-clock-history"></i> 
                        Terakhir Update: {{ $anggota->updated_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-gear"></i> Aksi
                </h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Anggota
                </a>
                <a href="{{ route('anggota.index') }}" class="btn btn-outline-success">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <hr>
                <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Hapus Anggota
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Peminjaman Anggota (Fitur Tambahan 5) --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card border shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-clock-history text-success"></i> Riwayat Peminjaman Buku
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-3 py-2">Total Pinjam: {{ $statistik['total_pinjam'] }}x</span>
                    <span class="badge bg-danger px-3 py-2">Total Denda: Rp {{ number_format($statistik['total_denda'], 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="card-body p-4 bg-white">
                {{-- Status Filter Buttons --}}
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-sm btn-outline-secondary active" onclick="filterHistory('semua', this)">Semua</button>
                    <button class="btn btn-sm btn-outline-warning" onclick="filterHistory('Dipinjam', this)">Dipinjam</button>
                    <button class="btn btn-sm btn-outline-success" onclick="filterHistory('Dikembalikan', this)">Dikembalikan</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small fw-bold text-secondary">
                                <th width="5%">No</th>
                                <th>Kode Transaksi</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Tanggal Dikembalikan</th>
                                <th>Status</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <tbody id="history-table-body">
                            @forelse($riwayat as $index => $trx)
                                <tr class="history-row" data-status="{{ $trx->status }}">
                                    <td class="text-muted small">{{ $index + 1 }}</td>
                                    <td><code class="fw-bold text-danger">{{ $trx->kode_transaksi }}</code></td>
                                    <td class="fw-bold">{{ $trx->buku->judul ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M Y') }}</td>
                                    <td>{{ $trx->tanggal_dikembalikan ? \Carbon\Carbon::parse($trx->tanggal_dikembalikan)->format('d M Y') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $trx->status == 'Dipinjam' ? 'warning text-dark' : 'success' }}">
                                            {{ $trx->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($trx->denda > 0)
                                            <span class="text-danger fw-bold">Rp {{ number_format($trx->denda, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                        Anggota ini belum pernah meminjam buku.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Button dengan SweetAlert --}}
<form action="{{ route('anggota.destroy', $anggota->id) }}" 
      method="POST" 
      class="d-inline delete-form">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-sm btn-danger w-100 btn-delete" 
            data-judul="{{ $anggota->nama }}">
        <i class="bi bi-trash"></i> Hapus
    </button>
</form>
 
@push('scripts')
<script>
    function filterHistory(status, btn) {
        // Toggle active class on buttons
        const buttons = btn.parentElement.querySelectorAll('button');
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Filter rows
        const rows = document.querySelectorAll('.history-row');
        rows.forEach(row => {
            if (status === 'semua' || row.getAttribute('data-status') === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
 
@push('scripts')
<script>
    // SweetAlert confirmation untuk delete
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const judul = this.getAttribute('data-judul');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus anggota "${judul}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

@push('scripts')
<script>
    // Loading state saat submit form
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !this.classList.contains('delete-form')) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            }
        });
    });
</script>
@endpush
@endsection