<?php
namespace App\Http\Controllers;

use App\Models\ApprovalPejabat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    /**
     * Menampilkan halaman verifikasi berdasarkan kode verifikasi
     */
    public function show(Request $request, $kode_verifikasi)
    {
        // Ambil data ApprovalPejabat berdasarkan kode_verifikasi
        $approval = ApprovalPejabat::where('kode_verifikasi', $kode_verifikasi)->first();

        if (!$approval) {
            abort(404, 'Verifikasi tidak ditemukan.');
        }

        // Ambil data pengajuan surat dan pejabat terkait
        $pengajuanSurat = $approval->pengajuanSurat;
        $pejabat = $approval->pejabat;

        // Kirim data ke view untuk ditampilkan
        return view('verifikasi.show', [
            'approval' => $approval,
            'pengajuanSurat' => $pengajuanSurat,
            'pejabat' => $pejabat,
        ]);
    }
}
