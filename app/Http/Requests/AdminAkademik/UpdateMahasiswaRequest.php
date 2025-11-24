<?php

namespace App\Http\Requests\AdminAkademik;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMahasiswaRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Atur ini sesuai kebutuhan otorisasi
    }

    public function rules()
    {
        // Ambil ID mahasiswa dari route untuk pengecekan saat edit
        $mahasiswaId = $this->route('mahasiswa'); // Ambil ID mahasiswa dari URL
        // Mengambil ID dari URL untuk mengecualikan email milik staff yang sedang diedit
        $userId = \App\Models\Mahasiswa::findOrFail($mahasiswaId)->user_id; 
        return [
            'nim' => 'required|unique:mahasiswa,nim,' . $mahasiswaId . '|max:50', // Validasi NIM (kecuali untuk mahasiswa yang sedang diedit)
            'nama_lengkap' => 'required|max:255',
            'tempat_lahir' => 'required|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|max:255',
            'jenis_kelamin' => 'required|in:Laki_laki,Perempuan', // Validasi jenis kelamin
            'angkatan' => 'required|integer|min:2000|max:'.(date('Y')+1), // Validasi angkatan
            'program_studi_id' => 'required|exists:program_studi,id',
            'no_telepon' => 'nullable|string|max:20',

            'email' => 'required|email|unique:users,email,' . $userId, // Validasi email dengan pengecualian untuk mahasiswa yang sedang diedit
        ];
    }
}
