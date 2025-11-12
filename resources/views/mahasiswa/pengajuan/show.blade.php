@extends('layouts.app')

@section('title', 'Detail Pengajuan')
@section('page-title', 'Detail Pengajuan: #' . $pengajuan->nomor_surat ?? $pengajuan->id)

@section('content')
<div class="row">
    <!-- Kolom Kiri: Detail & Hasil -->
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Pengajuan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Jenis Surat</div>
                    <div class="col-sm-8 fw-bold">{{ $pengajuan->jenisSurat->nama_surat }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Tanggal Diajukan</div>
                    <div class="col-sm-8">{{ $pengajuan->tanggal_pengajuan->format('d M Y, H:i') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Status</div>
                    <div class="col-sm-8">@include('partials.status_badge', ['status' => $pengajuan->status_pengajuan])</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Metode Pengambilan</div>
                    <div class="col-sm-8">{{ Str::title($pengajuan->metode_pengambilan) }}</div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Keperluan</div>
                    <div class="col-sm-8">{{ $pengajuan->keperluan }}</div>
                </div>

                <!-- Tampilkan Data Dinamis (jika ada) -->
                @if($pengajuan->data_pendukung)
                    @foreach($pengajuan->data_pendukung as $key => $value)
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">{{ Str::title(str_replace('_', ' ', $key)) }}</div>
                        <div class="col-sm-8">{{ $value }}</div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Bagian Hasil (Tombol Download / Catatan Revisi) -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Hasil</h5>
            </div>
            <div class="card-body">
                @if($pengajuan->status_pengajuan == 'selesai')
                    <div class="alert alert-success">
                        <h4 class="alert-heading">Surat Selesai!</h4>
                        <p>Surat Anda telah disetujui dan siap diunduh.</p>
                        
                       <a href="{{ route('preview.surat', basename($pengajuan->file_hasil_pdf)) }}" 
   target="_blank" 
   class="btn btn-secondary">
   <i class="bi bi-eye me-2"></i> Preview PDF
</a>

<a href="{{ route('download.surat', basename($pengajuan->file_hasil_pdf)) }}" 
   class="btn btn-primary">
   <i class="bi bi-download me-2"></i> Download PDF
</a>


                    </div>
                @elseif($pengajuan->status_pengajuan == 'siap_diambil')
                    <div class="alert alert-info">
                        <h4 class="alert-heading">Siap Diambil!</h4>
                        <p>Surat Anda (versi cetak) telah disetujui. Silakan ambil di Ruang Staff Jurusan Anda.</p>
                        <p class="mb-0">Nomor Surat: <strong>{{ $pengajuan->nomor_surat }}</strong></p>
                    </div>
                 @elseif($pengajuan->status_pengajuan == 'sudah_diambil')
                    <div class="alert alert-secondary">
                        <h4 class="alert-heading">Sudah Diambil</h4>
                        <p class="mb-0">Surat ini telah Anda ambil pada {{ $pengajuan->tanggal_diambil->format('d M Y') }}.</p>
                    </div>
                @elseif($pengajuan->status_pengajuan == 'perlu_revisi' || $pengajuan->status_pengajuan == 'ditolak')
                    <div class="alert alert-danger">
                        <h4 class="alert-heading">Pengajuan Ditolak / Perlu Revisi</h4>
                        <p><strong>Catatan dari Petugas:</strong></p>
                        <p class="mb-0 fst-italic">"{{ $pengajuan->catatan_revisi }}"</p>
                        <hr>
                        <p class="mb-0">Silakan perbaiki data Anda dan ajukan ulang.</p>
                    </div>
                @else
                    <p class="text-muted">Surat Anda sedang dalam proses. Silakan cek kembali nanti.</p>
                @endif
            </div>
        </div>

    </div>

    <!-- Kolom Kanan: Tracking -->
    <div class="col-lg-5">
        <div class="card shadow-sm"> <!-- Perbaikan: typo 'class_card' menjadi 'card' -->
            <div class="card-header bg-white">
                <h5 class="mb-0">Lacak Status</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">1. Pengajuan Dibuat</div>
                            <small class="text-muted">{{ $pengajuan->tanggal_pengajuan->format('d M Y, H:i') }}</small>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">2. Validasi Staff Jurusan</div>
                            @if($pengajuan->admin_validator_id)
                                <small class="text-muted">Divalidasi oleh {{ $pengajuan->adminValidator->nama_lengkap }}</small>
                            @else
                                <small class="text-muted">Menunggu validasi...</small>
                            @endif
                        </div>
                        @if($pengajuan->admin_validator_id)
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        @else
                            <i class="bi bi-hourglass-split text-warning fs-4"></i>
                        @endif
                    </li>
                    
                    <!-- Loop Approval Pejabat -->
                    @foreach($pengajuan->approvalPejabats->sortBy('urutan_approval') as $approval)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ 2 + $approval->urutan_approval }}. Approval {{ $approval->pejabat->masterJabatan->nama_jabatan }}</div>
                            <small class="text-muted">{{ $approval->pejabat->nama_lengkap }}</small>
                        </div>
                        
                        @if($approval->status_approval == 'disetujui')
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        @elseif($approval->status_approval == 'ditolak')
                            <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                        @else
                            <i class="bi bi-hourglass-split text-warning fs-4"></i>
                        @endif
                    </li>
                    @endforeach

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">Selesai</div>
                        </div>
                        @if($pengajuan->status_pengajuan == 'selesai' || $pengajuan->status_pengajuan == 'siap_diambil' || $pengajuan->status_pengajuan == 'sudah_diambil')
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        @else
                            <i class="bi bi-hourglass-split text-muted fs-4"></i>
                        @endif
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
