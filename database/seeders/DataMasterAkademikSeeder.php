<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\MasterJabatan;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataMasterAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terkait (hati-hati dengan foreign key)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Fakultas::truncate();
        ProgramStudi::truncate();
        MasterJabatan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data Fakultas
        $fst = Fakultas::create([
            'nama_fakultas' => 'Fakultas Sains dan Teknologi',
            'kode_fakultas' => 'FST' // Tambahkan kode fakultas
        ]);
        $feb = Fakultas::create([
            'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis',
            'kode_fakultas' => 'FEB'
        ]);

        // Data Program Studi
        ProgramStudi::create([
            'nama_prodi' => 'Teknik Informatika',
            'kode_prodi' => 'TI', // Pastikan kode_prodi ada
            'fakultas_id' => $fst->id
        ]);
        ProgramStudi::create([
            'nama_prodi' => 'Sistem Informasi',
            'kode_prodi' => 'SI',
            'fakultas_id' => $fst->id
        ]);
        ProgramStudi::create([
            'nama_prodi' => 'Manajemen',
            'kode_prodi' => 'MNJ',
            'fakultas_id' => $feb->id
        ]);

        // Data Master Jabatan
        MasterJabatan::create(['nama_jabatan' => 'Kaprodi']);
        MasterJabatan::create(['nama_jabatan' => 'Dekan']);
        MasterJabatan::create(['nama_jabatan' => 'Wakil Rektor 1']); // Contoh jabatan lain
    }
}

