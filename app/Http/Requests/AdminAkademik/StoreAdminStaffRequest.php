<?php

namespace App\Http\Requests\AdminAkademik;

use Illuminate\Foundation\Http\FormRequest;

class StorePejabatRequest extends FormRequest
{
    public function authorize()
    {
        // Mengizinkan semua user (bisa disesuaikan dengan logika autentikasi)
        return $this->user()->role->nama_role === 'admin akademik';
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email', // Validasi email
            'nip_atau_nidn' => 'nullable|string|max:50',    // Validasi NIP/NIDN (opsional)
            'nama_lengkap' => 'required|string',            // Validasi nama lengkap
            'jabatan' => 'required|exists:master_jabatan,id', // Validasi jabatan
            'fakultas_id' => 'nullable|exists:fakultas,id',  // Validasi fakultas (opsional)
            'program_studi_id' => 'nullable|exists:program_studi,id', // Validasi program studi (opsional)
        ];
    }
}
