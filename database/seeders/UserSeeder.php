<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate(); // Hapus user lama
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil ID roles
        $roleMahasiswa = Role::where('nama_role', 'mahasiswa')->first();
        $roleStaff = Role::where('nama_role', 'staff jurusan')->first();
        $rolePejabat = Role::where('nama_role', 'pejabat')->first();
        $roleAdminAkademik = Role::where('nama_role', 'admin akademik')->first(); // Ambil role baru

        // Buat User
        User::create([
            'email' => 'admin@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $roleAdminAkademik->id, // Gunakan role baru
            'email_verified_at' => now(),
        ]);
        User::create([
            'email' => 'mahasiswa@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $roleMahasiswa->id,
            'email_verified_at' => now(),
        ]);
        User::create([
            'email' => 'staff.ti@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $roleStaff->id,
            'email_verified_at' => now(),
        ]);
        User::create([
            'email' => 'kaprodi.ti@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $rolePejabat->id,
            'email_verified_at' => now(),
        ]);
        User::create([
            'email' => 'dekan.fst@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $rolePejabat->id,
            'email_verified_at' => now(),
        ]);
    }
}

