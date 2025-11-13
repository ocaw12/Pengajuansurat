<?php

namespace App\Notifications;

use App\Channels\FonnteChannel; // <-- Gunakan Channel kustom
use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SuratSiapDiambilNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pengajuan;

    /**
     * Create a new notification instance.
     */
    public function __construct(PengajuanSurat $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Tentukan channel pengiriman (Fonnte).
     */
    public function via(mixed $notifiable): array
    {
        return [FonnteChannel::class]; // <-- Kirim via Fonnte
    }

    /**
     * Buat pesan untuk Fonnte.
     */
    public function toFonnte(mixed $notifiable): array
    {
        $namaSurat = $this->pengajuan->jenisSurat->nama_surat;
        $nomorSurat = $this->pengajuan->nomor_surat;
        // Ambil nama depan mahasiswa
        $namaMahasiswa = explode(' ', $this->pengajuan->mahasiswa->nama_lengkap)[0]; 

        // Kustomisasi pesan WA Anda di sini
        $message = "Halo *{$namaMahasiswa}*,\n\n" .
                   "Surat Anda (*{$namaSurat}*) dengan nomor *{$nomorSurat}* sudah selesai dicetak dan **SIAP DIAMBIL**.\n\n" .
                   "Silakan ambil surat Anda di Ruang Staff Jurusan {$this->pengajuan->mahasiswa->programStudi->nama_prodi} pada jam kerja.\n\n" .
                   "Terima kasih.\n" .
                   "_SISURAT UP45_";

        return [
            'message' => $message
        ];
    }
}