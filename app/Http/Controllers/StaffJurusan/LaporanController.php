<?php

namespace App\Http\Controllers\StaffJurusan;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPengajuanExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Response;

class LaporanController extends Controller
{
    /**
     * Ambil query base yang dipakai berulang (index, export).
     */
    private function buildQuery(Request $request, int $programStudiId)
    {
        $bulan        = $request->integer('bulan', now()->month);
        $tahun        = $request->integer('tahun', now()->year);
        $jenisSuratId = $request->filled('jenis_surat_id') ? $request->integer('jenis_surat_id') : null;
        $statusFilter = $request->filled('status') ? $request->string('status')->toString() : null;

        $query = PengajuanSurat::with([
                'mahasiswa.programStudi',
                'jenisSurat',
                'adminValidator',
                'approvalPejabats.pejabat.masterJabatan',
            ])
            ->whereHas('mahasiswa', fn($q) => $q->where('program_studi_id', $programStudiId))
            ->whereYear('tanggal_pengajuan', $tahun)
            ->whereMonth('tanggal_pengajuan', $bulan);

        if ($jenisSuratId) $query->where('jenis_surat_id', $jenisSuratId);
        if ($statusFilter)  $query->where('status_pengajuan', $statusFilter);

        return $query;
    }

    /**
     * Halaman utama laporan.
     */
    public function index(Request $request): View
    {
        $this->authorizeStaff();

        $programStudiId = Auth::user()->adminStaff->program_studi_id;
        $bulan          = $request->integer('bulan', now()->month);
        $tahun          = $request->integer('tahun', now()->year);
        $jenisSuratId   = $request->filled('jenis_surat_id') ? $request->integer('jenis_surat_id') : null;
        $statusFilter   = $request->filled('status') ? $request->string('status')->toString() : null;

        $baseQuery = $this->buildQuery($request, $programStudiId);

        $rekapJenisSurat = (clone $baseQuery)
            ->selectRaw('jenis_surat_id, status_pengajuan, COUNT(*) as total')
            ->groupBy('jenis_surat_id', 'status_pengajuan')
            ->with('jenisSurat:id,nama_surat,kode_surat,kategori')
            ->get()
            ->groupBy('jenis_surat_id');

        $pengajuans = (clone $baseQuery)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total'   => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status_pengajuan', 'pending')->count(),
            'selesai' => (clone $baseQuery)->where('status_pengajuan', 'selesai')->count(),
            'ditolak' => (clone $baseQuery)->where('status_pengajuan', 'ditolak')->count(),
            'proses'  => (clone $baseQuery)->whereNotIn('status_pengajuan', ['selesai', 'ditolak', 'pending'])->count(),
        ];

        $jenisSurats   = JenisSurat::orderBy('nama_surat')->get(['id', 'nama_surat', 'kode_surat']);
        $statusOptions = $this->statusOptions();

        return view('staff_jurusan.laporan.index', compact(
            'pengajuans', 'rekapJenisSurat', 'summary',
            'jenisSurats', 'statusOptions',
            'bulan', 'tahun', 'jenisSuratId', 'statusFilter',
        ));
    }

    /**
     * Export ke Excel (.xlsx) — rapi per kolom, ada header freeze & styling.
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $this->authorizeStaff();

        $programStudiId = Auth::user()->adminStaff->program_studi_id;
        $bulan          = $request->integer('bulan', now()->month);
        $tahun          = $request->integer('tahun', now()->year);

        $pengajuans = $this->buildQuery($request, $programStudiId)
            ->orderBy('tanggal_pengajuan', 'asc')
            ->get();

        $namaProdi = Auth::user()->adminStaff->programStudi->nama_prodi ?? 'Prodi';
        $filename  = "Laporan_Pengajuan_{$namaProdi}_{$tahun}_{$bulan}.xlsx";

        return Excel::download(
            new LaporanPengajuanExport($pengajuans, $bulan, $tahun, $namaProdi),
            $filename
        );
    }

    /**
     * Export ke PDF — layout tabel landscape, header + footer instansi.
     */
    public function exportPdf(Request $request): Response
    {
        $this->authorizeStaff();

        $programStudiId = Auth::user()->adminStaff->program_studi_id;
        $bulan          = $request->integer('bulan', now()->month);
        $tahun          = $request->integer('tahun', now()->year);
        $jenisSuratId   = $request->filled('jenis_surat_id') ? $request->integer('jenis_surat_id') : null;
        $statusFilter   = $request->filled('status') ? $request->string('status')->toString() : null;

        $pengajuans = $this->buildQuery($request, $programStudiId)
            ->orderBy('tanggal_pengajuan', 'asc')
            ->get();

        // Kumpulkan semua key data_pendukung (form schema)
        $schemaKeys = collect();
        foreach ($pengajuans as $p) {
            if (is_array($p->data_pendukung)) {
                $schemaKeys = $schemaKeys->merge(array_keys($p->data_pendukung));
            }
        }
        $schemaKeys = $schemaKeys->unique()->values();

        $summary = [
            'total'   => $pengajuans->count(),
            'selesai' => $pengajuans->where('status_pengajuan', 'selesai')->count(),
            'pending' => $pengajuans->where('status_pengajuan', 'pending')->count(),
            'ditolak' => $pengajuans->where('status_pengajuan', 'ditolak')->count(),
        ];

        $namaProdi    = Auth::user()->adminStaff->programStudi->nama_prodi ?? '-';
        $namaStaff    = Auth::user()->adminStaff->nama_lengkap ?? '-';
        $namaBulan    = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
        $jenisSurat   = $jenisSuratId ? JenisSurat::find($jenisSuratId)?->nama_surat : 'Semua Jenis Surat';
        $statusLabel  = $statusFilter ? ($this->statusOptions()[$statusFilter] ?? $statusFilter) : 'Semua Status';

        $pdf = Pdf::loadView('staff_jurusan.laporan.pdf', compact(
            'pengajuans', 'schemaKeys', 'summary',
            'namaProdi', 'namaStaff', 'namaBulan',
            'jenisSurat', 'statusLabel', 'tahun', 'bulan',
        ))->setPaper('a4', 'landscape');

        $filename = "Laporan_Pengajuan_{$namaProdi}_{$tahun}_{$bulan}.pdf";

        return $pdf->download($filename);
    }

    // ---------------------------------------------------------------

    private function authorizeStaff(): void
    {
        if (!Auth::user()?->adminStaff) {
            abort(403, 'Akses ditolak. Profil staff jurusan tidak ditemukan.');
        }
    }

    private function statusOptions(): array
    {
        return [
            'pending'          => 'Pending',
            'divalidasi_admin' => 'Divalidasi Admin',
            'menunggu_pejabat' => 'Menunggu Pejabat',
            'perlu_revisi'     => 'Perlu Revisi',
            'ditolak'          => 'Ditolak',
            'selesai'          => 'Selesai',
            'siap_dicetak'     => 'Siap Dicetak',
            'siap_diambil'     => 'Siap Diambil',
            'sudah_diambil'    => 'Sudah Diambil',
            'gagal_generate'   => 'Gagal Generate',
        ];
    }
}