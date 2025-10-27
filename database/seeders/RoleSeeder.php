<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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

        $roles = [
            ['nama_role' => 'mahasiswa'],
            ['nama_role' => 'staff jurusan'],
            ['nama_role' => 'pejabat'],
            ['nama_role' => 'admin akademik'], // Nama baru
        ];

        Role::insert($roles);
    }
}

