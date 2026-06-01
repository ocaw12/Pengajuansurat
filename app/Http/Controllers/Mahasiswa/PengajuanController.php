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

        $pengajuan_aktif_ids = PengajuanSurat::where('mahasiswa_id', Auth::user()->mahasiswa->id)
            ->whereNotIn('status_pengajuan', ['selesai', 'ditolak'])
            ->pluck('jenis_surat_id')
            ->toArray();

        return view('mahasiswa.pengajuan.create', compact('jenis_surats', 'pengajuan_aktif_ids'));
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
            'data_pendukung' => 'nullable|array'
        ]);

        // Cek pengajuan aktif untuk jenis surat yang sama
        $mahasiswaId = Auth::user()->mahasiswa->id;
        $pengajuanAktif = PengajuanSurat::where('mahasiswa_id', $mahasiswaId)
            ->where('jenis_surat_id', $request->jenis_surat_id)
            ->whereNotIn('status_pengajuan', ['selesai', 'ditolak'])
            ->exists();

        if ($pengajuanAktif) {
            return back()
                ->withInput()
                ->withErrors(['jenis_surat_id' => 'Anda masih memiliki pengajuan aktif untuk jenis surat ini. Silakan tunggu hingga proses selesai.']);
        }

        try {
            $dataPendukung = [];

            if ($request->has('data_pendukung')) {
                foreach ($request->data_pendukung as $key => $value) {
                    if ($request->hasFile("data_pendukung.$key")) {
                        $file = $request->file("data_pendukung.$key");
                        $path = $file->store('dokumen_pengajuan', 'public');
                        $dataPendukung[$key] = $path;
                    } else {
                        $dataPendukung[$key] = $value;
                    }
                }
            }

            PengajuanSurat::create([
                'mahasiswa_id' => Auth::user()->mahasiswa->id,
                'jenis_surat_id' => $request->jenis_surat_id,
                'keperluan' => $request->keperluan,
                'metode_pengambilan' => $request->metode_pengambilan,
                'data_pendukung' => $dataPendukung,
                'status_pengajuan' => 'pending',
                'tanggal_pengajuan' => now(),
            ]);

            return redirect()
                ->route('mahasiswa.dashboard')
                ->with('success', 'Surat berhasil diajukan. Silakan tunggu validasi staff.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan saat menyimpan pengajuan. Coba lagi.')
                ->withInput();
        }
    }

    /**
     * Menampilkan detail dan status tracking satu pengajuan.
     */
    public function show(PengajuanSurat $pengajuan): View
    {
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403, 'Akses Ditolak');
        }

        $pengajuan->load(
            'jenisSurat', 
            'adminValidator', 
            'approvalPejabats.pejabat.masterJabatan'
        );

        return view('mahasiswa.pengajuan.show', compact('pengajuan'));
    }

    private function authorizeStaff(PengajuanSurat $pengajuan): void
    {
        if (!Auth::user()->adminStaff) {
            abort(403, 'Akses ditolak. Profil admin staff tidak ditemukan.');
        }
        $programStudiId = Auth::user()->adminStaff->program_studi_id;

        if (!$pengajuan->mahasiswa) {
            abort(500, 'Error: Data mahasiswa tidak dapat dimuat untuk pengajuan ini. ID Mahasiswa: ' . $pengajuan->mahasiswa_id);
        }

        if ($pengajuan->mahasiswa->program_studi_id != $programStudiId) {
            abort(403, 'Akses ditolak. Anda tidak berwenang untuk surat dari prodi ini.');
        }
    }
}