<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPinjamanController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function(){
    Route::resource('jurusan', JurusanController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);

    Route::get('pinjaman/pending', [PinjamanController::class, 'pending'])->name('pinjaman.pending');
    Route::post('pinjaman/{id}/approve', [PinjamanController::class, 'approve'])->name('pinjaman.approve');
    Route::post('pinjaman/{id}/reject', [PinjamanController::class, 'reject'])->name('pinjaman.reject');
    Route::post('pinjaman/{id}/kembalikan', [PinjamanController::class, 'kembalikan'])->name('pinjaman.kembalikan');
    Route::resource('pinjaman', PinjamanController::class);

});

Route::middleware('auth')->group(function () {
    Route::get('/pinjaman/request', [UserPinjamanController::class, 'create'])->name('pinjaman.request.create');
    Route::post('/pinjaman/request', [UserPinjamanController::class, 'store'])->name('pinjaman.request.store');
    Route::get('/pinjaman/my', [UserPinjamanController::class, 'index'])->name('pinjaman.my');
    Route::post('/pinjaman/{id}/kembalikan', [UserPinjamanController::class, 'kembalikan'])->name('pinjaman.user.kembalikan');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [DashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware(['auth', AdminMiddleware::class]);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
