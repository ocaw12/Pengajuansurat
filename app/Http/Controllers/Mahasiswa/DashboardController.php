<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $mahasiswa = Auth::user()->mahasiswa;

        // Query dasar
        $query = PengajuanSurat::where('mahasiswa_id', $mahasiswa->id);

        // Angka ringkasan dashboard
        $totalPengajuan = $query->count();
        $totalSelesai   = (clone $query)->where('status_pengajuan', 'selesai')->count();
        $totalDitolak   = (clone $query)->whereIn('status_pengajuan', ['ditolak', 'perlu_revisi'])->count();

        // Pengajuan terbaru
        $pengajuanTerbaru = (clone $query)
            ->latest('tanggal_pengajuan')
            ->take(5)
            ->get();

        // KIRIM SEMUA VARIABEL KE VIEW
        return view('mahasiswa.dashboard', compact(
            'totalPengajuan',
            'totalSelesai',
            'totalDitolak',
            'pengajuanTerbaru'
        ));
    }
}
