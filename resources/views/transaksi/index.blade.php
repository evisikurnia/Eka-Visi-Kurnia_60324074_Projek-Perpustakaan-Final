<x-app-layout>
    <div class="container py-5" style="font-family: 'Figtree', sans-serif;">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-3 shadow-sm border">
            <div>
                <h1 class="fs-3 fw-bold text-dark mb-0">
                    <i class="bi bi-arrow-left-right text-primary"></i> Daftar Transaksi Peminjaman
                </h1>
                <p class="text-muted small mb-0">Monitor sirkulasi log peminjaman dan pengembalian buku.</p>
            </div>
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary fw-semibold px-4 py-2">
                <i class="bi bi-plus-circle"></i> Pinjam Buku
            </a>
        </div>
        
        {{-- Statistik Mini --}}
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card border shadow-sm h-100 bg-white">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-muted text-uppercase fw-bold small mb-1">Total Transaksi</h6>
                        <h2 class="fw-bold mb-0 text-primary">{{ $transaksis->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border shadow-sm h-100 bg-white">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-muted text-uppercase fw-bold small mb-1">Sedang Dipinjam</h6>
                        <h2 class="fw-bold mb-0 text-warning">{{ $transaksis->where('status', 'Dipinjam')->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border shadow-sm h-100 bg-white">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-muted text-uppercase fw-bold small mb-1">Sudah Dikembalikan</h6>
                        <h2 class="fw-bold mb-0 text-success">{{ $transaksis->where('status', 'Dikembalikan')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Tabel Transaksi --}}
        <div class="card border shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-secondary small fw-bold">
                                <th class="ps-4" width="60px">No</th>
                                <th>Kode Transaksi</th>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th class="text-center" width="100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $transaksi)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                <td><code class="text-danger fw-semibold">{{ $transaksi->kode_transaksi }}</code></td>
                                <td class="fw-bold text-dark">{{ $transaksi->anggota->nama ?? '-' }}</td>
                                <td>{{ $transaksi->buku->judul ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('d M Y') }}</td>
                              <td>
                                @if($transaksi->status == 'Dipinjam')
                                    @if(\Carbon\Carbon::parse($transaksi->tanggal_kembali)->isPast())
                                        @php
                                            $selisih = \Carbon\Carbon::parse($transaksi->tanggal_kembali)->diffInDays(now());
                                        @endphp
                                        <span class="badge bg-danger px-2.5 py-1.5 d-block text-center mb-1">Terlambat {{ $selisih }} Hari</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-2.5 py-1.5 d-block text-center">Dipinjam</span>
                                    @endif
                                @else
                                    <span class="badge bg-success px-2.5 py-1.5 d-block text-center">Dikembalikan</span>
                                @endif
                             </td>
                                <td class="text-center">
                                    <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                    Belum ada catatan transaksi peminjaman buku.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>