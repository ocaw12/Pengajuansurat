<?php

namespace App\Http\Requests\AdminAkademik;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminStaffRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Mengizinkan semua pengguna untuk melakukan request
    }

    public function rules()
    {
        $adminStaffId = $this->route('admin_staff'); // Ambil ID dari URL untuk pengecualian email
        $userId = \App\Models\AdminStaff::findOrFail($adminStaffId)->user_id; 

        return [
            'nip_staff' => 'required|max:50',
            'nama_lengkap' => 'required|max:255',
            'program_studi_id' => 'required|exists:program_studi,id',
            // Pengecekan email, mengabaikan email yang sama dengan yang sedang diedit
            'email' => 'required|email|unique:users,email,' . $userId, 
         'no_telepon' => 'nullable|string|max:20',
          'is_active'        => 'nullable|boolean',


        ];
    }
}
