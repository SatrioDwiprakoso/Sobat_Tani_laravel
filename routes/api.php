<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AutentikasiController;
use App\Http\Controllers\Api\KonsultasiController;
use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\SurveiController;
use App\Http\Controllers\Api\CuacaController;

// Route Publik
Route::post('/daftar', [AutentikasiController::class, 'register']);
Route::post('/masuk', [AutentikasiController::class, 'login']);
Route::get('/cuaca', [CuacaController::class, 'index']);

// Route Protected (Sanctum)
Route::get('/artikel', [\App\Http\Controllers\Api\ArtikelController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/keluar', [AutentikasiController::class, 'logout']);
    Route::get('/profil', [AutentikasiController::class, 'profil']);
    Route::post('/profil', [AutentikasiController::class, 'updateProfil']);

    // Konsultasi CRUD & Tanggapan
    Route::get('/konsultasi', [KonsultasiController::class, 'index']);
    Route::post('/konsultasi', [KonsultasiController::class, 'store']);
    Route::get('/konsultasi/{id}', [KonsultasiController::class, 'show']);
    Route::post('/konsultasi/{id}/pesan', [KonsultasiController::class, 'kirimPesan']);
    Route::put('/konsultasi/{id}/selesai', [KonsultasiController::class, 'tandaiSelesai']);
    Route::put('/konsultasi/{id}/tanggapan', [KonsultasiController::class, 'berikanTanggapan']);

    // Pengaduan CRUD & Update Status
    Route::get('/pengaduan', [PengaduanController::class, 'index']);
    Route::post('/pengaduan', [PengaduanController::class, 'store']);
    Route::get('/pengaduan/{id}', [PengaduanController::class, 'show']);
    Route::put('/pengaduan/{id}/status', [PengaduanController::class, 'updateStatus']);

    // Survei
    Route::post('/survei', [SurveiController::class, 'store']);
    Route::get('/survei/ringkasan', [SurveiController::class, 'ringkasanSurvei']);
});


