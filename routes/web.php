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
use App\Http\Controllers\StaffJurusan\CetakController; // <-- Pastikan ini di-import

// --- CONTROLLER PEJABAT ---
use App\Http\Controllers\Pejabat\DashboardController as PejabatDashboard;
use App\Http\Controllers\Pejabat\ApprovalController;

// --- CONTROLLER ADMIN AKADEMIK ---
use App\Http\Controllers\AdminAkademik\DashboardController as AdminAkademikDashboard;
use App\Http\Controllers\AdminAkademik\UserController;
use App\Http\Controllers\AdminAkademik\AdminFakultasController;
use App\Http\Controllers\AdminAkademik\ProdiController;
use App\Http\Controllers\AdminAkademik\MasterJabatanController;
use App\Http\Controllers\AdminAkademik\MahasiswaController;
use App\Http\Controllers\AdminAkademik\PejabatController;
use App\Http\Controllers\AdminAkademik\AdminStaffController;
use App\Http\Controllers\AdminAkademik\JenisSuratController;
use App\Http\Controllers\AdminAkademik\AlurApprovalController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\VerifikasiController;



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
Route::get('/preview/{fileName}', [DownloadController::class, 'preview'])->name('preview.surat');


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


        Route::get('/download/{filename}', [DownloadController::class, 'download'])->name('download.surat');


    });

    /*
    |--------------------------------------------------------------------------
    | 🧑‍💼 GRUP STAFF JURUSAN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:staff jurusan'])
         ->prefix('staff-jurusan') 
         ->name('staff_jurusan.') 
         ->group(function () {
        
        // Dashboard staff = halaman antrian validasi
        Route::get('/dashboard', [ValidasiController::class, 'index'])->name('dashboard'); 
        
        // Rute untuk validasi
        Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi.index');
        Route::get('/validasi/{pengajuan}', [ValidasiController::class, 'show'])->name('validasi.show');
        Route::post('/validasi/{pengajuan}', [ValidasiController::class, 'validateSubmission'])->name('validasi.submit');
        
        // Rute untuk fitur cetak (menggunakan ValidasiController)
        Route::get('/perlu-dicetak', [ValidasiController::class, 'indexCetak'])->name('cetak.index'); // <-- Antrian Perlu Cetak
        Route::post('/tandai-siap-diambil/{pengajuan}', [ValidasiController::class, 'tandaiSiapDiambil'])->name('cetak.siapDiambil'); // <-- Aksi kirim WA
        
        Route::get('/antrian-pengambilan', [ValidasiController::class, 'indexPengambilan'])->name('cetak.pengambilan'); // <-- Antrian Siap Diambil
        Route::post('/tandai-sudah-diambil/{pengajuan}', [ValidasiController::class, 'markAsDiambil'])->name('cetak.diambil'); // <-- Aksi Selesai
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
   // Rute admin akademik
    Route::middleware('role:admin akademik')
    ->prefix('admin-akademik')
    ->name('admin_akademik.')
    ->group(function () {

        Route::get('/dashboard', [AdminAkademikDashboard::class, 'index'])->name('dashboard');

        // CRUD untuk semua data master
        Route::resource('users', UserController::class); // Manajemen Akun User
        Route::resource('fakultas', AdminFakultasController::class)
             ->parameters(['fakultas' => 'fakultas']); // Menambahkan parameter fakultas

        // **PERBAIKI BAGIAN INI**
        // Rute Prodi (dengan namespace yang benar)
        Route::resource('prodi', ProdiController::class); // Pastikan route prodi sudah benar
       
// Di dalam file web.php
Route::resource('master-jabatan', MasterJabatanController::class);
        Route::resource('pejabat', PejabatController::class); // Manajemen Profil Pejabat
Route::resource('admin-staff', AdminStaffController::class); 
Route::get('admin-staff/{id}/edit', [AdminStaffController::class, 'edit'])->name('admin_akademik.admin-staff.edit');
Route::put('admin-staff/{id}', [AdminStaffController::class, 'update'])->name('admin_akademik.admin-staff.update');
Route::resource('mahasiswa', MahasiswaController::class); // CRUD untuk Mahasiswa
Route::put('mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('admin_akademik.mahasiswa.update');

        // CRUD untuk Jenis Surat (Katalog)
        Route::resource('jenis-surat', JenisSuratController::class);

        // CRUD untuk Alur Approval (Nested di dalam Jenis Surat)
        // Ini akan membuat rute seperti:
        // /admin-akademik/jenis-surat/{jenis_surat}/alur/create
        // /admin-akademik/alur/{alur}/edit
        Route::resource('jenis-surat.alur', AlurApprovalController::class)->shallow();
    });

Route::get('/download/{fileName}', [DownloadController::class, 'download'])->name('download.surat');

});
Route::get('verifikasi/{kode_verifikasi}', [VerifikasiController::class, 'show'])->name('verifikasi.show');

