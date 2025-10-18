<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role Admin Akademik
        $role = Role::where('nama_role', 'Admin Akademik')->first();

        // Membuat user admin
        User::updateOrCreate([
            'id_user' => 'USR-ADM01'
        ], [
            'name' => 'Admin Akademik',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('password123'),
            'id_role' => $role->id_role
        ]);
    }
}
