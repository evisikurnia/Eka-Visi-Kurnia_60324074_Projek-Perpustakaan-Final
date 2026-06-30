<x-app-layout>
    <div class="container py-5" style="font-family: 'Figtree', sans-serif;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
                @endif
                @if($transaksi->status == 'Dipinjam' && \Carbon\Carbon::parse($transaksi->tanggal_kembali)->isPast())
                @php
                    $hari_lewat = \Carbon\Carbon::parse($transaksi->tanggal_kembali)->diffInDays(now());
                @endphp
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center">
                        <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
                        <div>
                            <strong>Peringatan Penting!</strong> Peminjaman ini telah melewati batas waktu pengembalian selama <strong>{{ $hari_lewat }} hari</strong>. Mohon segera diproses dan informasikan denda harian kepada anggota.
                        </div>
                    </div>
                @endif

                <div class="card border shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fs-5 fw-bold"><i class="bi bi-info-circle"></i> Detail Transaksi: {{ $transaksi->kode_transaksi }}</h4>
                        @if($transaksi->status == 'Dipinjam')
                            <span class="badge bg-warning text-dark px-3 py-2 fs-7">Sedang Dipinjam</span>
                        @else
                            <span class="badge bg-light text-success px-3 py-2 fs-7">Sudah Dikembalikan</span>
                        @endif
                    </div>
                    <div class="card-body p-4 bg-white">
                        <table class="table table-bordered mb-4">
                            <tr>
                                <th width="30%" class="bg-light text-secondary small fw-bold">Nama Peminjam</th>
                                <td class="fw-bold text-dark">{{ $transaksi->anggota->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-secondary small fw-bold">Buku yang Dipinjam</th>
                                <td>{{ $transaksi->buku->judul ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-secondary small fw-bold">Tanggal Peminjaman</th>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-secondary small fw-bold">Batas Waktu Pengembalian</th>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('d M Y') }}</td>
                            </tr>
                            @if($transaksi->status == 'Dikembalikan')
                            <tr>
                                <th class="bg-light text-secondary small fw-bold">Tanggal Dikembalikan</th>
                                <td class="text-success fw-semibold">{{ \Carbon\Carbon::parse($transaksi->tanggal_dikembalikan)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-secondary small fw-bold">Total Denda Terbayar</th>
                                <td class="text-danger fw-bold">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th class="bg-light text-secondary small fw-bold">Keterangan</th>
                                <td class="text-muted small">{{ $transaksi->keterangan ?? '-' }}</td>
                            </tr>
                        </table>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary px-4 py-2fw-semibold">
                                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                            </a>
                            
                            @if($transaksi->status == 'Dipinjam')
                            <form action="{{ route('transaksi.kembalikan', $transaksi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses pengembalian buku ini?')">
                                @csrf
                                <button type="submit" class="btn btn-success px-4 py-2 fw-semibold">
                                    <i class="bi bi-check2-circle"></i> Kembalikan Buku & Hitung Denda
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>