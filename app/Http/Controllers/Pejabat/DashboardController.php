<?php

namespace App\Http\Controllers\Pejabat;

use App\Http\Controllers\Controller;
use App\Models\ApprovalPejabat;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pejabatId = Auth::user()->pejabat->id;

        // Ringkasan statistik
        $totalMenunggu = ApprovalPejabat::where('pejabat_id', $pejabatId)
            ->where('status_approval', 'menunggu')
            ->count();

        $totalDisetujui = ApprovalPejabat::where('pejabat_id', $pejabatId)
            ->where('status_approval', 'disetujui')
            ->count();

        $totalDitolak = ApprovalPejabat::where('pejabat_id', $pejabatId)
            ->where('status_approval', 'ditolak')
            ->count();

        // Antrian terbaru (yang masih menunggu & sudah lolos level sebelumnya)
        $antrianTerbaru = ApprovalPejabat::where('pejabat_id', $pejabatId)
            ->where('status_approval', 'menunggu')
            ->with(['pengajuanSurat.mahasiswa.programStudi', 'pengajuanSurat.jenisSurat'])
            ->get()
            ->filter(function ($approval) {
                if ($approval->urutan_approval == 1) {
                    return true;
                }

                $previousApprovalStatus = ApprovalPejabat::where('pengajuan_surat_id', $approval->pengajuan_surat_id)
                    ->where('urutan_approval', $approval->urutan_approval - 1)
                    ->value('status_approval');

                return $previousApprovalStatus == 'disetujui';
            })
            ->take(5);

        // Riwayat terbaru
        $riwayatTerbaru = ApprovalPejabat::where('pejabat_id', $pejabatId)
            ->whereIn('status_approval', ['disetujui', 'ditolak'])
            ->with(['pengajuanSurat.mahasiswa.programStudi', 'pengajuanSurat.jenisSurat'])
            ->orderByDesc('tanggal_approval')
            ->take(5)
            ->get();

        return view('pejabat.dashboard', compact(
            'totalMenunggu',
            'totalDisetujui',
            'totalDitolak',
            'antrianTerbaru',
            'riwayatTerbaru'
        ));
    }
}
