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
        $this->call([
            RoleSeeder::class,
            DataMasterAkademikSeeder::class,
            UserSeeder::class,
            ProfilSeeder::class, // Harus setelah User & DataMaster
            JenisSuratSeeder::class,
            AlurApprovalSeeder::class, // Harus setelah JenisSurat & MasterJabatan
        ]);
    }
}

