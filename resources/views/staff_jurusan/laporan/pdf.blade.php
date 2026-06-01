{{-- resources/views/staff_jurusan/laporan/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 8.5pt;
        color: #111;
        background: #fff;
    }

    /* ══ KOP SURAT ══ */
    .kop-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
    .kop-table td { border: none; padding: 0; vertical-align: middle; }
    .kop-logo { width: 58px; text-align: center; }
    .kop-logo img { width: 50px; }
    .kop-teks { padding-left: 12px; }
    .kop-univ  { font-size: 13pt; font-weight: bold; letter-spacing: .4px; color: #000; text-transform: uppercase; }
    .kop-alamat { font-size: 7.5pt; color: #444; margin-top: 2px; line-height: 1.5; }
    /* Garis KOP — dua garis seperti dokumen resmi */
    .kop-garis-tebal { width: 100%; height: 3px; background: #000; margin-top: 6px; }
    .kop-garis-tipis { width: 100%; height: 1px; background: #000; margin-top: 2px; margin-bottom: 14px; }

    /* ══ JUDUL ══ */
    .judul-wrap { text-align: center; margin-bottom: 10px; }
    .judul-utama {
        font-size: 10.5pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-decoration: underline;
        color: #000;
    }
    .judul-sub { font-size: 8pt; color: #333; margin-top: 3px; }

    /* ══ TABEL INFO (keterangan dokumen) ══ */
    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .info-table td { font-size: 8pt; padding: 2px 4px; border: none; vertical-align: top; }
    .info-key   { width: 130px; color: #111; }
    .info-sep   { width: 10px; color: #111; }
    .info-val   { color: #111; }

    /* Garis pemisah tipis sebelum tabel data */
    .garis-section { width: 100%; height: 1px; background: #aaa; margin: 8px 0 10px; }

    /* ══ REKAPITULASI (inline text, bukan card) ══ */
    .rekap-wrap { margin-bottom: 10px; }
    .rekap-title { font-size: 8pt; font-weight: bold; margin-bottom: 4px; text-transform: uppercase; letter-spacing: .5px; }
    .rekap-table { border-collapse: collapse; }
    .rekap-table td { font-size: 8pt; padding: 1px 14px 1px 0; border: none; }
    .rekap-num { font-weight: bold; }

    /* ══ TABEL DATA UTAMA ══ */
    .main-table { width: 100%; border-collapse: collapse; font-size: 7.5pt; }
    .main-table thead tr { background: #e8e8e8; }
    .main-table thead th {
        padding: 5px 5px;
        text-align: left;
        font-weight: bold;
        font-size: 7pt;
        border: 1px solid #aaa;
        white-space: nowrap;
    }
    .main-table tbody tr:nth-child(even) { background: #f7f7f7; }
    .main-table tbody tr:nth-child(odd)  { background: #ffffff; }
    .main-table tbody td {
        padding: 4px 5px;
        border: 1px solid #ccc;
        vertical-align: top;
        line-height: 1.4;
    }
    .td-no   { text-align: center; color: #555; width: 20px; }
    .td-nim  { font-size: 7pt; white-space: nowrap; }
    .td-nomor { font-size: 6.5pt; white-space: nowrap; }

    /* Status — teks biasa, huruf kapital di awal */
    .status-text { font-weight: bold; font-size: 7pt; }
    .s-selesai  { color: #166534; }
    .s-pending  { color: #92400e; }
    .s-ditolak  { color: #991b1b; }
    .s-proses   { color: #1e40af; }
    .s-gagal    { color: #831843; }

    /* ══ FOOTER (fixed di setiap halaman) ══ */
    .footer {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        border-top: 1px solid #999;
        padding: 4px 0 2px;
        font-size: 7pt;
        color: #666;
    }
    .footer-tbl { width: 100%; border-collapse: collapse; }
    .footer-tbl td { border: none; padding: 0; vertical-align: middle; }
    .footer-kanan { text-align: right; }

    /* ══ TANDA TANGAN ══ */
    .ttd-table { width: 100%; border-collapse: collapse; margin-top: 24px; }
    .ttd-table td { border: none; padding: 0; font-size: 8pt; vertical-align: top; }
    .ttd-kiri  { width: 50%; }
    .ttd-kanan { width: 50%; text-align: center; }
    .ttd-ruang { height: 56px; }
    .ttd-garis { border-top: 1px solid #111; padding-top: 3px; font-weight: bold; display: inline-block; min-width: 160px; }

    tr { page-break-inside: avoid; }
</style>
</head>
<body>

{{-- ── Footer tiap halaman ── --}}
<div class="footer">
    <table class="footer-tbl">
        <tr>
            <td>SISURAT UP45 &mdash; Dicetak otomatis oleh sistem, bukan merupakan dokumen yang ditandatangani secara manual kecuali ada tanda tangan basah.</td>
            <td class="footer-kanan">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</td>
        </tr>
    </table>
</div>

{{-- ── KOP ── --}}
<table class="kop-table">
    <tr>
        <td class="kop-logo">
            <img src="{{ public_path('images/logoup45.png') }}" alt="Logo UP45">
        </td>
        <td class="kop-teks">
            <div class="kop-univ">Universitas Proklamasi 45 Yogyakarta</div>
            <div class="kop-alamat">
                Jl. Proklamasi No. 1, Babarsari, Yogyakarta 55281 &nbsp;|&nbsp; Telp. (0274) 485508<br>
                Program Studi {{ $namaProdi }}
            </div>
        </td>
    </tr>
</table>
<div class="kop-garis-tebal"></div>
<div class="kop-garis-tipis"></div>

{{-- ── JUDUL ── --}}
<div class="judul-wrap">
    <div class="judul-utama">Laporan Rekapitulasi Pengajuan Surat</div>
    <div class="judul-sub">Periode {{ $namaBulan }}</div>
</div>

{{-- ── INFO DOKUMEN ── --}}
<table class="info-table">
    <tr>
        <td class="info-key">Program Studi</td>
        <td class="info-sep">:</td>
        <td class="info-val">{{ $namaProdi }}</td>
        <td style="width:30px"></td>
        <td class="info-key">Jenis Surat</td>
        <td class="info-sep">:</td>
        <td class="info-val">{{ $jenisSurat }}</td>
    </tr>
    <tr>
        <td class="info-key">Periode Laporan</td>
        <td class="info-sep">:</td>
        <td class="info-val">{{ $namaBulan }}</td>
        <td></td>
        <td class="info-key">Filter Status</td>
        <td class="info-sep">:</td>
        <td class="info-val">{{ $statusLabel }}</td>
    </tr>
    <tr>
        <td class="info-key">Dibuat Oleh</td>
        <td class="info-sep">:</td>
        <td class="info-val">{{ $namaStaff }}</td>
        <td></td>
        <td class="info-key">Tanggal Cetak</td>
        <td class="info-sep">:</td>
        <td class="info-val">{{ now()->translatedFormat('d F Y') }}</td>
    </tr>
</table>

<div class="garis-section"></div>

{{-- ── REKAPITULASI (inline, bukan card) ── --}}
<div class="rekap-wrap">
    <div class="rekap-title">Rekapitulasi</div>
    <table class="rekap-table">
        <tr>
            <td>Total Pengajuan</td>
            <td>:</td>
            <td class="rekap-num">{{ $summary['total'] }} pengajuan</td>
            <td style="width:30px"></td>
            <td>Selesai</td>
            <td>:</td>
            <td class="rekap-num">{{ $summary['selesai'] }} pengajuan</td>
            <td style="width:30px"></td>
            <td>Pending</td>
            <td>:</td>
            <td class="rekap-num">{{ $summary['pending'] }} pengajuan</td>
            <td style="width:30px"></td>
            <td>Ditolak</td>
            <td>:</td>
            <td class="rekap-num">{{ $summary['ditolak'] }} pengajuan</td>
        </tr>
    </table>
</div>

{{-- ── TABEL DATA ── --}}
@if($pengajuans->isEmpty())
    <p style="text-align:center;color:#666;padding:20px;font-size:8pt;">
        Tidak ada data pengajuan untuk periode dan filter yang dipilih.
    </p>
@else
<table class="main-table">
    <thead>
        <tr>
            <th class="td-no">No</th>
            <th style="width:58px">Tanggal</th>
            <th style="width:65px">NIM</th>
            <th style="width:115px">Nama Mahasiswa</th>
            <th style="width:95px">Jenis Surat</th>
            <th>Keperluan</th>
            @foreach($schemaKeys as $key)
                <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
            @endforeach
            <th style="width:52px">Metode</th>
            <th style="width:60px">Status</th>
            <th style="width:105px">Nomor Surat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengajuans as $i => $p)
        @php
            $sCls = match($p->status_pengajuan) {
                'selesai', 'sudah_diambil' => 's-selesai',
                'pending'                  => 's-pending',
                'ditolak'                  => 's-ditolak',
                'gagal_generate'           => 's-gagal',
                default                    => 's-proses',
            };
            $dataPendukung = is_array($p->data_pendukung) ? $p->data_pendukung : [];
        @endphp
        <tr>
            <td class="td-no">{{ $i + 1 }}</td>
            <td>
                {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d/m/Y') }}<br>
                <span style="color:#777;font-size:6.5pt">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('H:i') }}</span>
            </td>
            <td class="td-nim">{{ $p->mahasiswa->nim ?? '-' }}</td>
            <td>{{ $p->mahasiswa->nama_lengkap ?? '-' }}</td>
            <td>
                {{ $p->jenisSurat->nama_surat ?? '-' }}<br>
                <span style="color:#777;font-size:6.5pt">{{ $p->jenisSurat->kode_surat ?? '' }}</span>
            </td>
            <td>{{ Str::limit($p->keperluan, 80) }}</td>
            @foreach($schemaKeys as $key)
                @php
                    $val = $dataPendukung[$key] ?? '-';
                    if (is_string($val) && str_starts_with($val, 'dokumen_pengajuan/')) {
                        $val = '[File terlampir]';
                    }
                @endphp
                <td>{{ is_array($val) ? implode(', ', $val) : $val }}</td>
            @endforeach
            <td style="text-align:center">{{ ucfirst($p->metode_pengambilan) }}</td>
            <td>
                <span class="status-text {{ $sCls }}">
                    {{ ucwords(str_replace('_', ' ', $p->status_pengajuan)) }}
                </span>
            </td>
            <td class="td-nomor">{{ $p->nomor_surat ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:8px;font-size:7.5pt;color:#444">
    Menampilkan <strong>{{ $pengajuans->count() }}</strong> data pengajuan.
</p>
@endif

{{-- ── TANDA TANGAN ── --}}
<table class="ttd-table">
    <tr>
        <td class="ttd-kiri"></td>
        <td class="ttd-kanan">
            <div>Yogyakarta, {{ now()->translatedFormat('d F Y') }}</div>
            <div style="margin-top:1px;color:#444">Staff Jurusan {{ $namaProdi }}</div>
            <div class="ttd-ruang"></div>
            <div>
                <span class="ttd-garis">{{ $namaStaff }}</span>
            </div>
        </td>
    </tr>
</table>

</body>
</html>