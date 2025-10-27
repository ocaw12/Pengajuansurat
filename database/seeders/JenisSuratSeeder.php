<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JenisSurat::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        JenisSurat::create([
            'kode_surat' => 'SK-AKTIF',
            'nama_surat' => 'Surat Keterangan Aktif Kuliah',
            'kategori' => 'Akademik',
            'isi_template' => "Nomor: [nomor_surat]\n\nYang bertanda tangan di bawah ini, [jabatan_pejabat] [fakultas], menerangkan bahwa:\n\nNama: [nama_mahasiswa]\nNIM: [nim]\nProgram Studi: [prodi]\nSemester: ...\n\nAdalah benar mahasiswa aktif pada semester ... Tahun Akademik ... Universitas Proklamasi 45.\nSurat keterangan ini dibuat untuk keperluan [keperluan].\n\nDemikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.",
            'form_schema' => null, // Tidak perlu field tambahan
            'format_penomoran' => '{nomor_urut}/SK/{kode_unit}/{bulan_romawi}/{tahun}', // {kode_unit} akan mengambil dari prodi
            'counter_nomor_urut' => 0,
            'counter_tahun' => null,
        ]);

        JenisSurat::create([
            'kode_surat' => 'SP-PENELITIAN',
            'nama_surat' => 'Surat Izin Penelitian',
            'kategori' => 'Penelitian',
            'isi_template' => "Nomor: [nomor_surat]\n\nDengan hormat,\nYang bertanda tangan di bawah ini, [jabatan_pejabat] [fakultas], memberikan izin kepada mahasiswa:\n\nNama: [nama_mahasiswa]\nNIM: [nim]\nProgram Studi: [prodi]\n\nUntuk melaksanakan penelitian dengan judul \"[judul_penelitian]\" di [lokasi].\nPenelitian akan dilaksanakan mulai tanggal [tgl_mulai] sampai dengan [tgl_selesai].\n\nDemikian surat izin ini dibuat untuk dapat dipergunakan sebagaimana mestinya.",
            'form_schema' => json_encode([ // Encode manual jika tidak pakai Factory
                ['name' => 'judul_penelitian', 'label' => 'Judul Penelitian', 'type' => 'text', 'required' => true],
                ['name' => 'lokasi', 'label' => 'Lokasi/Instansi Penelitian', 'type' => 'text', 'required' => true],
                ['name' => 'tgl_mulai', 'label' => 'Tanggal Mulai', 'type' => 'date', 'required' => true],
                ['name' => 'tgl_selesai', 'label' => 'Tanggal Selesai', 'type' => 'date', 'required' => true],
            ]),
            'format_penomoran' => '{nomor_urut}/SP/{kode_unit}/{bulan_romawi}/{tahun}',
            'counter_nomor_urut' => 0,
            'counter_tahun' => null,
        ]);
    }
}

