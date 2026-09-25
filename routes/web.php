<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminAuthController;
use App\Http\Controllers\Web\AdminKonsultasiController;
use App\Http\Controllers\Web\AdminPengaduanController;
use App\Http\Controllers\Web\AdminPenggunaController;
use App\Http\Controllers\Web\AdminSurveiController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () { return redirect()->route('admin.login'); });

Route::prefix('admin')->group(function () {
    // Login
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Admin Only
    Route::middleware([RoleMiddleware::class.':admin'])->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\Web\AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
                // Pengguna
        Route::get('/pengguna', [AdminPenggunaController::class, 'index'])->name('admin.pengguna.index');
        Route::post('/pengguna', [AdminPenggunaController::class, 'store'])->name('admin.pengguna.store');
        Route::put('/pengguna/{id}', [AdminPenggunaController::class, 'update'])->name('admin.pengguna.update');
        Route::delete('/pengguna/{id}', [AdminPenggunaController::class, 'destroy'])->name('admin.pengguna.destroy');

                // Survei
        Route::get('/survei', [\App\Http\Controllers\Web\AdminSurveiController::class, 'index'])->name('admin.survei.index');

        // Artikel
        Route::get('/artikel', [\App\Http\Controllers\Web\AdminArtikelController::class, 'index'])->name('admin.artikel.index');
        Route::post('/artikel', [\App\Http\Controllers\Web\AdminArtikelController::class, 'store'])->name('admin.artikel.store');
        Route::put('/artikel/{id}', [\App\Http\Controllers\Web\AdminArtikelController::class, 'update'])->name('admin.artikel.update');
        Route::delete('/artikel/{id}', [\App\Http\Controllers\Web\AdminArtikelController::class, 'destroy'])->name('admin.artikel.destroy');
    });

    // Admin and Penyuluh
    Route::middleware([RoleMiddleware::class.':admin,penyuluh'])->group(function() {
        // Konsultasi
        Route::get('/konsultasi', [AdminKonsultasiController::class, 'index'])->name('admin.konsultasi.index');
        Route::get('/konsultasi/{id}', [AdminKonsultasiController::class, 'show'])->name('admin.konsultasi.show');
        Route::post('/konsultasi/{id}/reply', [AdminKonsultasiController::class, 'reply'])->name('admin.konsultasi.reply');

        // Pengaduan
        Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('admin.pengaduan.index');
        Route::get('/pengaduan/{id}', [AdminPengaduanController::class, 'show'])->name('admin.pengaduan.show');
        Route::post('/pengaduan/{id}/status', [AdminPengaduanController::class, 'updateStatus'])->name('admin.pengaduan.status');
    });
});


