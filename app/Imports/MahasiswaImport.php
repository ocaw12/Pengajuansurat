<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // cek nim sudah ada atau belum
        $cek = Mahasiswa::where('nim', $row['nim'])->first();

        if ($cek) {
            return null; // skip kalau nim sudah ada
        }

        return DB::transaction(function () use ($row) {

            $user = User::create([
                'email' => $row['email'],
                'password' => Hash::make($row['nim']), // password = NIM
                'role_id' => 1,
                'is_active' => 1
            ]);

            return new Mahasiswa([
                'user_id' => $user->id,
                'nim' => $row['nim'],
                'nama_lengkap' => $row['nama_lengkap'],
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'alamat' => $row['alamat'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'no_telepon' => $row['no_telepon'],
                'program_studi_id' => $row['program_studi_id'],
                'angkatan' => $row['angkatan']
            ]);
        });
    }
}