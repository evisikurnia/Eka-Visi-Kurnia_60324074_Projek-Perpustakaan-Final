<x-app-layout>
    <div class="container py-5" style="font-family: 'Figtree', sans-serif;">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-3 shadow-sm border">
            <div>
                <h1 class="fs-3 fw-bold text-dark mb-0"><i class="bi bi-file-earmark-bar-graph text-primary"></i> Laporan Transaksi</h1>
                <p class="text-muted small mb-0">Filter log perpustakaan dan cetak laporan sirkulasi.</p>
            </div>
            <a href="{{ route('transaksi.cetak', request()->all()) }}" target="_blank" class="btn btn-danger fw-semibold px-4 py-2">
                <i class="bi bi-file-pdf"></i> Cetak / Export PDF
            </a>
        </div>

        <div class="card mb-4 border shadow-sm rounded-3">
            <div class="card-body bg-light p-4">
                <form action="{{ route('transaksi.laporan') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">Anggota</label>
                            <select name="anggota_id" class="form-select">
                                <option value="">Semua Anggota</option>
                                @foreach($anggotas as $agt)
                                    <option value="{{ $agt->id }}" {{ request('anggota_id') == $agt->id ? 'selected' : '' }}>{{ $agt->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="card bg-white border shadow-sm p-3 text-center">
                    <h6 class="text-muted small fw-bold mb-1">TOTAL DATA FILTERED</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $transaksis->count() }} Transaksi</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-white border shadow-sm p-3 text-center">
                    <h6 class="text-muted small fw-bold mb-1">TOTAL DENDA AKUMULASI</h6>
                    @php
                        $total_denda_plus_berjalan = $total_denda;
                        foreach($transaksis as $trx) {
                            if($trx->status == 'Dipinjam' && \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast()) {
                                $hari = floor(\Carbon\Carbon::parse($trx->tanggal_kembali)->diffInDays(now()));
                                $total_denda_plus_berjalan += ($hari * 5000);
                            }
                        }
                    @endphp
                    <h3 class="fw-bold text-danger mb-0">Rp {{ number_format($total_denda_plus_berjalan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="card border shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small fw-bold text-secondary">
                            <th class="ps-4">Kode</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th class="pe-4">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $trx)
                        <tr>
                            <td class="ps-4"><code>{{ $trx->kode_transaksi }}</code></td>
                            <td class="fw-bold">{{ $trx->anggota->nama ?? '-' }}</td>
                            <td>{{ $trx->buku->judul ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d/m/Y') }}</td>
                            <td>
                                @if($trx->status == 'Dipinjam' && \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast())
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge {{ $trx->status == 'Dipinjam' ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ $trx->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 fw-bold {{ ($trx->denda > 0 || ($trx->status == 'Dipinjam' && \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast())) ? 'text-danger' : 'text-muted' }}">
                                @if($trx->status == 'Dipinjam' && \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast())
                                    @php
                                        $hari_terlambat = floor(\Carbon\Carbon::parse($trx->tanggal_kembali)->diffInDays(now()));
                                        $denda_berjalan = $hari_terlambat * 5000;
                                    @endphp
                                    Rp {{ number_format($denda_berjalan, 0, ',', '.') }}
                                @else
                                    {{ $trx->denda > 0 ? 'Rp ' . number_format($trx->denda, 0, ',', '.') : '-' }}
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data transaksi yang sesuai filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>