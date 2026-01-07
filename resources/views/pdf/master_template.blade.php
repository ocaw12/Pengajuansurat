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
        
        /* Styling Kop Surat */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            position: relative; /* Penting agar absolute positioning logo bekerja relatif terhadap kop */
        }
        .kop-surat img {
            width: 80px;
            position: absolute;
            left: 0; /* Menyesuaikan posisi kiri (bisa diganti 2cm jika ingin menjorok) */
            top: 5px;  /* Menyesuaikan posisi atas agar sejajar dengan teks */
        }
        /* Jika ingin logo lebih menjorok ke dalam seperti kode asli, gunakan ini:
        .kop-surat img {
            width: 80px;
            position: absolute;
            left: 1cm; 
            top: 0.5cm;
        } 
        */

        .kop-surat h1, .kop-surat h2, .kop-surat p {
            margin: 0;
            padding: 0;
        }
        .kop-surat h2 {
            font-size: 14pt;
            font-weight: normal;
        }
        .kop-surat h1 {
            font-size: 16pt;
            font-weight: bold;
        }
        
        /* Styling Judul & Nomor */
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
        
        /* Styling Isi Surat */
        .isi-surat {
            text-align: justify;
        }
        
        /* Styling Tanda Tangan */
        .area-ttd {
            width: 40%;
            margin-left: 60%; /* Posisi di kanan */
            margin-top: 50px;
            text-align: left;
            page-break-inside: avoid; /* Mencegah TTD terpotong halaman */
        }
        .area-ttd .ttd-gambar {
            height: 80px;
            display: block;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    
    <!-- 1. KOP SURAT -->
    <div class="kop-surat">
        <!-- Menggunakan public_path untuk support DOMPDF -->
        <!-- Pastikan file ada di folder: public/images/logoup45.png -->
        <img src="{{ public_path('images/logoup45.png') }}" alt="Logo UP45">
        
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

    <!-- 3. ISI SURAT -->
    <div class="isi-surat">
        {!! $isi_surat_final !!}
    </div>

    <!-- 4. AREA TANDA TANGAN (LOOPING) -->
    @if(isset($pejabat_approvals) && count($pejabat_approvals) > 0)
        @foreach($pejabat_approvals->sortBy('urutan_approval') as $approval)
        <div class="area-ttd">
            <p>Yogyakarta, {{ $tanggal_terbit ?? date('d F Y') }}</p>
            <p>{{ $approval->pejabat->masterJabatan->nama_jabatan ?? 'Jabatan' }},</p>
            
            <!-- Menampilkan QR Code/TTD jika file ada -->
            @if(!empty($approval->path_qr))
                <img src="{{ storage_path('app/public/' . $approval->path_qr) }}" class="ttd-gambar" alt="QR TTD">
            @else
                <br><br><br> <!-- Space kosong jika belum ada TTD -->
            @endif

            <p style="font-weight: bold; margin-top: 5px; text-decoration: underline;">
                {{ $approval->pejabat->nama_lengkap }}
            </p>
            <p style="margin-top: -10px;">
                NIP/NIDN: {{ $approval->pejabat->nip_atau_nidn }}
            </p>
        </div>
        @endforeach
    @endif

</body>
</html>