<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\StaffJurusanController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (LOGIN & LOGOUT)
|--------------------------------------------------------------------------
*/

// Halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Proses logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN AKADEMIK ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard Admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // CRUD Fakultas
    Route::resource('fakultas', FakultasController::class);

    // CRUD Prodi
    Route::resource('prodi', ProdiController::class);

    // CRUD Staff Jurusan (khusus Admin Akademik)
    Route::resource('staff', StaffJurusanController::class)->except(['show']);
    // except('show') supaya /staff/dashboard gak bentrok sama /staff/{id}
});

/*
|--------------------------------------------------------------------------
| STAF JURUSAN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard Staff Jurusan
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');
});
