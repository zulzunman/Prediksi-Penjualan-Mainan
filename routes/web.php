<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

// 🔐 Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🌐 Semua route yang butuh login (semua user sudah admin)
Route::middleware(['auth'])->group(function () {

    // 🏠 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 📦 Barang (semua admin bisa akses)
    Route::resource('barang', BarangController::class);

    // 💰 Penjualan (semua admin bisa akses)
    Route::resource('penjualan', PenjualanController::class);
    Route::get('/penjualan/barang/{id}', [PenjualanController::class, 'getBarang'])
        ->name('penjualan.getBarang');
    Route::get('/penjualan-template/download', [PenjualanController::class, 'downloadTemplate'])
    ->name('penjualan.template');
    Route::post('/penjualan-import', [PenjualanController::class, 'import'])
        ->name('penjualan.import');
});
