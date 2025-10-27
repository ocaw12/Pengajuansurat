<?php

namespace Database\Seeders;

use App\Models\AlurApproval;
use App\Models\JenisSurat;
use App\Models\MasterJabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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

        // Ambil referensi
        $suratAktif = JenisSurat::where('kode_surat', 'SK-AKTIF')->first();
        $suratPenelitian = JenisSurat::where('kode_surat', 'SP-PENELITIAN')->first();
        $jabatanKaprodi = MasterJabatan::where('nama_jabatan', 'Kaprodi')->first();
        $jabatanDekan = MasterJabatan::where('nama_jabatan', 'Dekan')->first();

        // Alur Surat Aktif (Hanya Kaprodi)
        if ($suratAktif && $jabatanKaprodi) {
            AlurApproval::create([
                'jenis_surat_id' => $suratAktif->id,
                'urutan' => 1,
                'master_jabatan_id' => $jabatanKaprodi->id,
                'scope' => 'PRODI',
            ]);
        }

        // Alur Surat Penelitian (Kaprodi -> Dekan)
        if ($suratPenelitian && $jabatanKaprodi && $jabatanDekan) {
            AlurApproval::create([
                'jenis_surat_id' => $suratPenelitian->id,
                'urutan' => 1,
                'master_jabatan_id' => $jabatanKaprodi->id,
                'scope' => 'PRODI',
            ]);
            AlurApproval::create([
                'jenis_surat_id' => $suratPenelitian->id,
                'urutan' => 2,
                'master_jabatan_id' => $jabatanDekan->id,
                'scope' => 'FAKULTAS',
            ]);
        }
    }
}

