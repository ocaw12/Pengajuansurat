<?php

namespace App\Http\Controllers\StaffJurusan;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Notifications\SuratSiapDiambilNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class CetakController extends Controller
{
    /**
     * Menampilkan daftar surat yang 'selesai' & 'cetak' (Antrian Perlu Dicetak).
     */
    public function indexCetak(): View
    {
        $programStudiId = Auth::user()->adminStaff->program_studi_id;

        $pengajuans = PengajuanSurat::where('status_pengajuan', 'selesai') // <-- Status 'selesai'
            ->where('metode_pengambilan', 'cetak') // <-- Metode 'cetak'
            ->whereHas('mahasiswa', function ($query) use ($programStudiId) {
                $query->where('program_studi_id', $programStudiId);
            })
            ->with('mahasiswa', 'jenisSurat')
            ->latest('updated_at')
            ->get();
            
        // View ini adalah "Antrian Perlu Dicetak"
        return view('staff_jurusan.cetak.index', compact('pengajuans'));
    }

    /**
     * Aksi yang dipanggil saat Staff klik "Tandai Siap Diambil".
     * Ini akan mengubah status ke 'siap_diambil' DAN mengirim WA.
     */
    public function tandaiSiapDiambil(PengajuanSurat $pengajuan): RedirectResponse
    {
        // Otorisasi
        $this->authorizeStaff($pengajuan);

        // Cek status
        if ($pengajuan->status_pengajuan !== 'selesai' || $pengajuan->metode_pengambilan !== 'cetak') {
             return redirect()->route('staff_jurusan.cetak.index')->with('error', 'Status surat tidak valid untuk aksi ini.');
        }

        // 1. Update status
        $pengajuan->update([
            'status_pengajuan' => 'siap_diambil',
        ]);

        // 2. Kirim Notifikasi WA ke Mahasiswa
        try {
            // Pastikan relasi mahasiswa di-load jika belum
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

    /**
     * Menampilkan daftar surat yang 'siap_diambil' (Antrian Pengambilan).
     */
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
        
        // Cek status
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
        if (!Auth::user()->adminStaff) {
             abort(403, 'Akses ditolak. Profil admin staff tidak ditemukan.');
        }
        $programStudiId = Auth::user()->adminStaff->program_studi_id;

        $pengajuan->loadMissing('mahasiswa');
        if (!$pengajuan->mahasiswa) {
             abort(500, 'Error: Data mahasiswa tidak dapat dimuat.');
        }
        if ($pengajuan->mahasiswa->program_studi_id != $programStudiId) {
            abort(403, 'Akses ditolak. Anda tidak berwenang untuk surat prodi ini.');
        }
    }
}