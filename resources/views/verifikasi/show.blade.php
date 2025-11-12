@extends('layouts.app')

@section('content')
    <h1>Verifikasi Pengajuan Surat</h1>

    <h3>Data Pengajuan Surat</h3>
    <p><strong>Judul Surat:</strong> {{ $pengajuanSurat->jenisSurat->nama_surat }}</p>
    <p><strong>ID Pengajuan Surat:</strong> {{ $pengajuanSurat->nomor_surat }}</p>
    <p><strong>Tanggal Pengajuan:</strong> {{ $pengajuanSurat->tanggal_pengajuan->format('d-m-Y') }}</p>
    <p><strong>Status Pengajuan:</strong> {{ $pengajuanSurat->status_pengajuan }}</p>
    <!-- Tambahkan detail lainnya sesuai kebutuhan -->

    <h3>Data Approval Pejabat</h3>
    <p><strong>Nama Pejabat:</strong> {{ $pejabat->nama_lengkap }}</p>
    <p><strong>Jabatan:</strong> {{ $pejabat->masterJabatan->nama_jabatan }}</p>
    <p><strong>NIP:</strong> {{ $pejabat->nip_atau_nidn }}</p>
    <p><strong>Status Approval:</strong> {{ $approval->status_approval }}</p>
    <p><strong>Tanggal Approval:</strong> {{ $approval->tanggal_approval->format('d-m-Y H:i:s') }}</p>
    <p><strong>Catatan:</strong> {{ $approval->catatan_pejabat }}</p>
@endsection
