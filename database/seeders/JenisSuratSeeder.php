<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;
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

        $tahunSekarang = date('Y');

        // Surat 1: Keterangan Aktif (Tanpa Form Dinamis)
        JenisSurat::create([
            'kode_surat' => 'SK-AKTIF',
            'nama_surat' => 'Surat Keterangan Aktif',
            'kategori' => 'Akademik',
            'isi_template' => "Yang bertanda tangan di bawah ini, menerangkan bahwa:\n\nNama: [nama_mahasiswa]\nNIM: [nim]\nProgram Studi: [prodi]\n\nAdalah benar mahasiswa aktif di Program Studi [prodi] Fakultas [fakultas] Universitas Proklamasi 45.\nSurat keterangan ini dibuat untuk keperluan [keperluan].",
            'form_schema' => null,
            'format_penomoran' => '{nomor_urut}/SK/{kode_unit}/{bulan_romawi}/{tahun}',
            'counter_nomor_urut' => 0,
            'counter_tahun' => $tahunSekarang,
        ]);

        // Surat 2: Izin Penelitian (Dengan Form Dinamis)
        JenisSurat::create([
            'kode_surat' => 'IZIN-PEN',
            'nama_surat' => 'Surat Izin Penelitian',
            'kategori' => 'Penelitian',
            'isi_template' => "Dengan ini kami memberikan izin kepada:\n\nNama: [nama_mahasiswa]\nNIM: [nim]\nProgram Studi: [prodi]\n\nUntuk melaksanakan penelitian di [lokasi_penelitian] dengan judul \"[judul_penelitian]\" dalam rangka penyusunan tugas akhir.\nSurat ini dibuat untuk keperluan [keperluan].",
            'form_schema' => [
                ['name' => 'judul_penelitian', 'label' => 'Judul Penelitian Anda', 'type' => 'text'],
                ['name' => 'lokasi_penelitian', 'label' => 'Nama Instansi/Lokasi Penelitian', 'type' => 'text'],
            ],
            'format_penomoran' => '{nomor_urut}/SP/{kode_unit}/{bulan_romawi}/{tahun}',
            'counter_nomor_urut' => 0,
            'counter_tahun' => $tahunSekarang,
        ]);
    }
}
