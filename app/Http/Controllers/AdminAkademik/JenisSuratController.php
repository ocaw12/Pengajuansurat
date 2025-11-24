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
public function edit(JenisSurat $jenisSurat): View
    {
        $kategoriOptions = ['Akademik', 'Kemahasiswaan', 'Keuangan', 'Penelitian', 'Umum'];
        $masterJabatans  = MasterJabatan::orderBy('nama_jabatan')->get();

        // Ambil alur approval dari tabel alur_approvals, urut berdasarkan 'urutan'
        $alurApprovals = AlurApproval::where('jenis_surat_id', $jenisSurat->id)
            ->orderBy('urutan')
            ->get();

        // Kita map jadi array sederhana untuk dipakai di Blade (JS)
        $approvalData = $alurApprovals->map(function ($item) {
            return [
                'master_jabatan_id' => $item->master_jabatan_id,
                'scope'             => $item->scope,
            ];
        })->values();

        return view('admin_akademik.jenis_surat.edit', compact(
            'jenisSurat',
            'kategoriOptions',
            'masterJabatans',
            'approvalData'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreJenisSuratRequest $request, JenisSurat $jenisSurat): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            // 1. Update data Jenis Surat
            $formSchema = $validatedData['form_schema'] ?? null;

            $jenisSurat->update([
                'nama_surat'        => $validatedData['nama_surat'],
                'kode_surat'        => $validatedData['kode_surat'],
                'kategori'          => $validatedData['kategori'],
                'format_penomoran'  => $validatedData['format_penomoran'],
                'isi_template'      => $validatedData['isi_template'],
                'form_schema'       => $formSchema,
                // counter_nomor_urut & counter_tahun dibiarkan, biar tidak ke-reset tiap edit
            ]);

            // 2. Hapus alur approval lama
            AlurApproval::where('jenis_surat_id', $jenisSurat->id)->delete();

            // 3. Simpan ulang alur approval baru
            if (isset($validatedData['approvals']) && is_array($validatedData['approvals'])) {
                $urutan = 1;
                foreach ($validatedData['approvals'] as $approvalData) {
                    AlurApproval::create([
                        'jenis_surat_id'   => $jenisSurat->id,
                        'urutan'           => $urutan++,
                        'master_jabatan_id'=> $approvalData['master_jabatan_id'],
                        'scope'            => $approvalData['scope'],
                    ]);
                }
            } else {
                throw new \Exception("Data approval tidak ditemukan atau tidak valid.");
            }

            DB::commit();

            Log::info('Jenis surat berhasil diupdate beserta alur approvalnya: ' . $jenisSurat->nama_surat);

            return redirect()
                ->route('admin_akademik.jenis-surat.index')
                ->with('success', 'Jenis surat berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengupdate jenis surat: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisSurat $jenisSurat): RedirectResponse
    {
        DB::beginTransaction();
        try {
            // Hapus dulu alur approval yang terkait
            AlurApproval::where('jenis_surat_id', $jenisSurat->id)->delete();

            // (Opsional) Cek dulu apakah jenis surat sedang dipakai di pengajuan_surat,
            // kalau iya bisa dikasih restriction. Untuk sekarang langsung delete.
            $nama = $jenisSurat->nama_surat;
            $jenisSurat->delete();

            DB::commit();

            Log::info('Jenis surat dihapus: ' . $nama);

            return redirect()
                ->route('admin_akademik.jenis-surat.index')
                ->with('success', 'Jenis surat berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus jenis surat: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()
                ->route('admin_akademik.jenis-surat.index')
                ->with('error', 'Terjadi kesalahan saat menghapus jenis surat: ' . $e->getMessage());
        }
    }
    public function show(JenisSurat $jenisSurat)
{
    $alurApprovals = $jenisSurat->alurApprovals()->orderBy('urutan')->get();

    return view('admin_akademik.jenis_surat.show', compact('jenisSurat', 'alurApprovals'));
}

}

