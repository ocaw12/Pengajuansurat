<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PresentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();
        $defaultPassword = Hash::make('password'); // Password default untuk semua user: password

        $this->command->info('Memulai proses seeding data presentasi...');

        // ==========================================
        // 1. SEEDER ROLES
        // ==========================================
        $roles = [
            ['id' => 1, 'nama_role' => 'mahasiswa'],
            ['id' => 2, 'nama_role' => 'staff jurusan'],
            ['id' => 3, 'nama_role' => 'pejabat'],
            ['id' => 4, 'nama_role' => 'admin akademik'],
        ];
        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['id' => $role['id']], array_merge($role, ['created_at' => $now, 'updated_at' => $now]));
        }

        // ==========================================
        // 2. SEEDER MASTER JABATAN
        // ==========================================
        $jabatans = [
            ['id' => 1, 'nama_jabatan' => 'Kaprodi'],
            ['id' => 2, 'nama_jabatan' => 'Dekan'],
            ['id' => 3, 'nama_jabatan' => 'Wakil Rektor 1'],
        ];
        foreach ($jabatans as $jabatan) {
            DB::table('master_jabatan')->updateOrInsert(['id' => $jabatan['id']], array_merge($jabatan, ['created_at' => $now, 'updated_at' => $now]));
        }

        // ==========================================
        // 3. SEEDER FAKULTAS & PROGRAM STUDI
        // ==========================================
        $fakultasData = [
            [
                'nama' => 'Fakultas Ekonomi', 'kode' => 'FE',
                'prodi' => [['nama' => 'Manajemen', 'kode' => 'MNJ']]
            ],
            [
                'nama' => 'Fakultas Hukum', 'kode' => 'FH',
                'prodi' => [['nama' => 'Ilmu Hukum', 'kode' => 'HKM']]
            ],
            [
                'nama' => 'Fakultas Ilmu Sosial dan Ilmu Politik', 'kode' => 'FISIPOL',
                'prodi' => [['nama' => 'Administrasi Publik', 'kode' => 'ADP']]
            ],
            [
                'nama' => 'Fakultas Psikologi', 'kode' => 'FPS',
                'prodi' => [['nama' => 'Psikologi', 'kode' => 'PSK']]
            ],
            [
                'nama' => 'Fakultas Teknik', 'kode' => 'FT',
                'prodi' => [
                    ['nama' => 'Teknik Perminyakan', 'kode' => 'TPM'],
                    ['nama' => 'Teknik Mesin', 'kode' => 'TM'],
                    ['nama' => 'Teknik Lingkungan', 'kode' => 'TL']
                ]
            ],
        ];

        $prodiIds = []; // Untuk menyimpan id prodi yang nanti dipakai mahasiswa & staff
        $fakultasIds = [];

        foreach ($fakultasData as $fakData) {
            $fakId = DB::table('fakultas')->insertGetId([
                'nama_fakultas' => $fakData['nama'],
                'kode_fakultas' => $fakData['kode'],
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $fakultasIds[$fakData['kode']] = $fakId;

            foreach ($fakData['prodi'] as $prdData) {
                $prdId = DB::table('program_studi')->insertGetId([
                    'nama_prodi' => $prdData['nama'],
                    'kode_prodi' => $prdData['kode'],
                    'fakultas_id' => $fakId,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                $prodiIds[$prdData['kode']] = $prdId;
            }
        }

        // ==========================================
        // 4. SEEDER ADMIN AKADEMIK UTAMA
        // ==========================================
        $adminUserId = DB::table('users')->insertGetId([
            'email' => 'admin.akademik@up45.ac.id',
            'role_id' => 4,
            'password' => $defaultPassword,
            'is_active' => 1,
            'email_verified_at' => $now,
            'created_at' => $now, 'updated_at' => $now
        ]);
        DB::table('admin_akademik')->insert([
            'user_id' => $adminUserId,
            'nip_akademik' => 'ADM-UP45-001',
            'nama_lengkap' => 'Administrator Akademik',
            'no_telepon' => '081234567890',
            'created_at' => $now, 'updated_at' => $now
        ]);

        // ==========================================
        // 5. SEEDER PEJABAT (DEKAN & KAPRODI)
        // ==========================================
        $pejabatList = [
            // DEKAN (jabatan id: 2)
            ['role' => 2, 'fakultas' => 'FE', 'prodi' => null, 'nama' => 'Dr. H. Ahmad Sudirman, S.E., M.M.', 'email' => 'dekan.fe@up45.ac.id'],
            ['role' => 2, 'fakultas' => 'FH', 'prodi' => null, 'nama' => 'Prof. Dr. Maria Setiawati, S.H., M.Hum.', 'email' => 'dekan.fh@up45.ac.id'],
            ['role' => 2, 'fakultas' => 'FISIPOL', 'prodi' => null, 'nama' => 'Dr. Budi Wibowo, S.IP., M.Si.', 'email' => 'dekan.fisipol@up45.ac.id'],
            ['role' => 2, 'fakultas' => 'FPS', 'prodi' => null, 'nama' => 'Dr. Rina Lestari, S.Psi., M.Psi., Psikolog', 'email' => 'dekan.fps@up45.ac.id'],
            ['role' => 2, 'fakultas' => 'FT', 'prodi' => null, 'nama' => 'Prof. Ir. Joko Susilo, M.T., Ph.D.', 'email' => 'dekan.ft@up45.ac.id'],
            
            // KAPRODI (jabatan id: 1)
            ['role' => 1, 'fakultas' => null, 'prodi' => 'MNJ', 'nama' => 'Rahmat Hidayat, S.E., M.B.A.', 'email' => 'kaprodi.mnj@up45.ac.id'],
            ['role' => 1, 'fakultas' => null, 'prodi' => 'HKM', 'nama' => 'Dian Sastrowardoyo, S.H., LL.M.', 'email' => 'kaprodi.hkm@up45.ac.id'],
            ['role' => 1, 'fakultas' => null, 'prodi' => 'ADP', 'nama' => 'Anwar Sanusi, S.Sos., M.PA.', 'email' => 'kaprodi.adp@up45.ac.id'],
            ['role' => 1, 'fakultas' => null, 'prodi' => 'PSK', 'nama' => 'Fitriani Purnamasari, S.Psi., M.A.', 'email' => 'kaprodi.psk@up45.ac.id'],
            ['role' => 1, 'fakultas' => null, 'prodi' => 'TPM', 'nama' => 'Ir. Bambang Pamungkas, M.Sc.', 'email' => 'kaprodi.tpm@up45.ac.id'],
            ['role' => 1, 'fakultas' => null, 'prodi' => 'TM', 'nama' => 'Dr. Eng. Wahyu Pratama, S.T., M.T.', 'email' => 'kaprodi.tm@up45.ac.id'],
            ['role' => 1, 'fakultas' => null, 'prodi' => 'TL', 'nama' => 'Lestari Handayani, S.T., M.Env.', 'email' => 'kaprodi.tl@up45.ac.id'],
        ];

        foreach ($pejabatList as $pj) {
            $pjUserId = DB::table('users')->insertGetId([
                'email' => $pj['email'],
                'role_id' => 3, // Role Pejabat
                'password' => $defaultPassword,
                'is_active' => 1,
                'email_verified_at' => $now,
                'created_at' => $now, 'updated_at' => $now
            ]);

            DB::table('pejabat')->insert([
                'user_id' => $pjUserId,
                'nip_atau_nidn' => $faker->numerify('19########20######'),
                'nama_lengkap' => $pj['nama'],
                'no_telepon' => $faker->phoneNumber,
                'master_jabatan_id' => $pj['role'], // 1: Kaprodi, 2: Dekan
                'fakultas_id' => $pj['fakultas'] ? $fakultasIds[$pj['fakultas']] : null,
                'program_studi_id' => $pj['prodi'] ? $prodiIds[$pj['prodi']] : null,
                'created_at' => $now, 'updated_at' => $now
            ]);
        }

        // ==========================================
        // 6. SEEDER STAFF JURUSAN (1 per prodi)
        // ==========================================
        $staffNames = [
            'MNJ' => 'Siti Aminah, S.E.', 'HKM' => 'Agus Supriyanto, S.H.', 
            'ADP' => 'Rini Wulandari, S.A.P.', 'PSK' => 'Dwi Cahyono, S.Psi.', 
            'TPM' => 'Hendro Siswanto, A.Md.', 'TM' => 'Slamet Riyadi, S.T.', 
            'TL' => 'Nia Ramadhani, S.T.'
        ];

        foreach ($staffNames as $kodeProdi => $namaStaff) {
            $staffUserId = DB::table('users')->insertGetId([
                'email' => strtolower('staff.'.$kodeProdi.'@up45.ac.id'),
                'role_id' => 2, // Role Staff
                'password' => $defaultPassword,
                'is_active' => 1,
                'email_verified_at' => $now,
                'created_at' => $now, 'updated_at' => $now
            ]);

            DB::table('admin_staff')->insert([
                'user_id' => $staffUserId,
                'nip_staff' => $faker->numerify('STF########'),
                'nama_lengkap' => $namaStaff,
                'program_studi_id' => $prodiIds[$kodeProdi],
                'no_telepon' => $faker->phoneNumber,
                'created_at' => $now, 'updated_at' => $now
            ]);
        }

        // ==========================================
        // 7. SEEDER MAHASISWA (100 Rows)
        // ==========================================
        $prodiCodes = array_keys($prodiIds);
        $jenisKelaminEnum = ['Laki_laki', 'Perempuan'];

        for ($i = 1; $i <= 100; $i++) {
            $randomProdiCode = $faker->randomElement($prodiCodes);
            $jk = $faker->randomElement($jenisKelaminEnum);
            
            // Generate nama berdasarkan JK
            $namaMhs = $jk === 'Laki_laki' ? $faker->firstNameMale . ' ' . $faker->lastNameMale : $faker->firstNameFemale . ' ' . $faker->lastNameFemale;
            
            // Format NIM Dummy yang meyakinkan
            $angkatan = $faker->numberBetween(2020, 2024);
            $nim = substr($angkatan, 2, 2) . $prodiIds[$randomProdiCode] . $faker->unique()->numerify('#####');

            $mhsUserId = DB::table('users')->insertGetId([
                'email' => strtolower(str_replace(' ', '', $namaMhs)) . $faker->numberBetween(1,99) . '@mhs.up45.ac.id',
                'role_id' => 1, // Role Mahasiswa
                'password' => $defaultPassword,
                'is_active' => 1,
                'email_verified_at' => $now,
                'created_at' => $now, 'updated_at' => $now
            ]);

            DB::table('mahasiswa')->insert([
                'user_id' => $mhsUserId,
                'nim' => $nim,
                'nama_lengkap' => $namaMhs,
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '2005-01-01'),
                'alamat' => $faker->address,
                'jenis_kelamin' => $jk,
                'no_telepon' => $faker->phoneNumber,
                'program_studi_id' => $prodiIds[$randomProdiCode],
                'angkatan' => $angkatan,
                'created_at' => $now, 'updated_at' => $now
            ]);
        }

        $this->command->info('100 Data Mahasiswa, Staff, Pejabat, dan Master Data berhasil di-generate!');
    }
}