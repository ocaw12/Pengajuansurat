<?php

namespace App\Http\Controllers\AdminAkademik;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAkademik\StoreJenisSuratRequest;
use App\Models\AlurApproval;
use App\Models\JenisSurat;
use App\Models\MasterJabatan;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Http\Requests\AdminAkademik\UpdateJenisSuratRequest;

class JenisSuratController extends Controller
{
    /**
     * Ambil data konteks tahun ajaran & semester aktif untuk dikirim ke view.
     */
    private function getAkademikContext(): array
    {
        $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
        $semesterAktif    = Semester::where('is_aktif', true)
            ->with('tahunAjaran')
            ->first();

        return [
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'semesterAktif'    => $semesterAktif,
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jenisSurats = JenisSurat::orderBy('nama_surat')->get();
        return view('admin_akademik.jenis_surat.index', compact('jenisSurats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kategoriOptions = ['Akademik', 'Kemahasiswaan', 'Keuangan', 'Penelitian', 'Umum'];
        $masterJabatans  = MasterJabatan::orderBy('nama_jabatan')->get();

        return view('admin_akademik.jenis_surat.create', array_merge(
            compact('kategoriOptions', 'masterJabatans'),
            $this->getAkademikContext()
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJenisSuratRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $formSchema = $validatedData['form_schema'] ?? null;
            $jenisSurat = JenisSurat::create([
                'nama_surat'        => $validatedData['nama_surat'],
                'kode_surat'        => $validatedData['kode_surat'],
                'kategori'          => $validatedData['kategori'],
                'format_penomoran'  => $validatedData['format_penomoran'],
                'isi_template'      => $validatedData['isi_template'],
                'form_schema'       => $formSchema,
                'counter_nomor_urut'=> 0,
                'counter_tahun'     => null,
            ]);

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
            Log::info('Jenis surat baru berhasil dibuat: ' . $jenisSurat->nama_surat);

            return redirect()->route('admin_akademik.jenis-surat.index')
                ->with('success', 'Jenis surat baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan jenis surat baru: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisSurat $jenisSurat): View
    {
        $kategoriOptions = ['Akademik', 'Kemahasiswaan', 'Keuangan', 'Penelitian', 'Umum'];
        $masterJabatans  = MasterJabatan::orderBy('nama_jabatan')->get();

        $alurApprovals = AlurApproval::where('jenis_surat_id', $jenisSurat->id)
            ->orderBy('urutan')
            ->get();

        $approvalData = $alurApprovals->map(function ($item) {
            return [
                'master_jabatan_id' => $item->master_jabatan_id,
                'scope'             => $item->scope,
            ];
        })->values();

        return view('admin_akademik.jenis_surat.edit', array_merge(
            compact('jenisSurat', 'kategoriOptions', 'masterJabatans', 'approvalData'),
            $this->getAkademikContext()
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJenisSuratRequest $request, JenisSurat $jenisSurat): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $formSchema = $validatedData['form_schema'] ?? null;

            $jenisSurat->update([
                'nama_surat'        => $validatedData['nama_surat'],
                'kode_surat'        => $validatedData['kode_surat'],
                'kategori'          => $validatedData['kategori'],
                'format_penomoran'  => $validatedData['format_penomoran'],
                'isi_template'      => $validatedData['isi_template'],
                'form_schema'       => $formSchema,
            ]);

            AlurApproval::where('jenis_surat_id', $jenisSurat->id)->delete();

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
            Log::info('Jenis surat berhasil diupdate: ' . $jenisSurat->nama_surat);

            return redirect()->route('admin_akademik.jenis-surat.index')
                ->with('success', 'Jenis surat berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengupdate jenis surat: ' . $e->getMessage());

            return back()->withInput()
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
            AlurApproval::where('jenis_surat_id', $jenisSurat->id)->delete();
            $nama = $jenisSurat->nama_surat;
            $jenisSurat->delete();

            DB::commit();
            Log::info('Jenis surat dihapus: ' . $nama);

            return redirect()->route('admin_akademik.jenis-surat.index')
                ->with('success', 'Jenis surat berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus jenis surat: ' . $e->getMessage());

            return redirect()->route('admin_akademik.jenis-surat.index')
                ->with('error', 'Terjadi kesalahan saat menghapus jenis surat: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisSurat $jenisSurat): View
    {
        $alurApprovals = $jenisSurat->alurApprovals()->orderBy('urutan')->get();
        return view('admin_akademik.jenis_surat.show', compact('jenisSurat', 'alurApprovals'));
    }
}