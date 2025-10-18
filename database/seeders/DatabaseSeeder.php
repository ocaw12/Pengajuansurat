<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class, // Seed roles terlebih dahulu
            UserSeeder::class, // Baru seed user setelah roles ada
            AdminSeeder::class,   // Seeder untuk admin
        ]);
    }
}
