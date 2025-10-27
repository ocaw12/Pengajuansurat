<?php

namespace Database\Seeders;

use App\Models\AdminAkademik;
use App\Models\AdminStaff;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\MasterJabatan;
use App\Models\Pejabat;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        Pejabat::truncate();
        AdminStaff::truncate();
        AdminAkademik::truncate(); // Truncate tabel baru
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil data referensi
        $userAdmin = User::where('email', 'admin@up45.ac.id')->first();
        $userMhs = User::where('email', 'mahasiswa@up45.ac.id')->first();
        $userStaffTI = User::where('email', 'staff.ti@up45.ac.id')->first();
        $userKaprodiTI = User::where('email', 'kaprodi.ti@up45.ac.id')->first();
        $userDekanFST = User::where('email', 'dekan.fst@up45.ac.id')->first();

        $prodiTI = ProgramStudi::where('kode_prodi', 'TI')->first();
        $fakultasFST = Fakultas::where('kode_fakultas', 'FST')->first();
        $jabatanKaprodi = MasterJabatan::where('nama_jabatan', 'Kaprodi')->first();
        $jabatanDekan = MasterJabatan::where('nama_jabatan', 'Dekan')->first();

        // Buat Profil Mahasiswa (dengan detail baru)
        if ($userMhs && $prodiTI) {
            Mahasiswa::create([
                'user_id' => $userMhs->id,
                'nim' => '22110001',
                'nama_lengkap' => 'Budi Mahasiswa',
                'program_studi_id' => $prodiTI->id,
                'angkatan' => 2022,
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2004-05-15',
                'alamat' => 'Jl. Merdeka No. 10, Sleman',
                'jenis_kelamin' => 'Laki_laki',
            ]);
        }

        // Buat Profil Staff Jurusan
        if ($userStaffTI && $prodiTI) {
            AdminStaff::create([
                'user_id' => $userStaffTI->id,
                'nip_staff' => 'S12345',
                'nama_lengkap' => 'Staff Prodi TI',
                'program_studi_id' => $prodiTI->id,
            ]);
        }

        // Buat Profil Pejabat
        if ($userKaprodiTI && $jabatanKaprodi && $prodiTI) {
            Pejabat::create([
                'user_id' => $userKaprodiTI->id,
                'nip_atau_nidn' => 'NIDN001',
                'nama_lengkap' => 'Anton Kaprodi TI',
                'master_jabatan_id' => $jabatanKaprodi->id,
                'program_studi_id' => $prodiTI->id, // Scope Prodi
                'fakultas_id' => null,
                'tanda_tangan_path' => 'ttd/kaprodi-ti.png' // Contoh path
            ]);
        }
        if ($userDekanFST && $jabatanDekan && $fakultasFST) {
            Pejabat::create([
                'user_id' => $userDekanFST->id,
                'nip_atau_nidn' => 'NIDN002',
                'nama_lengkap' => 'Rina Dekan FST',
                'master_jabatan_id' => $jabatanDekan->id,
                'fakultas_id' => $fakultasFST->id, // Scope Fakultas
                'program_studi_id' => null,
                'tanda_tangan_path' => 'ttd/dekan-fst.png' // Contoh path
            ]);
        }

        // Buat Profil Admin Akademik (BARU)
        if ($userAdmin) {
             AdminAkademik::create([
                'user_id' => $userAdmin->id,
                'nip_akademik' => 'ADM001',
                'nama_lengkap' => 'Administrator Akademik Utama',
            ]);
        }
    }
}

