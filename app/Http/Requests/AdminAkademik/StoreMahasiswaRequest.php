<?php

namespace App\Http\Requests\AdminAkademik;

use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Atur ini sesuai kebutuhan otorisasi
    }

    public function rules()
    {
        // Ambil ID mahasiswa dari route untuk pengecekan saat edit
        $mahasiswaId = $this->route('mahasiswa'); // Ambil ID mahasiswa dari URL

        return [
            'nim' => 'required|unique:mahasiswa,nim,' . $mahasiswaId . '|max:50', // Validasi NIM
            'nama_lengkap' => 'required|max:255',
            'tempat_lahir' => 'required|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|max:255',
            'jenis_kelamin' => 'required|in:Laki_laki,Perempuan', // Validasi jenis kelamin
            'angkatan' => 'required|integer|min:2000|max:'.(date('Y')+1), // Validasi angkatan
            'program_studi_id' => 'required|exists:program_studi,id',
            'email' => 'required|email|unique:users,email,' . $this->route('mahasiswa'), // Validasi email dengan pengecualian untuk mahasiswa yang sedang diedit
        ];
    }
}
