<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Admin Akademik', 'Staf Jurusan', 'Mahasiswa', 'Pejabat Berwenang'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nama_role' => $role]);
        }
    }
}
