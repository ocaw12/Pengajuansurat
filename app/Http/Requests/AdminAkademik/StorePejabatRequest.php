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
           //'email' => 'required|email|unique:users,email',
            //'password' => 'required|min:6',
            //'nip_atau_nidn' => 'nullable|string|max:50',
            //'nama_lengkap' => 'required|string',
            //'jabatan' => 'required|exists:master_jabatan,id',
            //'fakultas_id' => 'nullable|exists:fakultas,id',
            //'program_studi_id' => 'nullable|exists:program_studi,id',
        ];
    }
}
