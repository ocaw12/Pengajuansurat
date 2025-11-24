<?php

namespace App\Http\Controllers\AdminAkademik;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\Pejabat;
use App\Models\AdminStaff;
use App\Models\Mahasiswa;
use Illuminate\View\View;

class DashboardController extends Controller   // ← INI PENTING
{
    public function index(): View
    {
        $totalJenisSurat  = JenisSurat::count();
        $totalPejabat     = Pejabat::count();
        $totalAdminStaff  = AdminStaff::count();
        $totalMahasiswa   = Mahasiswa::count();

        return view('admin_akademik.dashboard.index', compact(
            'totalJenisSurat',
            'totalPejabat',
            'totalAdminStaff',
            'totalMahasiswa'
        ));
    }
}
