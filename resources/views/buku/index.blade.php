<x-app-layout>
    <div class="container py-5" style="font-family: 'Figtree', sans-serif;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-3 shadow-sm border">
            <div>
                <h1 class="fs-3 fw-bold text-dark mb-0">
                    <i class="bi bi-book text-primary"></i> Daftar Koleksi Buku
                </h1>
                <p class="text-muted small mb-0">Kelola informasi buku dan lakukan pencarian data dengan mudah.</p>
            </div>
            <div>
                <a href="/buku/create" class="btn btn-primary fw-semibold px-4 py-2">
                    <i class="bi bi-plus-circle"></i> Tambah Buku
                </a>
            </div>
        </div>

        <div class="card mb-4 border shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0 fs-6 fw-bold"><i class="bi bi-search"></i> Pencarian & Filter</h5>
            </div>
            <div class="card-body bg-light p-4">
                <form action="/buku" method="GET">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">Kata Kunci</label>
                            <input type="text" name="keyword" class="form-control" placeholder="Cari judul atau pengarang..." value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">-- Semua Kategori --</option>
                                <option value="Programming" {{ request('kategori') == 'Programming' ? 'selected' : '' }}>Programming</option>
                                <option value="Database" {{ request('kategori') == 'Database' ? 'selected' : '' }}>Database</option>
                                <option value="Web Design" {{ request('kategori') == 'Web Design' ? 'selected' : '' }}>Web Design</option>
                                <option value="Networking" {{ request('kategori') == 'Networking' ? 'selected' : '' }}>Networking</option>
                                <option value="Data Science" {{ request('kategori') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-secondary small fw-bold">
                                <th class="ps-4">Kode Buku</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th class="text-center" width="120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukus ?? [] as $buku)
                            <tr>
                                <td class="ps-4"><code class="text-danger fw-semibold">{{ $buku->kode_buku }}</code></td>
                                <td class="fw-bold text-dark">{{ $buku->judul }}</td>
                                <td>{{ $buku->pengarang }}</td>
                                <td><span class="badge bg-secondary px-2.5 py-1.5">{{ $buku->kategori }}</span></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="/buku/{{ $buku->id }}" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>
                                        <a href="/buku/{{ $buku->id }}/edit" class="btn btn-sm btn-warning text-white" title="Ubah"><i class="bi bi-pencil"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-3 text-muted"></i>
                                    Tidak ada data buku yang ditemukan.
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