<?php

namespace App\Http\Controllers\AdminAkademik;

use App\Http\Controllers\Controller; // Pastikan use Controller
use Illuminate\Http\Request;
use Illuminate\View\View; // Gunakan View

class DashboardController extends Controller // Extends Controller
{
    /**
     * Menampilkan halaman dashboard Admin Akademik.
     */
    public function index(): View
    {
        // Untuk saat ini, hanya tampilkan view sederhana.
        // Nanti bisa ditambahkan data statistik (jumlah user, pengajuan, dll.)
        return view('admin_akademik.dashboard');
    }
}
