<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: sans-serif; padding: 30px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center mb-4">
        <h2>LAPORAN TRANSAKSI PERPUSTAKAAN</h2>
        <p class="text-muted">Sistem Informasi Perpustakaan Mandiri</p>
        <hr>
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Kode</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $trx)
            <tr>
                <td><code>{{ $trx->kode_transaksi }}</code></td>
                <td>{{ $trx->anggota->nama ?? '-' }}</td>
                <td>{{ $trx->buku->judul ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d/m/Y') }}</td>
                <td>{{ $trx->status }}</td>
                <td>Rp {{ number_format($trx->denda, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-4">
        <div class="col-6">
            <p>Total Catatan: <strong>{{ $transaksis->count() }} Data</strong></p>
            <p>Total Akumulasi Denda: <strong>Rp {{ number_format($total_denda, 0, ',', '.') }}</strong></p>
        </div>
    </div>
</body>
</html>