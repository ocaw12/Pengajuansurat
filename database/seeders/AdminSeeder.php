<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role Admin dari tabel `roles`
        $role = Role::where('nama_role', 'Admin Akademik')->first();

        // Membuat atau memperbarui user terlebih dahulu di tabel `users`
        $user = User::updateOrCreate(
            ['id_user' => 'USR-ADM01'], // Jika id_user sudah ada, ini akan memperbarui
            [
                'name' => 'Admin Akademik',
                'email' => 'admin@kampus.ac.id',
                'password' => Hash::make('password123'),
                'id_role' => $role->id_role,
            ]
        );

        // Membuat admin di tabel `admin`
        Admin::updateOrCreate(
            ['id_user' => $user->id_user], // Jika id_user sudah ada, ini akan memperbarui
            [
                'id_Admin' => 'ADMIN123456',
                'nama' => 'Admin Akademik',
            ]
        );
    }
}
