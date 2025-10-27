<?php

namespace App\Http\Controllers\AdminAkademik;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAkademik\StoreJenisSuratRequest;
use App\Models\AlurApproval; // <-- TAMBAHKAN USE
use App\Models\JenisSurat;
use App\Models\MasterJabatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN USE DB
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class JenisSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua jenis surat.
     */
    public function index(): View
    {
        // Ambil semua jenis surat, urutkan berdasarkan nama
        $jenisSurats = JenisSurat::orderBy('nama_surat')->get();

        // Kirim data ke view
        return view('admin_akademik.jenis_surat.index', compact('jenisSurats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kategoriOptions = ['Akademik', 'Kemahasiswaan', 'Keuangan', 'Penelitian', 'Umum'];
        // Data diambil di sini
        $masterJabatans = MasterJabatan::orderBy('nama_jabatan')->get(); 

        // Data dikirim ke view di sini
        return view('admin_akademik.jenis_surat.create', compact('kategoriOptions', 'masterJabatans')); 
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(StoreJenisSuratRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // Gunakan Transaksi Database
        DB::beginTransaction();
        try {
             // 1. Buat Jenis Surat
             $formSchema = $validatedData['form_schema'] ?? null;
             $jenisSurat = JenisSurat::create([
                 'nama_surat' => $validatedData['nama_surat'],
                 'kode_surat' => $validatedData['kode_surat'],
                 'kategori' => $validatedData['kategori'],
                 'format_penomoran' => $validatedData['format_penomoran'],
                 'isi_template' => $validatedData['isi_template'],
                 'form_schema' => $formSchema,
                 'counter_nomor_urut' => 0,
                 'counter_tahun' => null, // Biarkan null, Job akan mengisinya
             ]);

             // 2. Simpan Alur Approval (jika ada)
             if (isset($validatedData['approvals']) && is_array($validatedData['approvals'])) {
                 $urutan = 1; // Mulai urutan dari 1
                 foreach ($validatedData['approvals'] as $approvalData) {
                     AlurApproval::create([
                         'jenis_surat_id' => $jenisSurat->id,
                         'urutan' => $urutan++,
                         'master_jabatan_id' => $approvalData['master_jabatan_id'],
                         'scope' => $approvalData['scope'],
                     ]);
                 }
             } else {
                 // Jika tidak ada approval yang dikirim (seharusnya dicegah validasi min:1)
                 throw new \Exception("Data approval tidak ditemukan atau tidak valid.");
             }

             // Jika semua berhasil, commit transaksi
             DB::commit();

             Log::info('Jenis surat baru berhasil dibuat beserta alur approvalnya: ' . $jenisSurat->nama_surat);

             return redirect()->route('admin_akademik.jenis-surat.index')
                              ->with('success', 'Jenis surat baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Jika terjadi error, rollback semua perubahan database
            DB::rollBack();
            Log::error('Gagal menyimpan jenis surat baru: ' . $e->getMessage());
            // Tambahkan trace error ke log untuk debugging lebih detail
            Log::error($e->getTraceAsString());
            return back()->withInput()
                         ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    // ... (metode show, edit, update, destroy akan dibuat nanti) ...

}

