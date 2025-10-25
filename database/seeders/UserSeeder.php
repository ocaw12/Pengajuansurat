<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil ID Role
        $roleAdmin = Role::where('nama_role', 'admin akademik')->first();
        $roleMhs = Role::where('nama_role', 'mahasiswa')->first();
        $roleStaff = Role::where('nama_role', 'staff jurusan')->first();
        $rolePejabat = Role::where('nama_role', 'pejabat')->first();

        // Buat User Admin Akademik (Untuk Anda)
        User::create([
            'email' => 'admin@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $roleAdmin->id,
            'email_verified_at' => now(),
        ]);

        // Buat User Mahasiswa (Untuk Tes)
        User::create([
            'email' => 'mahasiswa@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $roleMhs->id,
            'email_verified_at' => now(),
        ]);

        // Buat User Staff Jurusan TI (Untuk Tes)
        User::create([
            'email' => 'staff.ti@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $roleStaff->id,
            'email_verified_at' => now(),
        ]);

        // Buat User Pejabat (Kaprodi TI)
        User::create([
            'email' => 'kaprodi.ti@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $rolePejabat->id,
            'email_verified_at' => now(),
        ]);

        // Buat User Pejabat (Dekan FST)
        User::create([
            'email' => 'dekan.fst@up45.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $rolePejabat->id,
            'email_verified_at' => now(),
        ]);
    }
}
