<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function(){
    Route::resource('jurusan', JurusanController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);

    Route::post('pinjaman/{id}/kembalikan', [PinjamanController::class, 'kembalikan'])->name('pinjaman.kembalikan');
    
    Route::resource('pinjaman', PinjamanController::class);

});
Route::get('/', [DashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware(['auth', AdminMiddleware::class]);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
