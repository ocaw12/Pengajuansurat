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
use Barryvdh\DomPDF\Facade\Pdf; 
use SimpleSoftwareIO\QrCode\Facades\QrCode; // <-- Import QR Code
use Illuminate\Support\Facades\URL; // <-- Import URL

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
        $this->pengajuan->load('mahasiswa.programStudi.fakultas', 'jenisSurat', 'approvalPejabats.pejabat.masterJabatan');
        $pengajuan = $this->pengajuan;
        $mahasiswa = $pengajuan->mahasiswa;
        $jenisSurat = $pengajuan->jenisSurat;
        $nomorSuratLengkap = null;

        try {
            DB::beginTransaction();

            // 1. GENERATE NOMOR SURAT
            $tahunSekarang = date('Y');
            $jenisSuratLock = JenisSurat::where('id', $pengajuan->jenis_surat_id)
                                 ->lockForUpdate() 
                                 ->firstOrFail();
            
            $nomorBaru;
            if ($jenisSuratLock->counter_tahun != $tahunSekarang) {
                $nomorBaru = 1;
                $jenisSuratLock->counter_nomor_urut = 1;
                $jenisSuratLock->counter_tahun = $tahunSekarang;
            } else {
                $nomorBaru = $jenisSuratLock->counter_nomor_urut + 1;
                $jenisSuratLock->counter_nomor_urut = $nomorBaru;
            }
            $jenisSuratLock->save();
            DB::commit(); // Commit transaksi counter

            // 2. BUAT NOMOR SURAT LENGKAP
            $nomorUrutFormatted = str_pad($nomorBaru, 3, '0', STR_PAD_LEFT);
            $kamusNomor = [
                '{nomor_urut}'    => $nomorUrutFormatted,
                '{kode_surat}'    => $jenisSurat->kode_surat,
                '{kode_unit}'     => $mahasiswa->programStudi->kode_prodi, 
                '{bulan_romawi}'  => $this->bulanKeRomawi(date('n')),
                '{tahun}'         => $tahunSekarang,
            ];
            $nomorSuratLengkap = str_replace(
                array_keys($kamusNomor), 
                array_values($kamusNomor), 
                $jenisSurat->format_penomoran
            );

            // 3. GENERATE QR CODE & KODE VERIFIKASI (Sesuai ERD baru)
            $approvals = $pengajuan->approvalPejabats->where('status_approval', 'disetujui');
            foreach ($approvals as $approval) {
                $kodeVerifikasi = Str::random(32); 
                $verificationUrl = URL::route('verifikasi.show', ['kode_verifikasi' => $kodeVerifikasi]);
                
                $qrPath = 'qrcodes/approval_' . $approval->id . '_' . Str::random(5) . '.svg';
                $qrImage = QrCode::format('svg')->size(70)->generate($verificationUrl);
                Storage::disk('public')->put($qrPath, $qrImage); 
                
                $approval->update([
                    'path_qr' => $qrPath,
                    'kode_verifikasi' => $kodeVerifikasi
                ]);
            }

            // 4. RANGKAI NASKAH SURAT FINAL
            $kamus = [
                '[nama_mahasiswa]' => $mahasiswa->nama_lengkap,
                '[nim]'            => $mahasiswa->nim,
                '[prodi]'          => $mahasiswa->programStudi->nama_prodi,
                '[fakultas]'       => $mahasiswa->programStudi->fakultas->nama_fakultas,
                '[keperluan]'      => $pengajuan->keperluan,
                '[tempat_lahir]'   => $mahasiswa->tempat_lahir ?? '-',
                '[tanggal_lahir]'  => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->format('d F Y') : '-',
                '[alamat]'         => $mahasiswa->alamat ?? '-',
                '[jenis_kelamin]'  => $mahasiswa->jenis_kelamin ?? '-',
                '[angkatan]'       => $mahasiswa->angkatan ?? '-',
                '[tanggal_sekarang]' => now()->isoFormat('D MMMM YYYY'),
                '[nomor_surat]'    => $nomorSuratLengkap, // Tambahkan nomor surat ke kamus
            ];
            if ($pengajuan->data_pendukung) {
                foreach ($pengajuan->data_pendukung as $key => $value) {
                    $kamus["[$key]"] = $value; 
                }
            }
            $naskahMentah = $jenisSurat->isi_template;
            $naskahDiterjemahkan = str_replace(array_keys($kamus), array_values($kamus), $naskahMentah);
            $isiSuratFinal = nl2br($naskahDiterjemahkan);

            // 5. GENERATE PDF
            $dataForPdf = [
                'pengajuan' => $pengajuan,
                'mahasiswa' => $mahasiswa,
                'jenis_surat' => $jenisSurat,
                'nomor_surat' => $nomorSuratLengkap,
                'isi_surat_final' => $isiSuratFinal,
                'pejabat_approvals' => $approvals, // Kirim data pejabat ke view PDF
                'tanggal_terbit' => now()->isoFormat('D MMMM YYYY'),
            ];
            $pdf = Pdf::loadView('pdf.master_template', $dataForPdf);

            // 6. SIMPAN PDF KE STORAGE
            $fileName = 'surat_'. $pengajuan->id . '_' . Str::slug($mahasiswa->nama_lengkap ?? 'mahasiswa') . '.pdf';
            $relativePath = 'surat_selesai/' . $fileName;
            Storage::disk('public')->put($relativePath, $pdf->output());
            Log::info("PDF berhasil disimpan ke: storage/app/public/" . $relativePath); 

            // 7. UPDATE FINAL PENGAJUAN SURAT
            // !! PERUBAHAN LOGIKA !!
            $statusAkhir = ($pengajuan->metode_pengambilan == 'digital') ? 'selesai' : 'siap_dicetak'; // <-- STATUS BARU

            $pengajuan->nomor_surat = $nomorSuratLengkap; 
            $pengajuan->file_hasil_pdf = $relativePath;
            $pengajuan->status_pengajuan = $statusAkhir;
            $pengajuan->save();
            Log::info("Status Pengajuan ID {$pengajuan->id} diupdate menjadi {$statusAkhir}");

            // 8. KIRIM NOTIFIKASI
            if ($statusAkhir == 'selesai') {
                // TODO: Kirim notifikasi "Selesai & Bisa di-download" ke Mahasiswa (Email/WA)
                // $mahasiswa->notify(new SuratDigitalSelesaiNotification($pengajuan));
            } else {
                // TODO: Kirim notifikasi ke Staff Jurusan bahwa ada surat "Siap Dicetak"
                // $pengajuan->adminStaff->user->notify(new SuratPerluCetakNotification($pengajuan));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal Generate PDF untuk pengajuan ID {$pengajuan->id}: " . $e->getMessage() . " di baris " . $e->getLine() . "\n" . $e->getTraceAsString());
            
            $pengajuan->status_pengajuan = 'gagal_generate';
            $pengajuan->catatan_revisi = 'Gagal generate PDF. Error internal sistem. (Error: ' . $e->getMessage() .')';
            $pengajuan->save();
            
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
    
    /**
     * Handle a job failure.
     */
     public function failed(\Throwable $exception): void
     {
         // Update status jika job gagal permanen
         $this->pengajuan->status_pengajuan = 'gagal_generate';
         $this->pengajuan->catatan_revisi = 'Gagal memproses PDF setelah beberapa kali percobaan: ' . $exception->getMessage();
         $this->pengajuan->save();
         Log::critical("GeneratePdfJob GAGAL PERMANEN untuk Pengajuan ID {$this->pengajuan->id}: " . $exception->getMessage());
     }
}