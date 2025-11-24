<?php

namespace App\Http\Requests\AdminAkademik;

use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // ID mahasiswa untuk pengecualian unique (kalau rutenya ada)
        $mahasiswaId = $this->route('mahasiswa');

        return [
            'nim' => 'required|max:50|unique:mahasiswa,nim,' . $mahasiswaId,
            'nama_lengkap' => 'required|max:255',
            'tempat_lahir' => 'required|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|max:255',
            'jenis_kelamin' => 'required|in:Laki_laki,Perempuan',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'program_studi_id' => 'required|exists:program_studi,id',

            // ⬇️ EMAIL
            'email' => 'required|email|unique:users,email,' . $mahasiswaId,

            // ⬇️ NOMOR TELEPON BARU DITAMBAHKAN
            'no_telepon' => 'nullable|string|max:20',
        ];
    }
}
