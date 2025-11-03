<?php
// app/Http/Requests/ProdiRequest.php
namespace App\Http\Requests\AdminAkademik;
use Illuminate\Foundation\Http\FormRequest;

class ProdiRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pastikan hanya user yang berwenang bisa mengakses
    }

    public function rules()
    {
        return [
            'nama_prodi' => 'required|string|max:100',
            'kode_prodi' => 'required|string|max:10',
            'fakultas_id' => 'required|exists:fakultas,id' // Pastikan fakultas_id ada di tabel fakultas
        ];
    }
}
