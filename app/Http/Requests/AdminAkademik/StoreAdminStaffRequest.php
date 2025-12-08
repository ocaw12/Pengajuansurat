<?php

namespace App\Http\Requests\AdminAkademik;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminStaffRequest extends FormRequest
{
    public function authorize()
    {
        return true; // sementara dibuka saja
    }

    public function rules()
    {
        return [
            'nip_staff'        => 'required|string|max:50',
            'nama_lengkap'     => 'required|string|max:255',
            'program_studi_id' => 'required|exists:program_studi,id',

            // email user baru, harus unik di tabel users
            'email'            => 'required|email|unique:users,email',

            // opsional
            'no_telepon'       => 'nullable|string|max:20',
             'is_active'        => 'nullable|boolean',

        ];
    }
}
