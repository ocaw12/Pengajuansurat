<?php

namespace App\Http\Controllers\StaffJurusan;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard Staff Jurusan:
     * - Ringkasan: pending validasi, perlu dicetak, siap diambil
     * - List singkat: antrian validasi & perlu dicetak
     */
    public function index(): View
    {
        // Pastikan user punya profil adminStaff
        $adminStaff = Auth::user()->adminStaff;

        if (!$adminStaff) {
            abort(403, 'Akses ditolak. Profil admin staff tidak ditemukan.');
        }

        $programStudiId = $adminStaff->program_studi_id;

        // ==========================
        //   RINGKASAN STATISTIK
        // ==========================

        // 1. Antrian validasi (status: pending)
        $totalPendingValidasi = PengajuanSurat::where('status_pengajuan', 'pending')
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->count();

        // 2. Perlu dicetak
        //    -> samakan dengan ValidasiController::indexCetak
        //    -> status: siap_dicetak, metode: cetak
        $totalPerluDicetak = PengajuanSurat::where('status_pengajuan', 'siap_dicetak')
            ->where('metode_pengambilan', 'cetak')
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->count();

        // 3. Siap diambil (status: siap_diambil, metode: cetak)
        $totalSiapDiambil = PengajuanSurat::where('status_pengajuan', 'siap_diambil')
            ->where('metode_pengambilan', 'cetak')
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->count();

        // ==========================
        //   LIST ANTRIAN TERBARU
        // ==========================

        // List kecil untuk antrian validasi
        $antrianValidasi = PengajuanSurat::where('status_pengajuan', 'pending')
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->with(['mahasiswa.programStudi', 'jenisSurat'])
            ->latest('tanggal_pengajuan')
            ->take(5)
            ->get();

        // List kecil untuk perlu dicetak
        // (status: siap_dicetak, metode: cetak) -> konsisten dengan halaman "Perlu Dicetak"
        $antrianPerluDicetak = PengajuanSurat::where('status_pengajuan', 'siap_dicetak')
            ->where('metode_pengambilan', 'cetak')
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->with(['mahasiswa.programStudi', 'jenisSurat'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('staff_jurusan.dashboard', compact(
            'totalPendingValidasi',
            'totalPerluDicetak',
            'totalSiapDiambil',
            'antrianValidasi',
            'antrianPerluDicetak'
        ));
    }
}
