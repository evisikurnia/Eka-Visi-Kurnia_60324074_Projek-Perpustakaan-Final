<x-app-layout>
    <div class="container py-5" style="font-family: 'Figtree', sans-serif;">
        <div class="row g-4">
            
            <div class="col-10">
                <h1 class="fs-3 fw-bold text-dark mb-1">Selamat Datang di Dashboard Perpustakaan</h1>
                <p class="text-muted small">Pantau metrik aktivitas sirkulasi buku hari ini.</p>
            </div>

            @php
                // Logika instan mengambil transaksi terlambat
                $terlambats = \App\Models\Transaksi::with(['anggota','buku'])
                                ->where('status', 'Dipinjam')
                                ->where('tanggal_kembali', '<', now())
                                ->get();
            @endphp

            <div class="col-md-4">
                <div class="card border-0 bg-danger text-white shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="small fw-bold text-uppercase opacity-75 mb-1">Buku Terlambat</h6>
                        <h2 class="display-5 fw-bold mb-0">{{ $terlambats->count() }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 fw-bold text-dark">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i> Daftar Anggota Terlambat Mengembalikan
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($terlambats as $tl)
                                @php
                                    $hari = \Carbon\Carbon::parse($tl->tanggal_kembali)->diffInDays(now());
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <strong class="text-dark">{{ $tl->anggota->nama ?? '-' }}</strong>
                                        <span class="text-muted small d-block">Meminjam: {{ $tl->buku->judul ?? '-' }}</span>
                                    </div>
                                    <span class="badge bg-danger rounded-pill">Terlambat {{ $hari }} Hari</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center py-4 text-muted small">
                                    <i class="bi bi-emoji-smile"></i> Luar biasa! Tidak ada peminjaman yang terlambat saat ini.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>