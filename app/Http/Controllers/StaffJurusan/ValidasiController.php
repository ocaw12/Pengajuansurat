<?php

namespace App\Http\Controllers\StaffJurusan;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Events\PengajuanDivalidasiEvent; // PENTING
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Notifications\SuratSiapDiambilNotification; // <-- Import Notifikasi WA
use Illuminate\Support\Facades\Log; // <-- IMPORT LOG

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
            
            return redirect()->route('staff_jurusan.validasi.index')->with('success', 'Pengajuan telah ditolak dan dikembalikan ke mahasiswa.');

        } else {
            // Aksi Validasi (Setuju)
            $pengajuan->update([
                'status_pengajuan' => 'divalidasi_admin',
                'admin_validator_id' => Auth::user()->adminStaff->id,
                'catatan_revisi' => null, // Hapus catatan revisi jika ada
            ]);

            // 🔥 MEMICU EVENT untuk membuat alur approval otomatis
            PengajuanDivalidasiEvent::dispatch($pengajuan);

            return redirect()->route('staff_jurusan.validasi.index')->with('success', 'Pengajuan berhasil divalidasi dan diteruskan ke pejabat.');
        }
    }

    /**
     * Menampilkan daftar surat yang 'siap_diambil' (antrian cetak).
     */
    public function indexCetak(): View
    {
        $programStudiId = Auth::user()->adminStaff->program_studi_id;

        $pengajuans = PengajuanSurat::where('status_pengajuan', 'siap_dicetak') // <-- STATUS BARU
            ->where('metode_pengambilan', 'cetak')
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->with('mahasiswa', 'jenisSurat')
            ->latest('updated_at')
            ->get();
            
        // View ini adalah "Antrian Perlu Dicetak"
        return view('staff_jurusan.cetak.index', compact('pengajuans'));
    }

    public function tandaiSiapDiambil(PengajuanSurat $pengajuan): RedirectResponse
    {
        // Otorisasi
        $this->authorizeStaff($pengajuan);

        // Cek status
        if ($pengajuan->status_pengajuan !== 'siap_dicetak' || $pengajuan->metode_pengambilan !== 'cetak') {
             return redirect()->route('staff_jurusan.cetak.index')->with('error', 'Status surat tidak valid untuk aksi ini.');
        }

        // 1. Update status
        $pengajuan->update([
            'status_pengajuan' => 'siap_diambil',
        ]);

        // 2. Kirim Notifikasi WA ke Mahasiswa
        try {
            $pengajuan->loadMissing('mahasiswa'); 
            if($pengajuan->mahasiswa->no_telepon) {
                $pengajuan->mahasiswa->notify(new SuratSiapDiambilNotification($pengajuan));
            } else {
                 Log::warning('Gagal kirim WA: Mahasiswa ID ' . $pengajuan->mahasiswa_id . ' tidak punya nomor telepon.');
                 return redirect()->route('staff_jurusan.cetak.index')->with('warning', 'Status berhasil diubah, tapi GAGAL kirim notifikasi WA (No HP tidak ada).');
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim WA notifikasi siap diambil: ' . $e->getMessage());
            return redirect()->route('staff_jurusan.cetak.index')->with('warning', 'Status berhasil diubah, tapi GAGAL kirim notifikasi WA (Error sistem).');
        }

        return redirect()->route('staff_jurusan.cetak.index')->with('success', 'Status diubah ke "Siap Diambil" dan notifikasi WA telah dikirim ke mahasiswa.');
    }

    public function indexPengambilan(): View
    {
         $programStudiId = Auth::user()->adminStaff->program_studi_id;

        $pengajuans = PengajuanSurat::where('status_pengajuan', 'siap_diambil') // <-- Status 'siap_diambil'
            ->where('metode_pengambilan', 'cetak') 
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->with('mahasiswa', 'jenisSurat')
            ->latest('updated_at')
            ->get();
            
        // View baru untuk antrian pengambilan
        return view('staff_jurusan.cetak.pengambilan', compact('pengajuans'));
    }

    /**
     * Menandai surat cetak sebagai 'sudah_diambil'.
     */
    public function markAsDiambil(PengajuanSurat $pengajuan): RedirectResponse
    {
        // Otorisasi
        $this->authorizeStaff($pengajuan);
        
         if ($pengajuan->status_pengajuan !== 'siap_diambil' || $pengajuan->metode_pengambilan !== 'cetak') {
             return redirect()->route('staff_jurusan.cetak.pengambilan')->with('error', 'Status surat tidak valid.');
         }

        $pengajuan->update([
            'status_pengajuan' => 'sudah_diambil',
            'tanggal_diambil' => now(),
        ]);

        return redirect()->route('staff_jurusan.cetak.pengambilan')->with('success', 'Surat telah ditandai sebagai "Sudah Diambil".');
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
