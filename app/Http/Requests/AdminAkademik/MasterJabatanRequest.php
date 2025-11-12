<?php
namespace App\Http\Requests\AdminAkademik;

use Illuminate\Foundation\Http\FormRequest;

class MasterJabatanRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Sesuaikan dengan aturan otorisasi Anda
    }

    public function rules()
    {
        return [
            'nama_jabatan' => 'required|string|max:100|unique:master_jabatan,nama_jabatan,' . $this->route('jabatan'), // Untuk update, biarkan nama jabatan yang sama
        ];
    }
}
