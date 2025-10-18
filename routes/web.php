<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route untuk login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// ---- TAMBAHKAN ROUTE LOGOUT DI SINI ----
Route::post('/logout', function (Request $request) {
    Auth::logout(); // Logout user

    $request->session()->invalidate(); // Hapus data sesi

    $request->session()->regenerateToken(); // Buat token CSRF baru

    return redirect('/login'); // Arahkan ke halaman login
})->name('logout'); // Beri nama 'logout'
// -----------------------------------------

// Admin Dashboard (hanya bisa diakses setelah login)
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');  // Tampilkan halaman dashboard admin
    })->name('admin.dashboard');
});

