<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $nama_sistem }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
            <h1 class="display-5 fw-bold text-primary">{{ $nama_sistem }}</h1>
            <p class="col-md-8 fs-4">Selamat datang di sistem manajemen perpustakaan berbasis Laravel versi {{ $versi }}</p>
            
            <div class="alert alert-info d-inline-block">
                <strong>Info:</strong> Total Buku Tersedia: <span class="badge bg-primary fs-6">{{ $total_buku }}</span>
            </div>
        </div>
        
        <h3 class="mb-3 text-secondary">Daftar Buku Terpopuler</h3>
        <div class="table-responsive bg-white p-3 rounded shadow-sm">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Harga Sewa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($buku_list as $index => $buku)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $buku['judul'] }}</td>
                        <td>{{ $buku['pengarang'] }}</td>
                        <td>Rp {{ number_format($buku['harga'], 0, ',', '.') }}</td>
                        <td>
                            @if ($buku['stok'] > 0)
                                <span class="badge bg-success">Tersedia ({{ $buku['stok'] }})</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>