<?php

namespace App\Http\Controllers\StaffJurusan;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Events\PengajuanDivalidasiEvent; // PENTING
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ValidasiController extends Controller
{
    /**
     * Menampilkan daftar pengajuan yang 'pending' (antrian validasi).
     */
    public function index(): View
    {
        $programStudiId = Auth::user()->adminStaff->program_studi_id;

        $pengajuans = PengajuanSurat::where('status_pengajuan', 'pending')
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->with('mahasiswa', 'jenisSurat')
            ->latest('tanggal_pengajuan')
            ->get();

        return view('staff_jurusan.validasi.index', compact('pengajuans'));
    }

    /**
     * Menampilkan detail pengajuan untuk divalidasi.
     */
    public function show(PengajuanSurat $pengajuan): View
    {
        // Otorisasi: Pastikan staff hanya bisa memvalidasi prodinya
        $this->authorizeStaff($pengajuan);

        return view('staff_jurusan.validasi.show', compact('pengajuan'));
    }

    /**
     * Memproses aksi Validasi atau Tolak.
     */
    public function validateSubmission(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        // Otorisasi
        $this->authorizeStaff($pengajuan);

        $request->validate([
            'action' => 'required|in:validasi,tolak',
            'catatan_revisi' => 'required_if:action,tolak|nullable|string|min:5',
        ]);

        if ($request->action == 'tolak') {
            // Aksi Tolak / Minta Revisi
            $pengajuan->update([
                'status_pengajuan' => 'perlu_revisi',
                'catatan_revisi' => $request->catatan_revisi,
                'admin_validator_id' => Auth::user()->adminStaff->id,
            ]);
            // TODO: Kirim notifikasi ke mahasiswa bahwa suratnya ditolak/perlu revisi
            
            return redirect()->route('staff.validasi.index')->with('success', 'Pengajuan telah ditolak dan dikembalikan ke mahasiswa.');

        } else {
            // Aksi Validasi (Setuju)
            $pengajuan->update([
                'status_pengajuan' => 'divalidasi_admin',
                'admin_validator_id' => Auth::user()->adminStaff->id,
                'catatan_revisi' => null, // Hapus catatan revisi jika ada
            ]);

            // 🔥 MEMICU EVENT untuk membuat alur approval otomatis
            PengajuanDivalidasiEvent::dispatch($pengajuan);

            return redirect()->route('staff.validasi.index')->with('success', 'Pengajuan berhasil divalidasi dan diteruskan ke pejabat.');
        }
    }

    /**
     * Menampilkan daftar surat yang 'siap_diambil' (antrian cetak).
     */
    public function indexCetak(): View
    {
        $programStudiId = Auth::user()->adminStaff->program_studi_id;

        $pengajuans = PengajuanSurat::where('status_pengajuan', 'siap_diambil')
            ->where('metode_pengambilan', 'cetak')
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->with('mahasiswa', 'jenisSurat')
            ->latest('updated_at')
            ->get();
            
        return view('staff_jurusan.cetak.index', compact('pengajuans'));
    }

    /**
     * Menandai surat cetak sebagai 'sudah_diambil'.
     */
    public function markAsDiambil(PengajuanSurat $pengajuan): RedirectResponse
    {
        // Otorisasi
        $this->authorizeStaff($pengajuan);

        $pengajuan->update([
            'status_pengajuan' => 'sudah_diambil',
            'tanggal_diambil' => now(),
        ]);

        return redirect()->route('staff.validasi.cetak')->with('success', 'Surat telah ditandai sebagai "Sudah Diambil".');
    }

    /**
     * Helper untuk otorisasi staff jurusan.
     */
    private function authorizeStaff(PengajuanSurat $pengajuan): void
    {
        $programStudiId = Auth::user()->adminStaff->program_studi_id;
        if ($pengajuan->mahasiswa->program_studi_id != $programStudiId) {
            abort(403, 'Akses ditolak. Anda tidak berwenang untuk surat ini.');
        }
    }
}
