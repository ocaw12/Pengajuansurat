<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLER UMUM ---
use App\Http\Controllers\DashboardController;

// --- CONTROLLER MAHASISWA ---
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboard;
use App\Http\Controllers\Mahasiswa\PengajuanController;

// --- CONTROLLER STAFF JURUSAN ---
use App\Http\Controllers\StaffJurusan\DashboardController as StaffDashboard;
use App\Http\Controllers\StaffJurusan\ValidasiController;

// --- CONTROLLER PEJABAT ---
use App\Http\Controllers\Pejabat\DashboardController as PejabatDashboard;
use App\Http\Controllers\Pejabat\ApprovalController;

// --- CONTROLLER ADMIN AKADEMIK ---
use App\Http\Controllers\AdminAkademik\DashboardController as AdminAkademikDashboard;
use App\Http\Controllers\AdminAkademik\UserController;
use App\Http\Controllers\AdminAkademik\FakultasController;
use App\Http\Controllers\AdminAkademik\ProgramStudiController;
use App\Http\Controllers\AdminAkademik\MasterJabatanController;
use App\Http\Controllers\AdminAkademik\PejabatController;
use App\Http\Controllers\AdminAkademik\AdminStaffController;
use App\Http\Controllers\AdminAkademik\JenisSuratController;
use App\Http\Controllers\AdminAkademik\AlurApprovalController;


/*
|--------------------------------------------------------------------------
| Rute Publik & Autentikasi
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome'); // Halaman landing page
});

// Rute bawaan Breeze/Jetstream (login, register, forgot password, dll)
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Rute Utama (Wajib Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard utama yang akan me-redirect berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | 🧑‍🎓 GRUP MAHASISWA
    |--------------------------------------------------------------------------
    */
     Route::middleware(['role:mahasiswa'])
         ->prefix('mahasiswa')
         ->name('mahasiswa.')
         ->group(function () {

        Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('dashboard');
        
        // Rute untuk MENAMPILKAN form (Anda sudah punya ini)
        Route::get('/pengajuan/buat', [PengajuanController::class, 'create'])->name('pengajuan.create');
        
        // Rute untuk MENYIMPAN form (INI YANG HILANG)
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
        
        // Rute untuk API form dinamis
        Route::get('/api/form-schema/{jenis_surat}', [PengajuanController::class, 'getFormSchema'])->name('api.form-schema');
        
        // Rute untuk detail (Anda akan butuh ini nanti)
        Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    });

    /*
    |--------------------------------------------------------------------------
    | 🧑‍💼 GRUP STAFF JURUSAN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:staff jurusan')
         ->prefix('staff')
         ->name('staff.')
         ->group(function () {
        
        Route::get('/dashboard', [StaffDashboard::class, 'index'])->name('dashboard'); // Antrian validasi

        // web.php (Perbaikan)
        Route::get('/validasi/{pengajuan}', [ValidasiController::class, 'show'])->name('validasi.show');
        Route::post('/validasi/{pengajuan}', [ValidasiController::class, 'validateSubmission'])->name('validasi.submit');
        Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi.index');
        Route::post('/tandai-diambil/{pengajuan}', [ValidasiController::class, 'markAsDiambil'])->name('validasi.diambil');
        // web.php (Perbaikan)

        // Ganti 'antrianCetak' menjadi 'indexCetak'
        Route::get('/antrian-cetak', [ValidasiController::class, 'indexCetak'])->name('validasi.cetak');

        // Ganti 'tandaiDiambil' menjadi 'markAsDiambil'
        Route::post('/tandai-diambil/{pengajuan}', [ValidasiController::class, 'markAsDiambil'])->name('validasi.diambil');

        });

    /*
    |--------------------------------------------------------------------------
    | 👨‍⚖️ GRUP PEJABAT
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:pejabat')
         ->prefix('pejabat')
         ->name('pejabat.')
         ->group(function () {
        
        Route::get('/dashboard', [PejabatDashboard::class, 'index'])->name('dashboard'); // Antrian approval
        
        // Rute untuk approval
        Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
        // Rute untuk approval
        Route::get('/approval/{approval}', [ApprovalController::class, 'show'])->name('approval.show');
        Route::post('/approval/{approval}', [ApprovalController::class, 'approveOrReject'])->name('approval.submit');
    });

    /*
    |--------------------------------------------------------------------------
    | 👑 GRUP ADMIN AKADEMIK
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin akademik')
         ->prefix('admin-akademik')
         ->name('admin_akademik.')
         ->group(function () {
        
        Route::get('/dashboard', [AdminAkademikDashboard::class, 'index'])->name('dashboard');

        // CRUD untuk semua data master
        Route::resource('users', UserController::class); // Manajemen Akun User
        Route::resource('fakultas', FakultasController::class);
        Route::resource('program-studi', ProgramStudiController::class);
        Route::resource('master-jabatan', MasterJabatanController::class);
        Route::resource('pejabat', PejabatController::class); // Manajemen Profil Pejabat
        Route::resource('admin-staff', AdminStaffController::class); // Manajemen Profil Staff

        // CRUD untuk Jenis Surat (Katalog)
        Route::resource('jenis-surat', JenisSuratController::class);

        // CRUD untuk Alur Approval (Nested di dalam Jenis Surat)
        // Ini akan membuat rute seperti:
        // /admin-akademik/jenis-surat/{jenis_surat}/alur/create
        // /admin-akademik/alur/{alur}/edit
        Route::resource('jenis-surat.alur', AlurApprovalController::class)->shallow();
    });

});
