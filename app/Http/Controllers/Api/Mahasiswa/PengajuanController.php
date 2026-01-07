<?php

namespace App\Http\Controllers\Api\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // <--- WAJIB TAMBAHKAN INI

class PengajuanController extends Controller
{
    /**
     * Mendapatkan daftar Jenis Surat untuk dropdown
     */
    public function getJenisSurat()
    {
        $jenis_surats = JenisSurat::orderBy('nama_surat', 'asc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $jenis_surats
        ]);
    }

    /**
     * Mendapatkan Form Schema dinamis berdasarkan ID Jenis Surat
     */
    public function getFormSchema($id)
    {
        $jenisSurat = JenisSurat::find($id);

        if (!$jenisSurat) {
            return response()->json(['success' => false, 'message' => 'Jenis surat tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $jenisSurat->form_schema // Mengembalikan JSON schema
        ]);
    }

    /**
     * Melihat riwayat pengajuan surat
     */
    public function index(Request $request)
    {
        // Pastikan user memiliki data mahasiswa
        $mahasiswa = $request->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        $pengajuan = PengajuanSurat::with(['jenisSurat'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest('tanggal_pengajuan')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pengajuan
        ]);
    }

    /**
     * Membuat pengajuan surat baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'keperluan' => 'required|string|min:5',
            'metode_pengambilan' => 'required|in:digital,cetak',
            'data_pendukung' => 'nullable' // Bisa array atau JSON string
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mahasiswa = $request->user()->mahasiswa;

            // Handle data_pendukung
            // Jika dikirim sebagai JSON string dari Flutter, decode dulu
            $dataPendukung = $request->data_pendukung;
            if (is_string($dataPendukung)) {
                $dataPendukung = json_decode($dataPendukung, true);
            }

            $pengajuan = PengajuanSurat::create([
                'mahasiswa_id' => $mahasiswa->id,
                'jenis_surat_id' => $request->jenis_surat_id,
                'keperluan' => $request->keperluan,
                'metode_pengambilan' => $request->metode_pengambilan,
                'data_pendukung' => $dataPendukung,
                'status_pengajuan' => 'pending',
                'tanggal_pengajuan' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Surat berhasil diajukan',
                'data' => $pengajuan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detail Pengajuan
     */
    public function show(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::with([
            'jenisSurat', 
            'approvalPejabats.pejabat.masterJabatan', // Untuk melihat tracking approval
            'adminValidator'
        ])->find($id);

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan'], 404);
        }

        // Security Check: Pastikan ini milik user yang login
        if ($pengajuan->mahasiswa_id !== $request->user()->mahasiswa->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $pengajuan
        ]);
    }
    public function download(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::find($id);

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan'], 404);
        }

        // 1. Cek Kepemilikan
        if ($pengajuan->mahasiswa_id !== $request->user()->mahasiswa->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // 2. Cek Status
        // Sesuaikan dengan status "final" di sistem Anda (misal: disetujui, selesai, atau dicetak)
        $validStatuses = ['disetujui', 'selesai', 'dicetak'];
        if (!in_array(strtolower($pengajuan->status_pengajuan), $validStatuses)) {
             return response()->json(['success' => false, 'message' => 'Surat belum siap atau belum disetujui'], 400);
        }
        
        // 3. Tentukan Lokasi File
        // Asumsi: File disimpan di folder 'storage/app/public/surat' dengan nama 'surat_{id}.pdf'
        // Jika Anda menyimpan path di database (misal kolom file_path), gunakan: $path = $pengajuan->file_path;
        $fileName = 'surat_' . $pengajuan->id . '.pdf';
        $path = 'public/surat/' . $fileName; 

        // Cek keberadaan file
        if (!Storage::exists($path)) {
             // Coba generate on-the-fly jika perlu, atau return error
             return response()->json(['success' => false, 'message' => 'File PDF belum digenerate oleh sistem'], 404);
        }

        // 4. Return Download Response
        return Storage::download($path, 'Surat_Keterangan_' . $pengajuan->id . '.pdf');
    }
}