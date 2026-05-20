<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Panggil seeder secara berurutan
        // PresentationSeeder dipanggil lebih dulu agar Master Jabatan, Fakultas, Prodi, dll tersedia
        $this->call([
            PresentationSeeder::class,
            AkademikDanSuratSeeder::class,
        ]);
    }
}