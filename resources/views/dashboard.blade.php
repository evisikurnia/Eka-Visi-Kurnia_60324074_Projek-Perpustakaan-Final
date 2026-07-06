@extends('layouts.app')
@section('title', 'Dashboard') 

@section('content')
<div class="container-fluid py-4">    
    <h2 class="mb-4">Dashboard Perpustakaan</h2>     
    
    {{-- 1. 8 Statistics Cards --}}    
    <div class="row g-3 mb-4">        
        @foreach([            
            ['Total Buku', $stats['total_buku'], 'bi-book', 'primary'],            
            ['Anggota Aktif', $stats['total_anggota'], 'bi-people', 'success'],            
            ['Sedang Dipinjam', $stats['sedang_dipinjam'], 'bi-journal-arrow-up', 'info'],            
            ['Terlambat', $stats['terlambat'], 'bi-exclamation-triangle', 'danger'],            
            ['Transaksi Hari Ini', $stats['transaksi_hari_ini'], 'bi-calendar-check', 'warning'],            
            ['Buku Tersedia', $stats['buku_tersedia'], 'bi-bookshelf', 'secondary'],            
            ['Total Transaksi', $stats['total_transaksi'], 'bi-receipt', 'dark'],            
            ['Denda Bulan Ini', 'Rp ' . number_format($stats['denda_bulan_ini'], 0, ',', '.'), 'bi-cash', 'danger'],        
        ] as [$label, $value, $icon, $color])        
        <div class="col-xl-3 col-md-6">            
            <div class="card border-{{ $color }} h-100">                
                <div class="card-body d-flex align-items-center">                    
                    <i class="bi {{ $icon }} fs-1 text-{{ $color }} me-3"></i>                    
                    <div>                        
                        <h6 class="text-muted mb-1">{{ $label }}</h6>                        
                        <h4 class="mb-0">{{ $value }}</h4>                    
                    </div>                
                </div>            
            </div>        
        </div>        
        @endforeach    
    </div>     

    {{-- 2. Charts Row (Line Chart Transaksi & Pie Chart Buku Populer) --}}    
    <div class="row g-4 mb-4">        
        <div class="col-lg-8">            
            <div class="card h-100">                
                <div class="card-header bg-white fw-bold">Transaksi 6 Bulan Terakhir</div>                
                <div class="card-body">                    
                    <canvas id="chartTransaksi" height="120"></canvas>                
                </div>            
            </div>        
        </div>        
        <div class="col-lg-4">            
            <div class="card h-100">                
                <div class="card-header bg-white fw-bold">Top 5 Buku Populer</div>                
                <div class="card-body d-flex flex-column justify-content-center">                    
                    <canvas id="chartBuku"></canvas>                
                </div>            
            </div>        
        </div>    
    </div>     

    {{-- 3. New Row: Top 5 Anggota Aktif & Recent Transactions Table --}}
    <div class="row g-4">
        {{-- Top 5 Anggota Aktif --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white fw-bold">Top 5 Anggota Aktif</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($anggotaAktif ?? [] as $index => $agt)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-3">{{ $index + 1 }}</span>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $agt->nama }}</h6>
                                        <small class="text-muted">Kode: {{ $agt->kode_anggota }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-success rounded-pill">{{ $agt->transaksis_count }}x Pinjam</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">Belum ada data anggota aktif.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Recent Transactions Table --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-white fw-bold">Transaksi Terbaru</div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Kode</th>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th class="pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>                    
                            @forelse($recentTransaksi as $trx)                    
                            <tr>                        
                                <td class="ps-3 fw-medium text-primary">{{ $trx->kode_transaksi }}</td>                        
                                <td>{{ $trx->anggota->nama }}</td>                        
                                <td class="text-wrap" style="max-width: 200px;">{{ $trx->buku->judul }}</td>                        
                                <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>                        
                                <td class="pe-3">                            
                                    <span class="badge bg-{{ $trx->status === 'Dipinjam' ? 'warning' : 'success' }}">                                
                                        {{ $trx->status }}                            
                                    </span>                        
                                </td>                    
                            </tr>                    
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada transaksi terbaru.</td>
                            </tr>
                            @endforelse                
                        </tbody>            
                    </table>        
                </div>    
            </div>
        </div>
    </div>

    {{-- 4. Buku Terlambat & Quick Actions Row --}}
    <div class="row g-4 mt-2">
        {{-- Buku Terlambat (Notifikasi Terlambat) --}}
        <div class="col-lg-8">
            <div class="card h-100 border-danger shadow-sm">
                <div class="card-header bg-danger text-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-exclamation-triangle"></i> Daftar Buku Terlambat</span>
                    <span class="badge bg-white text-danger">{{ $bukuTerlambat->count() }} Peminjaman</span>
                </div>
                <div class="card-body p-0 table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small fw-bold text-secondary">
                                <th class="ps-3">Anggota</th>
                                <th>Buku</th>
                                <th>Batas Waktu</th>
                                <th class="pe-3">Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukuTerlambat as $trx)
                                @php
                                    $selisih = \Carbon\Carbon::parse($trx->tanggal_kembali)->diffInDays(now());
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $trx->anggota->nama }}</td>
                                    <td>{{ $trx->buku->judul }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M Y') }}</td>
                                    <td class="pe-3">
                                        <span class="badge bg-danger">Terlambat {{ $selisih }} Hari</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-1"></i>
                                        Tidak ada peminjaman yang terlambat saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white fw-bold">Aksi Cepat (Quick Actions)</div>
                <div class="card-body d-flex flex-column gap-2 justify-content-center">
                    <a href="{{ route('buku.create') }}" class="btn btn-outline-primary text-start p-3 rounded-3">
                        <i class="bi bi-book-fill me-2 fs-5"></i> Tambah Buku Baru
                    </a>
                    <a href="{{ route('anggota.create') }}" class="btn btn-outline-success text-start p-3 rounded-3">
                        <i class="bi bi-person-plus-fill me-2 fs-5"></i> Tambah Anggota Baru
                    </a>
                    <a href="{{ route('transaksi.create') }}" class="btn btn-outline-info text-start p-3 rounded-3 text-dark">
                        <i class="bi bi-arrow-left-right me-2 fs-5 text-info"></i> Pinjam Buku (Transaksi)
                    </a>
                    <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary text-start p-3 rounded-3">
                        <i class="bi bi-file-earmark-bar-graph-fill me-2 fs-5"></i> Lihat Laporan Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> 

{{-- Jembatan Data HTML Aman --}}
<div id="dosen-chart-data" 
     data-bulan="{{ json_encode($chartData->pluck('bulan')) }}"
     data-pinjam="{{ json_encode($chartData->pluck('pinjam')) }}"
     data-kembali="{{ json_encode($chartData->pluck('kembali')) }}"
     data-buku-judul="{{ json_encode($bukuPopuler->pluck('judul')) }}"
     data-buku-total="{{ json_encode($bukuPopuler->pluck('transaksis_count')) }}"
     style="display: none;">
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dataContainer = document.getElementById('dosen-chart-data');
    
    const chartLabelsBulan = JSON.parse(dataContainer.dataset.bulan);
    const chartDataPinjam = JSON.parse(dataContainer.dataset.pinjam);
    const chartDataKembali = JSON.parse(dataContainer.dataset.kembali);
    const chartLabelsBuku = JSON.parse(dataContainer.dataset.bukuJudul);
    const chartDataBuku = JSON.parse(dataContainer.dataset.bukuTotal);

    // Line chart — Transaksi 6 bulan terakhir
    new Chart(document.getElementById('chartTransaksi'), {    
        type: 'line',    
        data: {        
            labels: chartLabelsBulan,        
            datasets: [            
                { label: 'Peminjaman', data: chartDataPinjam, borderColor: '#0d6efd', tension: 0.3 },            
                { label: 'Pengembalian', data: chartDataKembali, borderColor: '#198754', tension: 0.3 }        
            ]    
        },    
        options: { responsive: true }
    }); 

    // Pie chart — Buku Populer
    new Chart(document.getElementById('chartBuku'), {    
        type: 'pie',    
        data: {        
            labels: chartLabelsBuku,        
            datasets: [{            
                data: chartDataBuku,            
                backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1']        
            }]    
        },    
        options: { 
            responsive: true, 
            plugins: { legend: { position: 'bottom' } } 
        }
    });
</script>
@endpush
@endsection