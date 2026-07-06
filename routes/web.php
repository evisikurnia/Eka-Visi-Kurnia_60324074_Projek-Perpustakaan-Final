<?php
 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;
 
// Public routes (tanpa auth)
Route::get('/', function () {
    return redirect()->route('login');
});
// Protected routes (dengan auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    // Laporan
    Route::middleware(['auth'])->group(function () {
    // ... route kamu yang lain ...
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 
    // Buku - CRUD
    Route::resource('buku', BukuController::class);
 
    // Anggota - CRUD
    Route::get('anggota/export', [AnggotaController::class, 'export'])->name('anggota.export');
    Route::get('anggota/search', [AnggotaController::class, 'search'])->name('anggota.search');
    Route::resource('anggota', AnggotaController::class);
    
    // Transaksi - CRUD + Custom routes
    Route::get('/transaksi/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
Route::get('/transaksi/cetak', [TransaksiController::class, 'cetakLaporan'])->name('transaksi.cetak');
    Route::resource('transaksi', TransaksiController::class);
    Route::post('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
});
 
require __DIR__.'/auth.php';