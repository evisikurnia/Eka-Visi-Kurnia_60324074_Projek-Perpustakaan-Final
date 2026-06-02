<div class="card h-100 shadow-sm border-0 transition-hover">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <i class="bi bi-book text-primary display-4"></i>
                <div class="mt-2">
                    <span class="badge bg-{{ $buku->kategori == 'Programming' ? 'primary' : ($buku->kategori == 'Database' ? 'success' : ($buku->kategori == 'Web Design' ? 'info' : 'warning')) }}">
                        {{ $buku->kategori }}
                    </span>
                </div>
            </div>
            
            <div class="col-md-6">
                <h5 class="card-title mb-1 fw-bold text-primary">
                    {{ $buku->judul }}
                </h5>
                <p class="card-text text-muted mb-2 small">
                    <i class="bi bi-person-fill"></i> {{ $buku->pengarang }} | 
                    <i class="bi bi-calendar3"></i> {{ $buku->tahun_terbit }}
                </p>
                <h5 class="text-dark fw-semibold mb-0">
                    {{ $buku->harga_format ?? 'Rp ' . number_format($buku->harga, 0, ',', '.') }}
                </h5>
            </div>
            
            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                <div class="mb-2">
                    @if ($buku->stok > 0)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                            <i class="bi bi-check-circle-fill"></i> Tersedia
                        </span>
                        <small class="text-muted d-block mt-1">Stok: {{ $buku->stok }} buku</small>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                            <i class="bi bi-x-circle-fill"></i> Habis
                        </span>
                    @endif
                </div>

                @if ($showActions)
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                        <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>