<?php

namespace App\Http\Requests\AdminAkademik;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum; // Pastikan Enum diimpor jika menggunakan Enum PHP 8.1+

class StoreJenisSuratRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan hanya admin akademik yang bisa
        return $this->user()->role->nama_role === 'admin akademik';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_surat' => 'required|string|max:255',
            'kode_surat' => 'required|string|max:20|unique:jenis_surat,kode_surat',
            'kategori' => ['required', 'string', Rule::in(['Akademik', 'Kemahasiswaan', 'Keuangan', 'Penelitian', 'Umum'])],
            // Atau jika pakai Enum: new Enum(\App\Enums\KategoriSurat::class)
            'format_penomoran' => 'required|string|max:255',
            'isi_template' => 'required|string',

            // Validasi untuk Form Schema (array of objects)
            'form_schema' => 'nullable|array',
            'form_schema.*.label' => 'required_with:form_schema|string|max:100',
            'form_schema.*.name' => 'required_with:form_schema|string|max:50|regex:/^[a-zA-Z0-9_]+$/', // Hanya huruf, angka, underscore
            'form_schema.*.type' => ['required_with:form_schema', 'string', Rule::in(['text', 'textarea', 'date', 'number', 'select', 'file'])], // Tipe input yang diizinkan

            // --- VALIDASI BARU UNTUK ALUR APPROVAL ---
            'approvals' => 'required|array|min:1', // Harus ada minimal 1 approval
            'approvals.*.master_jabatan_id' => 'required|integer|exists:master_jabatan,id',
            'approvals.*.scope' => ['required', 'string', Rule::in(['PRODI', 'FAKULTAS', 'UNIVERSITAS'])],
            // -----------------------------------------
        ];
    }

     /**
     * Pesan error kustom (opsional tapi bagus).
     *
     * @return array
     */
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

