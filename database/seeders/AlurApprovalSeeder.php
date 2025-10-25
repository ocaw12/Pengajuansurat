<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlurApproval;
use App\Models\JenisSurat;
use App\Models\MasterJabatan;
use Illuminate\Support\Facades\DB;

class AlurApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AlurApproval::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil data master
        $suratAktif = JenisSurat::where('kode_surat', 'SK-AKTIF')->first();
        $suratIzin = JenisSurat::where('kode_surat', 'IZIN-PEN')->first();
        $jabatanKaprodi = MasterJabatan::where('nama_jabatan', 'Kaprodi')->first();
        $jabatanDekan = MasterJabatan::where('nama_jabatan', 'Dekan')->first();

        // Alur 1: Surat Aktif (Hanya 1 level approval: Kaprodi)
        AlurApproval::create([
            'jenis_surat_id' => $suratAktif->id,
            'urutan' => 1,
            'master_jabatan_id' => $jabatanKaprodi->id,
            'scope' => 'PRODI',
        ]);

        // Alur 2: Izin Penelitian (2 level approval: Kaprodi -> Dekan)
        AlurApproval::create([
            'jenis_surat_id' => $suratIzin->id,
            'urutan' => 1,
            'master_jabatan_id' => $jabatanKaprodi->id,
            'scope' => 'PRODI',
        ]);
        
        AlurApproval::create([
            'jenis_surat_id' => $suratIzin->id,
            'urutan' => 2,
            'master_jabatan_id' => $jabatanDekan->id,
            'scope' => 'FAKULTAS',
        ]);
    }
}
