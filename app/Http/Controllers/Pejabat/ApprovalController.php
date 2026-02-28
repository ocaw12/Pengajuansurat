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
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Gunakan Simple QR Code
use Illuminate\Support\Str;

class ApprovalController extends Controller
{
    /**
     * Menampilkan daftar surat yang menunggu approval pejabat ini (ANTRIAN).
     */
    public function index(): View
    {
        $pejabatId = Auth::user()->pejabat->id;

        $antrians = ApprovalPejabat::where('pejabat_id', $pejabatId)
            ->where('status_approval', 'menunggu')
            ->whereHas('pengajuanSurat', function ($q) {
                $q->where('status_pengajuan', 'menunggu_pejabat');
            })
            ->with('pengajuanSurat.mahasiswa.programStudi', 'pengajuanSurat.jenisSurat')
            ->get()
            ->filter(function ($approval) {
                if ($approval->urutan_approval == 1) {
                    return true;
                }

                $previousApprovalStatus = ApprovalPejabat::where('pengajuan_surat_id', $approval->pengajuan_surat_id)
                    ->where('urutan_approval', $approval->urutan_approval - 1)
                    ->value('status_approval');

                return $previousApprovalStatus == 'disetujui';
            });

        return view('pejabat.approval.antrian', compact('antrians'));
    }

    /**
     * Menampilkan RIWAYAT approval pejabat (sudah disetujui / ditolak).
     */
    public function riwayat(): View
    {
        $pejabatId = Auth::user()->pejabat->id;

        $riwayats = ApprovalPejabat::where('pejabat_id', $pejabatId)
            ->whereIn('status_approval', ['disetujui', 'ditolak']) // riwayat = yg sudah diproses
            ->with([
                'pengajuanSurat.mahasiswa.programStudi',
                'pengajuanSurat.jenisSurat',
            ])
            ->orderByDesc('tanggal_approval') // pakai tanggal_approval biar urut
            ->get();

        return view('pejabat.approval.riwayat', compact('riwayats'));
    }

    /**
     * Menampilkan detail surat untuk ditinjau pejabat.
     */
    public function show(ApprovalPejabat $approval): View
    {
        if ((int)$approval->pejabat_id !== (int)Auth::user()?->pejabat?->id) {
            abort(403, 'Akses Ditolak. Anda tidak ditugaskan untuk approval ini.');
        }

        $approval->load(['pengajuanSurat.mahasiswa.programStudi', 'pengajuanSurat.jenisSurat']);
        return view('pejabat.approval.show', compact('approval'));
    }

    /**
     * Memproses aksi Setuju atau Tolak.
     */
    public function approveOrReject(Request $request, ApprovalPejabat $approval): RedirectResponse
    {
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

            $pengajuan->update([
                'status_pengajuan' => 'ditolak',
                'catatan_revisi' => "Ditolak oleh {$approval->pejabat->masterJabatan->nama_jabatan}: " . $request->catatan_pejabat,
            ]);

            return redirect()->route('pejabat.approval.antrian')->with('success', 'Pengajuan telah ditolak.');
        } else {
            // Aksi Setuju
            $approval->update([
                'status_approval' => 'disetujui',
                'tanggal_approval' => now(),
                'catatan_pejabat' => $request->catatan_pejabat,
            ]);

            // 1. Generate kode verifikasi unik untuk surat
            $kodeVerifikasi = Str::random(10);

            // 2. Buat URL verifikasi untuk surat dengan waktu approval
            $waktuApproval = now()->format('Y-m-d H:i:s');
            $urlVerifikasi = 'http://10.75.168.126:8000/verifikasi/'.$kodeVerifikasi.'?pengajuan_surat_id='.$approval->pengajuan_surat_id.'&pejabat_id='.$approval->pejabat_id;

            // 3. Generate QR Code menggunakan simple-qrcode
            $qrCode = QrCode::format('png')->size(200)->generate($urlVerifikasi);

            // Tentukan path untuk menyimpan QR Code
            $pathQr = 'qr/' . $approval->pejabat_id . '-' . $kodeVerifikasi . '.png';

            // Menyimpan QR code ke public/storage folder
            \Storage::disk('public')->put($pathQr, $qrCode); // Menyimpan ke storage public

            // 4. Simpan QR code dan kode verifikasi di database
            $approval->update([
                'path_qr' => $pathQr,
                'kode_verifikasi' => $kodeVerifikasi,
                'tanggal_ttd' => now(),
            ]);

            // Cek apakah ini approval terakhir
            $semuaApproval = $pengajuan->approvalPejabats()->count();
            $approvalDisetujui = $pengajuan->approvalPejabats()->where('status_approval', 'disetujui')->count();

            if ($semuaApproval == $approvalDisetujui) {
                // Ini adalah approval terakhir
                // Memicu event untuk generate PDF
                ApprovalSelesaiEvent::dispatch($pengajuan);

                return redirect()->route('pejabat.approval.antrian')->with('success', 'Surat berhasil disetujui. PDF sedang digenerate.');
            } else {
                // Masih ada level approval berikutnya
                return redirect()->route('pejabat.approval.antrian')->with('success', 'Surat berhasil disetujui dan diteruskan ke level berikutnya.');
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
