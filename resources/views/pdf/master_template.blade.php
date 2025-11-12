<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Resmi - {{ $jenis_surat->nama_surat }}</title>
    <style>
        /* Gaya font standar untuk surat resmi */
        @page {
            margin: 2.5cm 2cm; /* Margin standar kertas A4 */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }
        .kop-surat img {
            width: 80px;
            position: absolute;
            left: 2cm;
            top: 1.5cm;
        }
        .kop-surat h1, .kop-surat h2, .kop-surat p {
            margin: 0;
            padding: 0;
        }
        .judul-surat {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 20px;
            margin-bottom: 5px;
            font-size: 14pt;
        }
        .nomor-surat {
            text-align: center;
            margin-bottom: 25px;
        }
        .isi-surat {
            text-align: justify;
        }
        .area-ttd {
            width: 40%;
            margin-left: 60%;
            margin-top: 50px;
            text-align: left;
        }
        .area-ttd .ttd-gambar {
            height: 80px; /* Tinggi untuk TTD */
        }
        .footer-page {
            /* Jika perlu nomor halaman */
        }
    </style>
</head>
<body>
    
    <!-- 1. KOP SURAT (Contoh Sederhana) -->
    <div class="kop-surat">
        <!-- <img src="{{ public_path('images/logo_up45.png') }}" alt="Logo"> -->
        <h2>UNIVERSITAS PROKLAMASI 45</h2>
        <h1>FAKULTAS SAINS & TEKNOLOGI</h1>
        <p style="font-size: 10pt;">Alamat: Jl. Proklamasi No.1, Babarsari, Yogyakarta</p>
    </div>

    <!-- 2. JUDUL & NOMOR SURAT -->
    <div class="judul-surat">
        {{ $jenis_surat->nama_surat }}
    </div>
    <div class="nomor-surat">
        Nomor: {{ $nomor_surat }}
    </div>

    <!-- 3. ISI SURAT (DISUNTIK DARI JOB) -->
    <div class="isi-surat">
        {!! $isi_surat_final !!} <!-- Ini adalah "lubang" untuk naskah -->
    </div>

    <!-- 4. AREA TANDA TANGAN (LOOPING) -->
    <!-- Loop ini akan menampilkan TTD berdasarkan urutan approval -->
    <!-- Loop untuk menampilkan tanda tangan pejabat dan QR Code -->
@foreach($pejabat_approvals->sortBy('urutan_approval') as $approval)
<div class="area-ttd">
    <p>Yogyakarta, {{ $tanggal_terbit }}</p>
    <p>{{ $approval->pejabat->masterJabatan->nama_jabatan }},</p>
    
    <!-- Menampilkan QR Code untuk tiap pejabat -->
    <img src="{{ storage_path('app/public/' . $approval->path_qr) }}" class="ttd-gambar" width="100">

    <p style="font-weight: bold; margin-top: 5px; text-decoration: underline;">{{ $approval->pejabat->nama_lengkap }}</p>
    <p style="margin-top: -10px;">NIP/NIDN: {{ $approval->pejabat->nip_atau_nidn }}</p>
</div>
@endforeach


</body>
</html>
