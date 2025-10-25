<?php

namespace App\Http\Controllers\Pejabat;

use App\Http\Controllers\Controller;
use App\Models\ApprovalPejabat;
use App\Models\PengajuanSurat;
use App\Events\ApprovalSelesaiEvent; // PENTING
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ApprovalController extends Controller
{
    /**
     * Menampilkan daftar surat yang menunggu approval pejabat ini.
     */
    public function index(): View
    {
        $pejabatId = Auth::user()->pejabat->id;

        // Query ini SANGAT PENTING.
        // 1. Ambil semua antrian 'menunggu' untuk pejabat ini.
        // 2. Filter: Hanya tampilkan jika urutan 1, ATAU urutan sebelumnya sudah 'disetujui'.
        $antrians = ApprovalPejabat::where('pejabat_id', $pejabatId)
            ->where('status_approval', 'menunggu')
            ->whereHas('pengajuanSurat', function ($q) {
                // Pastikan surat utamanya masih aktif (belum ditolak di level lain)
                $q->where('status_pengajuan', 'menunggu_pejabat'); 
            })
            ->with('pengajuanSurat.mahasiswa.programStudi', 'pengajuanSurat.jenisSurat')
            ->get()
            ->filter(function ($approval) {
                // Jika urutan pertama (1), selalu tampilkan.
                if ($approval->urutan_approval == 1) {
                    return true;
                }

                // Jika bukan urutan pertama, cek status approval sebelumnya
                $previousApprovalStatus = ApprovalPejabat::where('pengajuan_surat_id', $approval->pengajuan_surat_id)
                                          ->where('urutan_approval', $approval->urutan_approval - 1)
                                          ->value('status_approval');
                
                // Tampilkan hanya jika approval sebelumnya sudah 'disetujui'
                return $previousApprovalStatus == 'disetujui';
            });

        return view('pejabat.approval.index', compact('antrians'));
    }

    /**
     * Menampilkan detail surat untuk ditinjau pejabat.
     */
    public function show(ApprovalPejabat $approval): View
{
    // ---> INI BAGIAN YANG MENOLAK ANDA <---
    // Cek: Apakah ID pejabat di approval ini SAMA DENGAN ID profil pejabat yang sedang login?
    // Ganti !== menjadi !=
if ((int)$approval->pejabat_id !== (int)Auth::user()?->pejabat?->id) {
     abort(403, 'Akses Ditolak. Anda tidak ditugaskan untuk approval ini.');
}
    // ---> BATAS PENGECEKAN <---

    // Jika sama, lanjutkan memuat data dan tampilkan view
    $approval->load(['pengajuanSurat.mahasiswa.programStudi', 'pengajuanSurat.jenisSurat']);
    return view('pejabat.approval.show', compact('approval'));
}

    /**
     * Memproses aksi Setuju atau Tolak.
     */
    public function approveOrReject(Request $request, ApprovalPejabat $approval): RedirectResponse
    {
        // Otorisasi
        $this->authorizePejabat($approval);

        $request->validate([
            'action' => 'required|in:setuju,tolak',
            'catatan_pejabat' => 'required_if:action,tolak|nullable|string|min:5',
        ]);

        $pengajuan = $approval->pengajuanSurat;

        if ($request->action == 'tolak') {
            // Aksi Tolak
            $approval->update([
                'status_approval' => 'ditolak',
                'tanggal_approval' => now(),
                'catatan_pejabat' => $request->catatan_pejabat,
            ]);

            // Update surat utama
            $pengajuan->update([
                'status_pengajuan' => 'ditolak',
                'catatan_revisi' => "Ditolak oleh {$approval->pejabat->masterJabatan->nama_jabatan}: " . $request->catatan_pejabat,
            ]);

            // TODO: Kirim notifikasi ke mahasiswa
            return redirect()->route('pejabat.approval.index')->with('success', 'Pengajuan telah ditolak.');

        } else {
            // Aksi Setuju
            $approval->update([
                'status_approval' => 'disetujui',
                'tanggal_approval' => now(),
                'catatan_pejabat' => $request->catatan_pejabat,
            ]);

            // Cek apakah ini approval terakhir
            $semuaApproval = $pengajuan->approvalPejabats()->count();
            $approvalDisetujui = $pengajuan->approvalPejabats()->where('status_approval', 'disetujui')->count();

            if ($semuaApproval == $approvalDisetujui) {
                // INI ADALAH APPROVAL TERAKHIR
                // Update status sementara (Job akan meng-update lagi nanti)
                // $pengajuan->update(['status_pengajuan' => 'memproses_pdf']); 

                // 🔥 MEMICU EVENT untuk generate PDF
                ApprovalSelesaiEvent::dispatch($pengajuan);

                return redirect()->route('pejabat.approval.index')->with('success', 'Surat berhasil disetujui. PDF sedang digenerate.');
            } else {
                // Masih ada level approval berikutnya
                // TODO: Kirim notifikasi ke pejabat berikutnya
                return redirect()->route('pejabat.approval.index')->with('success', 'Surat berhasil disetujui dan diteruskan ke level berikutnya.');
            }
        }
    }

    /**
     * Helper untuk otorisasi pejabat.
     */
    private function authorizePejabat(ApprovalPejabat $approval): void
    {
        if ($approval->pejabat_id !== Auth::user()->pejabat->id) {
            abort(403, 'Akses ditolak.');
        }
        if ($approval->status_approval !== 'menunggu') {
             abort(403, 'Tindakan ini sudah diproses.');
        }
    }
}
