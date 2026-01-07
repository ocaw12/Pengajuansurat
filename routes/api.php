<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Mahasiswa\PengajuanController;

// Tes Route Sederhana (Untuk memastikan API jalan)
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// --- Auth Routes ---
Route::post('/login', [AuthController::class, 'login']);

// --- Protected Routes ---
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('mahasiswa')->group(function () {
        Route::get('/jenis-surat', [PengajuanController::class, 'getJenisSurat']);
        Route::get('/form-schema/{id}', [PengajuanController::class, 'getFormSchema']);
        Route::get('/pengajuan', [PengajuanController::class, 'index']);
        Route::post('/pengajuan', [PengajuanController::class, 'store']);
        Route::get('/pengajuan/{id}', [PengajuanController::class, 'show']);
        Route::get('/pengajuan/{id}/download', [PengajuanController::class, 'download']);
    });
});