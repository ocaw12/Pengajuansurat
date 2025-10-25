<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pastikan urutannya seperti ini:
        $this->call([
            // 1. Buat Roles dulu
            RoleSeeder::class,
            
            // 2. Buat Data Master Akademik
            DataMasterAkademikSeeder::class,
            
            // 3. Buat Akun User (tergantung Roles)
            UserSeeder::class,
            
            // 4. Buat Profil (TERGANTUNG User & Data Master)
            // Ini adalah seeder yang kemungkinan gagal/terlewat
            ProfilSeeder::class, 
            
            // 5. Buat Aturan Surat (tergantung Data Master & Jenis Surat)
            JenisSuratSeeder::class,
            AlurApprovalSeeder::class,
        ]);
    }
}
