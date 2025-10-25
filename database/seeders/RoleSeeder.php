<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel dulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Role::create(['nama_role' => 'mahasiswa']);
        Role::create(['nama_role' => 'staff jurusan']);
        Role::create(['nama_role' => 'pejabat']);
        Role::create(['nama_role' => 'admin akademik']);
    }
}
