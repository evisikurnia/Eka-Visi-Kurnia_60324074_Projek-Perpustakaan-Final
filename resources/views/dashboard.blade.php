@extends('layouts.app')

@section('title', 'Dashboard Perpustakaan')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold"><i class="bi bi-speedometer2 text-primary"></i> Dashboard Sistem Perpustakaan</h1>
            <p class="text-muted">Ringkasan data, statistik terkini, dan jalan pintas menu utama perpustakaan.</p>
        </div>
    </div>

    {{-- SECTION 1: QUICK LINKS --}}
    <div class="card mb-4 bg-light border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3 fw-bold"><i class="bi bi-lightning-fill text-warning"></i> Quick Links Menu Utama</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('buku.index') }}" class="btn btn-primary"><i class="bi bi-book"></i> Kelola Buku</a>
                <a href="{{ route('buku.create') }}" class="btn btn-outline-primary"><i class="bi bi-plus-circle"></i> Tambah Buku Baru</a>
                <a href="{{ route('anggota.index') }}" class="btn btn-success"><i class="bi bi-people"></i> Kelola Anggota</a>
                <a href="{{ route('anggota.create') }}" class="btn btn-outline-success"><i class="bi bi-person-plus"></i> Tambah Anggota Baru</a>
            </div>
        </div>
    </div>

    {{-- SECTION 2: STATISTIK BUKU & ANGGOTA --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100 shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Buku</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <i class="bi bi-book-fill opacity-50" style="font-size: 3.5rem;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100 shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Buku Tersedia</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ $bukuTersedia }}</h2>
                    </div>
                    <i class="bi bi-check-circle-fill opacity-50" style="font-size: 3.5rem;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white h-100 shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Anggota</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ $totalAnggota }}</h2>
                    </div>
                    <i class="bi bi-people-fill opacity-50" style="font-size: 3.5rem;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100 shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Anggota Aktif</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ $anggotaAktif }}</h2>
                    </div>
                    <i class="bi bi-person-check-fill opacity-50" style="font-size: 3.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: LIST DATA TERBARU --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 fw-bold border-bottom-0">
                    <i class="bi bi-journal-plus text-primary"></i> 5 Buku Terbaru Yang Ditambahkan
                </div>
                <div class="list-group list-group-flush">
                    @forelse($bukuTerbaru as $buku)
                        <div class="list-group-item py-3">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <h6 class="mb-1 fw-bold text-truncate" style="max-width: 75%;">{{ $buku->judul }}</h6>
                                <span class="badge bg-secondary small">{{ $buku->kategori }}</span>
                            </div>
                            <small class="text-muted d-block"><i class="bi bi-person"></i> Pengarang: {{ $buku->pengarang }}</small>
                            <small class="text-muted small">Terbit: {{ $buku->tahun_terbit }}</small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">Belum ada data buku.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 fw-bold border-bottom-0">
                    <i class="bi bi-person-plus-fill text-success"></i> 5 Anggota Terbaru Terdaftar
                </div>
                <div class="list-group list-group-flush">
                    @forelse($anggotaTerbaru as $anggota)
                        <div class="list-group-item py-3">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <h6 class="mb-1 fw-bold">{{ $anggota->nama }}</h6>
                                <span class="badge bg-{{ $anggota->status == 'Aktif' ? 'success' : 'danger' }}">{{ $anggota->status }}</span>
                            </div>
                            <small class="text-muted d-block"><i class="bi bi-upc-scan"></i> ID: <code>{{ $anggota->kode_anggota }}</code></small>
                            <small class="text-muted small">Email: {{ $anggota->email }}</small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">Belum ada data anggota.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@forelse($bukuTerbaru as $buku)
    <div class="mb-3">
        <x-buku-card :buku="$buku" :showActions="false" />
    </div>
@empty
    <p class="text-muted text-center py-3">Belum ada data buku.</p>
@endforelse