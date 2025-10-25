<?php

namespace App\Listeners;

use App\Events\PengajuanDivalidasiEvent;
use App\Models\AlurApproval;
use App\Models\ApprovalPejabat;
use App\Models\Pejabat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BuatAlurApprovalListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PengajuanDivalidasiEvent $event): void
    {
        $pengajuan = $event->pengajuan;
        
        // Ambil data mahasiswa & relasinya (prodi & fakultas)
        $mahasiswa = $pengajuan->mahasiswa()->with('programStudi.fakultas')->first();

        // Ambil alur approval yang sudah didefinisikan untuk jenis surat ini
        $alurItems = AlurApproval::where('jenis_surat_id', $pengajuan->jenis_surat_id)
                                 ->orderBy('urutan', 'asc')
                                 ->get();

        if ($alurItems->isEmpty()) {
            // Jika tidak ada alur, catat error dan mungkin ubah status
            Log::error("Tidak ada alur approval didefinisikan untuk jenis_surat_id: {$pengajuan->jenis_surat_id}");
            $pengajuan->status_pengajuan = 'ditolak'; // atau status 'error'
            $pengajuan->catatan_revisi = 'Alur approval internal tidak ditemukan. Hubungi Admin Akademik.';
            $pengajuan->save();
            return;
        }

        foreach ($alurItems as $item) {
            $query = Pejabat::where('master_jabatan_id', $item->master_jabatan_id);

            // Tentukan scope pejabat
            if ($item->scope == 'PRODI') {
                $query->where('program_studi_id', $mahasiswa->program_studi_id);
            } elseif ($item->scope == 'FAKULTAS') {
                $query->where('fakultas_id', $mahasiswa->programStudi->fakultas_id);
            } elseif ($item->scope == 'UNIVERSITAS') {
                // Asumsi jabatan universitas (Rektor dll) tidak punya scope prodi/fakultas
                $query->whereNull('program_studi_id')->whereNull('fakultas_id');
            }

            $pejabat = $query->first();

            if ($pejabat) {
                // Buat entri tugas di tabel approval_pejabat
                ApprovalPejabat::create([
                    'pengajuan_surat_id' => $pengajuan->id,
                    'pejabat_id' => $pejabat->id,
                    'urutan_approval' => $item->urutan,
                    'status_approval' => 'menunggu',
                ]);
            } else {
                // Jika pejabat untuk scope tsb tidak ditemukan, ini error kritis
                Log::error("Pejabat tidak ditemukan untuk alur approval id: {$item->id} (Jabatan: {$item->master_jabatan_id}, Scope: {$item->scope})");
                // Hentikan proses & batalkan
                $pengajuan->status_pengajuan = 'ditolak';
                $pengajuan->catatan_revisi = "Pejabat berwenang (Scope: {$item->scope}) tidak ditemukan di sistem. Hubungi Admin Akademik.";
                $pengajuan->save();
                return;
            }
        }

        // Setelah semua alur dibuat, update status pengajuan utama
        $pengajuan->status_pengajuan = 'menunggu_pejabat';
        $pengajuan->save();
    }
}
