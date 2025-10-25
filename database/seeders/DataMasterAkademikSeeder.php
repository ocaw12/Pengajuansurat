<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\MasterJabatan;
use Illuminate\Support\Facades\DB;

class DataMasterAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Fakultas::truncate();
        ProgramStudi::truncate();
        MasterJabatan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Buat Fakultas
        $fst = Fakultas::create(['nama_fakultas' => 'Fakultas Sains dan Teknologi']);
        $feb = Fakultas::create(['nama_fakultas' => 'Fakultas Ekonomi dan Bisnis']);

        // 2. Buat Program Studi
        ProgramStudi::create([
            'fakultas_id' => $fst->id,
            'nama_prodi' => 'Teknik Informatika',
            'kode_prodi' => 'TI'
        ]);
        
        ProgramStudi::create([
            'fakultas_id' => $fst->id,
            'nama_prodi' => 'Sistem Informasi',
            'kode_prodi' => 'SI'
        ]);

        ProgramStudi::create([
            'fakultas_id' => $feb->id,
            'nama_prodi' => 'Manajemen',
            'kode_prodi' => 'MNJ'
        ]);

        // 3. Buat Master Jabatan
        MasterJabatan::create(['nama_jabatan' => 'Kaprodi']);
        MasterJabatan::create(['nama_jabatan' => 'Dekan']);
        MasterJabatan::create(['nama_jabatan' => 'Wakil Rektor 1']);
    }
}
