<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PengajuanController extends Controller
{
    /**
     * Menampilkan form untuk membuat pengajuan baru.
     */
    public function create(): View
    {
        $jenis_surats = JenisSurat::orderBy('nama_surat', 'asc')->get();
        return view('mahasiswa.pengajuan.create', compact('jenis_surats'));
    }

    /**
     * (API) Mengambil skema form dinamis berdasarkan jenis surat.
     */
    public function getFormSchema(JenisSurat $jenisSurat): JsonResponse
    {
        return response()->json($jenisSurat->form_schema);
    }

    /**
     * Menyimpan pengajuan baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'keperluan' => 'required|string|min:10',
            'metode_pengambilan' => 'required|in:digital,cetak',
            'data_pendukung' => 'nullable|array' // Validasi data dinamis (jika perlu)
        ]);

        try {
            PengajuanSurat::create([
                'mahasiswa_id' => Auth::user()->mahasiswa->id,
                'jenis_surat_id' => $request->jenis_surat_id,
                'keperluan' => $request->keperluan,
                'metode_pengambilan' => $request->metode_pengambilan,
                'data_pendukung' => $request->data_pendukung, // Akan di-cast ke JSON oleh Model
                'status_pengajuan' => 'pending', // Status awal
                'tanggal_pengajuan' => now(),
            ]);

            return redirect()->route('mahasiswa.dashboard')->with('success', 'Surat berhasil diajukan. Silakan tunggu validasi staff.');

        } catch (\Exception $e) {
            // Log error
            return back()->with('error', 'Terjadi kesalahan saat menyimpan pengajuan. Coba lagi.')->withInput();
        }
    }

    /**
     * Menampilkan detail dan status tracking satu pengajuan.
     */
    public function show(PengajuanSurat $pengajuan): View
    {
        // Pastikan mahasiswa hanya bisa melihat surat miliknya
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403, 'Akses Ditolak');
        }

        // Eager load relasi untuk halaman detail
        $pengajuan->load(
            'jenisSurat', 
            'adminValidator', 
            'approvalPejabats.pejabat.masterJabatan'
        );

        return view('mahasiswa.pengajuan.show', compact('pengajuan'));
    }

    private function authorizeStaff(PengajuanSurat $pengajuan): void
        {
            // Pastikan user adalah admin staff dan memiliki profil
            if (!Auth::user()->adminStaff) {
                 abort(403, 'Akses ditolak. Profil admin staff tidak ditemukan.');
            }
            $programStudiId = Auth::user()->adminStaff->program_studi_id;
    
            // TAMBAHKAN PENGECEKAN INI:
            if (!$pengajuan->mahasiswa) {
                 // Jika relasi mahasiswa null, beri pesan error spesifik
                 abort(500, 'Error: Data mahasiswa tidak dapat dimuat untuk pengajuan ini. ID Mahasiswa: ' . $pengajuan->mahasiswa_id);
            }
    
            // Lanjutkan pengecekan program studi
            if ($pengajuan->mahasiswa->program_studi_id != $programStudiId) {
                abort(403, 'Akses ditolak. Anda tidak berwenang untuk surat dari prodi ini.');
            }
        }
}
