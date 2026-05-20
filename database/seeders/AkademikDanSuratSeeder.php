<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AkademikDanSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $this->command->info('Memulai seeding Tahun Ajaran, Semester, dan Template Surat...');

        // ==========================================
        // 1. SEEDER TAHUN AJARAN & SEMESTER
        // ==========================================
        $tahunAjarans = [
            ['tahun' => '2024/2025', 'is_aktif' => 0],
            ['tahun' => '2025/2026', 'is_aktif' => 1], // Tahun Ajaran Aktif saat ini
            ['tahun' => '2026/2027', 'is_aktif' => 0],
        ];

        foreach ($tahunAjarans as $ta) {
            $taId = DB::table('tahun_ajaran')->insertGetId(
                array_merge($ta, ['created_at' => $now, 'updated_at' => $now])
            );

            // Buat Semester Ganjil & Genap untuk setiap Tahun Ajaran
            DB::table('semester')->insert([
                [
                    'tahun_ajaran_id' => $taId,
                    'semester' => 'GANJIL',
                    'is_aktif' => ($ta['is_aktif'] == 1) ? 0 : 0, // Misal saat ini genap yang aktif
                    'created_at' => $now, 'updated_at' => $now
                ],
                [
                    'tahun_ajaran_id' => $taId,
                    'semester' => 'GENAP',
                    'is_aktif' => ($ta['is_aktif'] == 1) ? 1 : 0, // Semester Aktif
                    'created_at' => $now, 'updated_at' => $now
                ]
            ]);
        }
        $this->command->info('Data Tahun Ajaran & Semester berhasil ditambahkan.');

        // ==========================================
        // 2. SEEDER JENIS SURAT & TEMPLATENYA
        // ==========================================
        $surats = [
            [
                'kode_surat' => 'SK-AKTIF',
                'nama_surat' => 'Surat Keterangan Aktif Kuliah',
                'kategori' => 'Akademik',
                'format_penomoran' => '{nomor_urut}/SK/{kode_unit}/{bulan_romawi}/{tahun}',
                'form_schema' => json_encode([
                    ['name' => 'keperluan', 'type' => 'text', 'label' => 'Keperluan (Contoh: Syarat Beasiswa/Tunjangan Anak)']
                ]),
                'isi_template' => "Yang bertanda tangan di bawah ini Dekan Fakultas [fakultas] Universitas Proklamasi 45, menerangkan bahwa:\n\nNama: [nama_mahasiswa]\nNIM: [nim]\nProgram Studi: [prodi]\n\nAdalah benar mahasiswa yang bersangkutan berstatus AKTIF pada Semester [semester] Tahun Akademik [tahun_akademik].\n\nSurat keterangan ini dibuat dengan sebenar-benarnya untuk keperluan: [keperluan].\nDemikian surat ini dibuat agar dapat dipergunakan sebagaimana mestinya."
            ],
            [
                'kode_surat' => 'SP-PENELITIAN',
                'nama_surat' => 'Surat Izin Penelitian / Pengambilan Data',
                'kategori' => 'Penelitian',
                'format_penomoran' => '{nomor_urut}/SP/{kode_unit}/{bulan_romawi}/{tahun}',
                'form_schema' => json_encode([
                    ['name' => 'instansi_tujuan', 'type' => 'text', 'label' => 'Nama Instansi Tujuan'],
                    ['name' => 'alamat_instansi', 'type' => 'textarea', 'label' => 'Alamat Instansi'],
                    ['name' => 'judul_penelitian', 'type' => 'text', 'label' => 'Judul Penelitian/Tugas Akhir']
                ]),
                'isi_template' => "Nomor: [nomor_surat]\nLampiran: -\nPerihal: Permohonan Izin Penelitian\n\nKepada Yth.\nPimpinan [instansi_tujuan]\ndi [alamat_instansi]\n\nDengan hormat,\nDalam rangka penyelesaian tugas akhir/skripsi mahasiswa Universitas Proklamasi 45, kami memohon kesediaan Bapak/Ibu untuk memberikan izin penelitian/pengambilan data kepada mahasiswa kami:\n\nNama: [nama_mahasiswa]\nNIM: [nim]\nProgram Studi: [prodi]\nJudul Penelitian: \"[judul_penelitian]\"\n\nDemikian permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih."
            ],
            [
                'kode_surat' => 'SP-MAGANG',
                'nama_surat' => 'Surat Pengantar Magang / Kerja Praktik',
                'kategori' => 'Akademik',
                'format_penomoran' => '{nomor_urut}/MAGANG/{kode_unit}/{bulan_romawi}/{tahun}',
                'form_schema' => json_encode([
                    ['name' => 'nama_perusahaan', 'type' => 'text', 'label' => 'Nama Perusahaan/Instansi'],
                    ['name' => 'alamat_perusahaan', 'type' => 'textarea', 'label' => 'Alamat Perusahaan/Instansi'],
                    ['name' => 'tgl_mulai', 'type' => 'date', 'label' => 'Tanggal Mulai Magang'],
                    ['name' => 'tgl_selesai', 'type' => 'date', 'label' => 'Tanggal Selesai Magang']
                ]),
                'isi_template' => "Nomor: [nomor_surat]\nHal: Permohonan Kerja Praktik / Magang\n\nKepada Yth.\nHRD / Pimpinan [nama_perusahaan]\n[alamat_perusahaan]\n\nDengan hormat,\nSebagai salah satu syarat kurikulum di Program Studi [prodi] Universitas Proklamasi 45, mahasiswa diwajibkan melaksanakan Kerja Praktik/Magang. Sehubungan dengan hal tersebut, kami memohon agar mahasiswa berikut:\n\nNama: [nama_mahasiswa]\nNIM: [nim]\n\nDapat diterima untuk melaksanakan magang di perusahaan yang Bapak/Ibu pimpin, mulai tanggal [tgl_mulai] sampai dengan [tgl_selesai].\n\nAtas bantuan dan fasilitas yang diberikan, kami ucapkan terima kasih."
            ],
            [
                'kode_surat' => 'SK-KEGIATAN',
                'nama_surat' => 'Surat Izin Mengikuti Kegiatan (Lomba/Seminar)',
                'kategori' => 'Kemahasiswaan',
                'format_penomoran' => '{nomor_urut}/KMHS/{kode_unit}/{bulan_romawi}/{tahun}',
                'form_schema' => json_encode([
                    ['name' => 'nama_kegiatan', 'type' => 'text', 'label' => 'Nama Kegiatan/Lomba'],
                    ['name' => 'penyelenggara', 'type' => 'text', 'label' => 'Penyelenggara Kegiatan'],
                    ['name' => 'tanggal_kegiatan', 'type' => 'date', 'label' => 'Tanggal Pelaksanaan']
                ]),
                'isi_template' => "Nomor: [nomor_surat]\n\nYang bertanda tangan di bawah ini menerangkan bahwa mahasiswa:\n\nNama: [nama_mahasiswa]\nNIM: [nim]\nProgram Studi: [prodi]\n\nAdalah benar utusan dari Universitas Proklamasi 45 dan diberikan izin untuk mengikuti kegiatan [nama_kegiatan] yang diselenggarakan oleh [penyelenggara] pada tanggal [tanggal_kegiatan].\n\nDemikian surat tugas/izin ini dibuat untuk dilaksanakan dengan penuh tanggung jawab."
            ],
            [
                'kode_surat' => 'SK-BAIK',
                'nama_surat' => 'Surat Keterangan Berkelakuan Baik',
                'kategori' => 'Kemahasiswaan',
                'format_penomoran' => '{nomor_urut}/SKBB/{kode_unit}/{bulan_romawi}/{tahun}',
                'form_schema' => json_encode([
                    ['name' => 'keperluan', 'type' => 'text', 'label' => 'Keperluan (Contoh: Pendaftaran Beasiswa, Melamar Pekerjaan)']
                ]),
                'isi_template' => "Nomor: [nomor_surat]\n\nPimpinan Fakultas [fakultas] Universitas Proklamasi 45, dengan ini menerangkan bahwa:\n\nNama: [nama_mahasiswa]\nNIM: [nim]\nProgram Studi: [prodi]\n\nBerdasarkan catatan akademik dan kemahasiswaan, mahasiswa tersebut di atas selama mengikuti perkuliahan BERKELAKUAN BAIK, tidak pernah melanggar tata tertib kampus, dan tidak sedang menerima sanksi akademik maupun administratif.\n\nSurat keterangan ini diberikan untuk keperluan: [keperluan].\nHarap yang berkepentingan maklum."
            ]
        ];

        foreach ($surats as $surat) {
            // Gunakan updateOrInsert agar jika kode_surat sudah ada, dia hanya meng-update isinya (mencegah duplicate entry)
            DB::table('jenis_surat')->updateOrInsert(
                ['kode_surat' => $surat['kode_surat']],
                array_merge($surat, [
                    'counter_nomor_urut' => 0,
                    'counter_tahun' => date('Y'),
                    'created_at' => $now,
                    'updated_at' => $now
                ])
            );
            
            // Mengambil ID jenis surat yang baru saja diinsert/update
            $jenisSuratId = DB::table('jenis_surat')->where('kode_surat', $surat['kode_surat'])->value('id');

            // Set Alur Approval Default untuk masing-masing surat (Biar bisa langsung dites)
            // Urutan 1: Kaprodi (jabatan_id: 1, scope: PRODI)
            DB::table('alur_approval')->updateOrInsert(
                ['jenis_surat_id' => $jenisSuratId, 'urutan' => 1],
                ['master_jabatan_id' => 1, 'scope' => 'PRODI', 'created_at' => $now, 'updated_at' => $now]
            );

            // Khusus kategori Akademik/Penelitian, tambahkan Approval Dekan di urutan 2
            if (in_array($surat['kategori'], ['Akademik', 'Penelitian'])) {
                DB::table('alur_approval')->updateOrInsert(
                    ['jenis_surat_id' => $jenisSuratId, 'urutan' => 2],
                    ['master_jabatan_id' => 2, 'scope' => 'FAKULTAS', 'created_at' => $now, 'updated_at' => $now]
                );
            }
        }

        $this->command->info('5 Template Surat dan Alur Approval berhasil ditambahkan!');
    }
}