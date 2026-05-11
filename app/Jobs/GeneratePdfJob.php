<?php

namespace App\Jobs;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PengajuanSurat $pengajuan;

    public function __construct(PengajuanSurat $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function handle(): void
    {
        $this->pengajuan->load(
            'mahasiswa.programStudi.fakultas',
            'jenisSurat',
            'approvalPejabats.pejabat.masterJabatan'
        );

        $pengajuan  = $this->pengajuan;
        $mahasiswa  = $pengajuan->mahasiswa;
        $jenisSurat = $pengajuan->jenisSurat;
        $nomorSuratLengkap = null;

        try {
            DB::beginTransaction();

            // ─── 1. GENERATE NOMOR SURAT ─────────────────────────────────
            $tahunSekarang = date('Y');
            $jenisSuratLock = JenisSurat::where('id', $pengajuan->jenis_surat_id)
                ->lockForUpdate()
                ->firstOrFail();

            $nomorBaru = 0;
            if ($jenisSuratLock->counter_tahun != $tahunSekarang) {
                $nomorBaru = 1;
                $jenisSuratLock->counter_nomor_urut = 1;
                $jenisSuratLock->counter_tahun      = $tahunSekarang;
            } else {
                $nomorBaru = $jenisSuratLock->counter_nomor_urut + 1;
                $jenisSuratLock->counter_nomor_urut = $nomorBaru;
            }
            $jenisSuratLock->save();

            DB::commit();

            // ─── 2. BUAT NOMOR SURAT LENGKAP ─────────────────────────────
            $nomorUrutFormatted = str_pad($nomorBaru, 3, '0', STR_PAD_LEFT);
            $kamusNomor = [
                '{nomor_urut}'   => $nomorUrutFormatted,
                '{kode_surat}'   => $jenisSurat->kode_surat,
                '{kode_unit}'    => $mahasiswa->programStudi->kode_prodi,
                '{bulan_romawi}' => $this->bulanKeRomawi(date('n')),
                '{tahun}'        => $tahunSekarang,
            ];
            $nomorSuratLengkap = str_replace(
                array_keys($kamusNomor),
                array_values($kamusNomor),
                $jenisSurat->format_penomoran
            );

            // ─── 3. DATA TAHUN AJARAN & SEMESTER AKTIF ───────────────────
            $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();
            $semesterAktif    = Semester::where('is_aktif', true)
                ->with('tahunAjaran')
                ->first();

            $nilaiTahunAjaran  = $tahunAjaranAktif?->tahun ?? '-';
            $nilaiSemester     = $semesterAktif?->semester ?? '-';
            // Contoh: "Ganjil 2025/2026"
            $nilaiSemesterLengkap = $semesterAktif
                ? ucfirst(strtolower($semesterAktif->semester)) . ' ' . ($tahunAjaranAktif?->tahun ?? '')
                : '-';

            // Hitung angka semester dari angkatan (opsional, berguna untuk beberapa surat)
            $angkatanMhs        = (int) ($mahasiswa->angkatan ?? date('Y'));
            $tahunAktifInt      = $tahunAjaranAktif
                ? (int) explode('/', $tahunAjaranAktif->tahun)[0]
                : (int) date('Y');
            $selisihTahun       = max(0, $tahunAktifInt - $angkatanMhs);
            $angkaSemesterMhs   = ($selisihTahun * 2) + ($nilaiSemester === 'GANJIL' ? 1 : 2);

            // ─── 4. GENERATE QR CODE ──────────────────────────────────────
            $approvals = $pengajuan->approvalPejabats->where('status_approval', 'disetujui');
            foreach ($approvals as $approval) {
                $kodeVerifikasi  = Str::random(32);
                $verificationUrl = URL::route('verifikasi.show', ['kode_verifikasi' => $kodeVerifikasi]);
                $qrPath          = 'qrcodes/approval_' . $approval->id . '_' . Str::random(5) . '.svg';
                $qrImage         = QrCode::format('svg')->size(70)->generate($verificationUrl);

                Storage::disk('public')->put($qrPath, $qrImage);
                $approval->update([
                    'path_qr'          => $qrPath,
                    'kode_verifikasi'  => $kodeVerifikasi,
                ]);
            }

            // ─── 5. RANGKAI NASKAH SURAT ──────────────────────────────────
            $kamus = [
                // ── Data Mahasiswa ──
                '[nama_mahasiswa]'       => $mahasiswa->nama_lengkap,
                '[nim]'                  => $mahasiswa->nim,
                '[prodi]'                => $mahasiswa->programStudi->nama_prodi,
                '[fakultas]'             => $mahasiswa->programStudi->fakultas->nama_fakultas ?? '-',
                '[keperluan]'            => $pengajuan->keperluan,
                '[tempat_lahir]'         => $mahasiswa->tempat_lahir ?? '-',
                '[tanggal_lahir]'        => $mahasiswa->tanggal_lahir
                                               ? $mahasiswa->tanggal_lahir->format('d F Y')
                                               : '-',
                '[alamat]'               => $mahasiswa->alamat ?? '-',
                '[jenis_kelamin]'        => $mahasiswa->jenis_kelamin ?? '-',
                '[angkatan]'             => $mahasiswa->angkatan ?? '-',

                // ── Semester & Tahun Ajaran ──
                '[tahun_ajaran]'         => $nilaiTahunAjaran,
                '[semester]'             => ucfirst(strtolower($nilaiSemester)),
                '[semester_lengkap]'     => $nilaiSemesterLengkap,
                '[semester_angka]'       => $angkaSemesterMhs,

                // ── Tanggal Surat ──
                '[tanggal_sekarang]'     => now()->isoFormat('D MMMM YYYY'),
                '[bulan_sekarang]'       => now()->isoFormat('MMMM'),
                '[tahun_sekarang]'       => now()->format('Y'),

                // ── Nomor Surat ──
                '[nomor_surat]'          => $nomorSuratLengkap,
            ];

            // Merge data_pendukung (field dinamis dari form schema)
            if ($pengajuan->data_pendukung) {
                foreach ($pengajuan->data_pendukung as $key => $value) {
                    $kamus["[$key]"] = $value;
                }
            }

            $naskahMentah       = $jenisSurat->isi_template;
            $naskahDiterjemahkan = str_replace(
                array_keys($kamus),
                array_values($kamus),
                $naskahMentah
            );
            $isiSuratFinal = nl2br($naskahDiterjemahkan);

            // ─── 6. GENERATE PDF ──────────────────────────────────────────
            $dataForPdf = [
                'pengajuan'        => $pengajuan,
                'mahasiswa'        => $mahasiswa,
                'jenis_surat'      => $jenisSurat,
                'nomor_surat'      => $nomorSuratLengkap,
                'isi_surat_final'  => $isiSuratFinal,
                'pejabat_approvals'=> $approvals,
                'tanggal_terbit'   => now()->isoFormat('D MMMM YYYY'),
            ];
            $pdf = Pdf::loadView('pdf.master_template', $dataForPdf);

            // ─── 7. SIMPAN PDF ────────────────────────────────────────────
            $fileName     = 'surat_' . $pengajuan->id . '_' . Str::slug($mahasiswa->nama_lengkap ?? 'mahasiswa') . '.pdf';
            $relativePath = 'surat_selesai/' . $fileName;
            Storage::disk('public')->put($relativePath, $pdf->output());

            Log::info("PDF berhasil disimpan: storage/app/public/{$relativePath}");

            // ─── 8. UPDATE STATUS PENGAJUAN ───────────────────────────────
            $statusAkhir = ($pengajuan->metode_pengambilan === 'digital') ? 'selesai' : 'siap_dicetak';

            $pengajuan->nomor_surat   = $nomorSuratLengkap;
            $pengajuan->file_hasil_pdf = $relativePath;
            $pengajuan->status_pengajuan = $statusAkhir;
            $pengajuan->save();

            Log::info("Status Pengajuan ID {$pengajuan->id} → {$statusAkhir}");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(
                "Gagal Generate PDF untuk pengajuan ID {$pengajuan->id}: "
                . $e->getMessage()
                . " di baris " . $e->getLine()
                . "\n" . $e->getTraceAsString()
            );

            $pengajuan->status_pengajuan = 'gagal_generate';
            $pengajuan->catatan_revisi   = 'Gagal generate PDF. Error: ' . $e->getMessage();
            $pengajuan->save();

            $this->fail($e);
        }
    }

    /**
     * Konversi angka bulan ke angka Romawi.
     */
    private function bulanKeRomawi(int $bulan): string
    {
        $romawi = [
            1 => 'I',   2 => 'II',  3 => 'III', 4 => 'IV',
            5 => 'V',   6 => 'VI',  7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X',  11 => 'XI', 12 => 'XII',
        ];
        return $romawi[$bulan];
    }

    /**
     * Handle job failure permanen.
     */
    public function failed(\Throwable $exception): void
    {
        $this->pengajuan->status_pengajuan = 'gagal_generate';
        $this->pengajuan->catatan_revisi   = 'Gagal memproses PDF setelah beberapa kali percobaan: ' . $exception->getMessage();
        $this->pengajuan->save();

        Log::critical(
            "GeneratePdfJob GAGAL PERMANEN untuk Pengajuan ID {$this->pengajuan->id}: "
            . $exception->getMessage()
        );
    }
}