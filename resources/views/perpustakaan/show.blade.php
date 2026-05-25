<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail: {{ $buku['judul'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white p-3">
                <h3 class="mb-0">{{ $buku['judul'] }}</h3>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-borderless fs-5">
                            <tr><th width="200">ID Buku</th><td>: {{ $buku['id'] }}</td></tr>
                            <tr><th>Pengarang</th><td>: {{ $buku['pengarang'] }}</td></tr>
                            <tr><th>Penerbit</th><td>: {{ $buku['penerbit'] }}</td></tr>
                            <tr><th>Tahun Terbit</th><td>: {{ $buku['tahun'] }}</td></tr>
                            <tr><th>Stok Tersedia</th><td>: 
                                <span class="badge {{ $buku['stok'] > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $buku['stok'] > 0 ? $buku['stok'].' Buku' : 'Habis' }}
                                </span>
                            </td></tr>
                        </table>
                        <hr>
                        <h5>Deskripsi Buku :</h5>
                        <p class="text-secondary lh-lg">{{ $buku['deskripsi'] }}</p>
                    </div>
                    <div class="col-md-4 text-center bg-light p-4 rounded">
                        <h4 class="text-muted">Harga Buku</h4>
                        <h2 class="text-success fw-bold my-3">Rp {{ number_format($buku['harga'], 0, ',', '.') }}</h2>
                        <button class="btn btn-outline-primary btn-lg w-100" {{ $buku['stok'] == 0 ? 'disabled' : '' }}>
                            Pinjam Buku Ini
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white p-3 text-end">
                <a href="/perpustakaan" class="btn btn-secondary">← Kembali ke Daftar Buku</a>
            </div>
        </div>
    </div>
</body>
</html>