<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\AdminStaff;
use App\Models\Pejabat;
use App\Models\ProgramStudi;
use App\Models\Fakultas;
use App\Models\MasterJabatan;
use Illuminate\Support\Facades\DB;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Mahasiswa::truncate();
        AdminStaff::truncate();
        Pejabat::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil Data Master
        $prodiTI = ProgramStudi::where('kode_prodi', 'TI')->first();
        $fakultasFST = Fakultas::where('nama_fakultas', 'Fakultas Sains dan Teknologi')->first();
        $jabatanKaprodi = MasterJabatan::where('nama_jabatan', 'Kaprodi')->first();
        $jabatanDekan = MasterJabatan::where('nama_jabatan', 'Dekan')->first();

        // 1. Buat Profil Mahasiswa
        $userMhs = User::where('email', 'mahasiswa@up45.ac.id')->first();
        Mahasiswa::create([
            'user_id' => $userMhs->id,
            'nim' => '22110001',
            'nama_lengkap' => 'Budi Mahasiswa',
            'program_studi_id' => $prodiTI->id,
            'angkatan' => 2022,
        ]);

        // 2. Buat Profil Staff Jurusan
        $userStaff = User::where('email', 'staff.ti@up45.ac.id')->first();
        AdminStaff::create([
            'user_id' => $userStaff->id,
            'nip_staff' => 'S12345',
            'nama_lengkap' => 'Staff Prodi TI',
            'program_studi_id' => $prodiTI->id,
        ]);

        // 3. Buat Profil Pejabat
        $userKaprodi = User::where('email', 'kaprodi.ti@up45.ac.id')->first();
        Pejabat::create([
            'user_id' => $userKaprodi->id,
            'nip_atau_nidn' => 'P1001',
            'nama_lengkap' => 'Dr. Anton, S.Kom., M.Cs.',
            'tanda_tangan_path' => 'ttd/ttd_kaprodi_ti.png', // Path dummy
            'master_jabatan_id' => $jabatanKaprodi->id,
            'fakultas_id' => null, // Kaprodi terikat ke Prodi
            'program_studi_id' => $prodiTI->id,
        ]);

        $userDekan = User::where('email', 'dekan.fst@up45.ac.id')->first();
        Pejabat::create([
            'user_id' => $userDekan->id,
            'nip_atau_nidn' => 'P1002',
            'nama_lengkap' => 'Prof. Dr. Ir. Rina, M.T.',
            'tanda_tangan_path' => 'ttd/ttd_dekan_fst.png', // Path dummy
            'master_jabatan_id' => $jabatanDekan->id,
            'fakultas_id' => $fakultasFST->id, // Dekan terikat ke Fakultas
            'program_studi_id' => null, 
        ]);
    }
}
