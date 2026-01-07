<?php

namespace App\Http\Requests\AdminAkademik;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJenisSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role->nama_role === 'admin akademik';
    }

    public function rules(): array
    {
        // sesuaikan dengan nama parameter di route-mu
        // misal: Route::put('jenis-surat/{jenis_surat}', ...)
        $id = $this->route('jenis_surat') ?? $this->route('id');

        return [
            'nama_surat' => 'required|string|max:255',
            'kode_surat' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_surat', 'kode_surat')->ignore($id),
            ],
            'kategori' => ['required', 'string', Rule::in(['Akademik', 'Kemahasiswaan', 'Keuangan', 'Penelitian', 'Umum'])],
            'format_penomoran' => 'required|string|max:255',
            'isi_template' => 'required|string',

            'form_schema' => 'nullable|array',
            'form_schema.*.label' => 'required_with:form_schema|string|max:100',
            'form_schema.*.name' => 'required_with:form_schema|string|max:50|regex:/^[a-zA-Z0-9_]+$/',
            'form_schema.*.type' => ['required_with:form_schema', 'string', Rule::in(['text', 'textarea', 'date', 'number', 'select', 'file'])],

            'approvals' => 'required|array|min:1',
            'approvals.*.master_jabatan_id' => 'required|integer|exists:master_jabatan,id',
            'approvals.*.scope' => ['required', 'string', Rule::in(['PRODI', 'FAKULTAS', 'UNIVERSITAS'])],
        ];
    }

    public function messages(): array
    {
        return [
            'form_schema.*.name.regex' => 'Nama Kunci (Shortcode) hanya boleh berisi huruf, angka, dan underscore (_).',
            'approvals.required' => 'Minimal harus ada satu langkah approval.',
            'approvals.min' => 'Minimal harus ada satu langkah approval.',
            'approvals.*.master_jabatan_id.required' => 'Jabatan pada langkah approval #:position wajib diisi.',
            'approvals.*.scope.required' => 'Scope pada langkah approval #:position wajib diisi.',
        ];
    }
}
