<x-app-layout>
    <div class="container py-5" style="font-family: 'Figtree', sans-serif;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('error'))
                    <div class="alert alert-danger mb-3">{{ session('error') }}</div>
                @endif

                <div class="card border shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0 fs-5 fw-bold">
                            <i class="bi bi-plus-circle"></i> Form Peminjaman Buku
                        </h4>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            
                            {{-- Pilih Anggota --}}
                            <div class="mb-3">
                                <label for="anggota_id" class="form-label fw-semibold text-secondary">Pilih Anggota <span class="text-danger">*</span></label>
                                <select name="anggota_id" id="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror">
                                    <option value="">-- Pilih Anggota --</option>
                                    @foreach($anggotas as $anggota)
                                        <option value="{{ $anggota->id }}" {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                            {{ $anggota->kode_anggota }} - {{ $anggota->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('anggota_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">Hanya anggota dengan status Aktif yang dapat meminjam</small>
                            </div>
                            
                            {{-- Pilih Buku --}}
                            <div class="mb-3">
                                <label for="buku_id" class="form-label fw-semibold text-secondary">Pilih Buku <span class="text-danger">*</span></label>
                                <select name="buku_id" id="buku_id" class="form-select @error('buku_id') is-invalid @enderror">
                                    <option value="">-- Pilih Buku --</option>
                                    @foreach($bukus as $buku)
                                        <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                            {{ $buku->judul }} - (Stok: {{ $buku->stok }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('buku_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">Hanya buku dengan stok tersedia yang dapat dipinjam</small>
                            </div>
                            
                            {{-- Tanggal Pinjam --}}
                            <div class="mb-3">
                                <label for="tanggal_pinjam" class="form-label fw-semibold text-secondary">Tanggal Pinjam <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                                @error('tanggal_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">Tanggal kembali otomatis 7 hari dari tanggal pinjam</small>
                            </div>
                            
                            {{-- Keterangan --}}
                            <div class="mb-4">
                                <label for="keterangan" class="form-label fw-semibold text-secondary">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            </div>
                            
                            {{-- Info Box --}}
                            <div class="alert alert-info border-0 shadow-sm bg-light-info p-3 mb-4 rounded-3">
                                <div class="fw-bold text-primary mb-2"><i class="bi bi-info-circle-fill"></i> Informasi Peminjaman:</div>
                                <ul class="mb-0 ps-3 text-secondary small">
                                    <li>Durasi peminjaman: <strong class="text-dark">7 hari</strong></li>
                                    <li>Denda keterlambatan: <strong class="text-dark">Rp 5.000/hari</strong></li>
                                    <li>Stok buku akan berkurang otomatis setelah peminjaman divalidasi.</li>
                                </ul>
                            </div>
                            
                            <hr class="text-muted opacity-25">
                            
                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                                    <i class="bi bi-save"></i> Proses Peminjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>