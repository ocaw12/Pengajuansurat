<?php

namespace App\Jobs;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan Anda sudah 'composer require barryvdh/laravel-dompdf'

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Objek pengajuan surat
     */
    public PengajuanSurat $pengajuan;

    /**
     * Create a new job instance.
     */
    public function __construct(PengajuanSurat $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load ulang relasi untuk memastikan data segar (terutama jika job di-delay)
        $this->pengajuan->load('mahasiswa', 'jenisSurat', 'approvalPejabats.pejabat');
        $pengajuan = $this->pengajuan;

        $nomorSuratLengkap = null;
        $isiSuratFinal = null;
        $dataForPdf = [];
        $mahasiswa = $pengajuan->mahasiswa()->with('programStudi.fakultas')->first();
        $jenisSurat = $pengajuan->jenisSurat;

        try {
            DB::beginTransaction();

            // 1. GENERATE NOMOR SURAT (Dengan logic reset tahunan)
            $tahunSekarang = date('Y');
            
            // Kunci baris JenisSurat untuk mencegah race condition
            $jenisSuratLock = JenisSurat::where('id', $pengajuan->jenis_surat_id)
                                ->lockForUpdate() 
                                ->first();
            
            $nomorBaru;

            if ($jenisSuratLock->counter_tahun != $tahunSekarang) {
                // Surat pertama di tahun baru, reset counter
                $nomorBaru = 1;
                $jenisSuratLock->counter_nomor_urut = 1;
                $jenisSuratLock->counter_tahun = $tahunSekarang;
            } else {
                // Tahun yang sama, increment counter
                $nomorBaru = $jenisSuratLock->counter_nomor_urut + 1;
                $jenisSuratLock->counter_nomor_urut = $nomorBaru;
            }
            $jenisSuratLock->save();

            // 2. KUMPULKAN "KAMUS" SHORTCODE
            
            // Kamus data standar
            $kamus = [
                '[nama_mahasiswa]' => $mahasiswa->nama_lengkap,
                '[nim]'            => $mahasiswa->nim,
                '[prodi]'          => $mahasiswa->programStudi->nama_prodi,
                '[fakultas]'       => $mahasiswa->programStudi->fakultas->nama_fakultas,
                '[keperluan]'      => $pengajuan->keperluan,
                // Tambahkan shortcode standar lain jika ada (cth: [angkatan])
            ];

            // Kamus data dinamis (dari form_schema)
            $dataDinamis = $pengajuan->data_pendukung; // Ini sudah array karena $casts
            if ($dataDinamis) {
                foreach ($dataDinamis as $key => $value) {
                    // Hapus e() agar HTML/formatting dari user (jika ada) bisa dirender di PDF
                    $kamus["[$key]"] = $value; 
                }
            }

            // 3. BUAT NOMOR SURAT LENGKAP
            $kamusNomor = [
                '{nomor_urut}'   => str_pad($nomorBaru, 3, '0', STR_PAD_LEFT),
                '{kode_surat}'   => $jenisSurat->kode_surat,
                '{kode_unit}'    => $mahasiswa->programStudi->kode_prodi, // FST, TI, dll.
                '{bulan_romawi}' => $this->bulanKeRomawi(date('n')),
                '{tahun}'        => $tahunSekarang,
            ];
            
            $nomorSuratLengkap = str_replace(
                array_keys($kamusNomor), 
                array_values($kamusNomor), 
                $jenisSurat->format_penomoran
            );

            // 4. RANGKAI NASKAH SURAT FINAL
            $naskahMentah = $jenisSurat->isi_template;
            $naskahDiterjemahkan = str_replace(
                array_keys($kamus), 
                array_values($kamus), 
                $naskahMentah
            );
            // Konversi "Enter" (baris baru) dari textarea menjadi tag <br>
            $isiSuratFinal = nl2br($naskahDiterjemahkan);

            // 5. AMBIL DATA PEJABAT (untuk TTD)
            $approvals = $pengajuan->approvalPejabats()
                                 ->with('pejabat') // Ambil data pejabat
                                 ->where('status_approval', 'disetujui')
                                 ->orderBy('urutan_approval', 'asc')
                                 ->get();
            
            // 6. GENERATE PDF
            $dataForPdf = [
                'pengajuan' => $pengajuan,
                'mahasiswa' => $mahasiswa,
                'jenis_surat' => $jenisSurat,
                'nomor_surat' => $nomorSuratLengkap,
                'isi_surat_final' => $isiSuratFinal,
                'pejabat_approvals' => $approvals, // Kirim data pejabat ke view PDF
                'tanggal_terbit' => now()->isoFormat('D MMMM YYYY'), // Tanggal hari ini
            ];

            // Panggil view master template PDF
            $pdf = Pdf::loadView('pdf.master_template', $dataForPdf);

            // 7. SIMPAN PDF KE STORAGE (Logika baru)
            $fileName = 'surat_'. $pengajuan->id . '_' . Str::slug($mahasiswa->nama_lengkap ?? 'mahasiswa') . '.pdf';
            // Tentukan path RELATIF di dalam folder public
            $relativePath = 'surat_selesai/' . $fileName;

            // Gunakan disk 'public' yang otomatis menyimpan ke storage/app/public/
            // Pastikan Anda sudah menjalankan 'php artisan storage:link'
            Storage::disk('public')->put($relativePath, $pdf->output());
            Log::info("PDF berhasil disimpan ke: storage/app/public/" . $relativePath); // Log untuk debugging

            // 8. UPDATE FINAL PENGAJUAN SURAT (Logika baru)
            $statusAkhir = ($pengajuan->metode_pengambilan == 'digital') ? 'selesai' : 'siap_diambil';

            if ($statusAkhir == 'siap_diambil') {
                 // TODO: Kirim notifikasi ke Staff Jurusan bahwa surat siap dicetak
            }
            
            $pengajuan->nomor_surat = $nomorSuratLengkap; 
            $pengajuan->file_hasil_pdf = $relativePath;   // Simpan path relatif
            $pengajuan->status_pengajuan = $statusAkhir;
            $pengajuan->save();

            // 9. Selesaikan Transaksi
            DB::commit();
            
            // TODO: Kirim notifikasi ke Mahasiswa bahwa surat selesai/siap diambil

        } catch (\Exception $e) {
            // Jika terjadi error, batalkan semua perubahan DB
            DB::rollBack();
            Log::error("Gagal Generate PDF untuk pengajuan ID {$pengajuan->id}: " . $e->getMessage() . " di baris " . $e->getLine());
            
            // Update status ke 'ditolak' atau 'gagal' agar tidak tersangkut
            $pengajuan->status_pengajuan = 'ditolak';
            $pengajuan->catatan_revisi = 'Gagal generate PDF. Error internal sistem. Hubungi Admin. (Error: ' . $e->getMessage() .')';
            $pengajuan->save();
            
            // Gagal kan job ini agar bisa di-retry atau diperiksa
            $this->fail($e);
        }
    }

    /**
     * Helper function untuk konversi bulan ke Romawi.
     */
    private function bulanKeRomawi($bulan): string
    {
        $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        return $romawi[ (int) $bulan ];
    }
}
