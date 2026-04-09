@extends('layouts.app')

@section('title', 'Detail Surat')
@section('page-title', 'Detail Pengajuan')

@push('styles')
<style>
    /* Card Styling */
    .card { border: none; border-radius: 15px; }
    .card-header { border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; }
    
    /* Timeline Tracking Styling */
    .timeline-container { position: relative; padding-left: 30px; }
    .timeline-container::before {
        content: ''; position: absolute; left: 7px; top: 5px;
        width: 2px; height: 90%; background: #e2e8f0;
    }
    .timeline-item { position: relative; padding-bottom: 1.5rem; }
    .timeline-dot {
        position: absolute; left: -30px; top: 4px;
        width: 16px; height: 16px; border-radius: 50%;
        background: #fff; border: 3px solid #cbd5e1; z-index: 2;
    }
    /* Timeline States */
    .timeline-item.active .timeline-dot { border-color: #10b981; background: #10b981; }
    .timeline-item.pending .timeline-dot { border-color: #f59e0b; background: #fff; }
    .timeline-item.rejected .timeline-dot { border-color: #ef4444; background: #ef4444; }

    /* Info Display */
    .info-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: 700; }
    .info-value { color: #1e293b; font-weight: 600; display: block; }
    
    .btn-action { border-radius: 10px; font-weight: 600; padding: 0.6rem 1.25rem; transition: 0.2s; }
</style>
@endpush

@section('content')
<div class="row">

    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex align-items-center">
                <i class="bi bi-info-circle text-primary fs-5 me-2"></i>
                <h5 class="mb-0 fw-bold">Informasi Pengajuan</h5>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="info-label">Nama Mahasiswa</label>
                        <span class="info-value">{{ $pengajuan->mahasiswa->nama }}</span>
                        <small class="text-muted">NIM: {{ $pengajuan->mahasiswa->nim }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="info-label">Program Studi</label>
                        <span class="info-value text-secondary">{{ $pengajuan->mahasiswa->programStudi->nama_prodi ?? '-' }}</span>
                    </div>

                    <div class="col-12"><hr class="my-1 opacity-25"></div>

                    <div class="col-md-6">
                        <label class="info-label">Jenis Surat</label>
                        <div class="d-flex align-items-center mt-1">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                <i class="bi bi-file-earmark-text me-1"></i> {{ $pengajuan->jenisSurat->nama_surat }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="info-label">Tanggal Pengajuan</label>
                        <span class="info-value">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</span>
                        <small class="text-muted">Pukul {{ $pengajuan->tanggal_pengajuan->format('H:i') }} WIB</small>
                    </div>

                    <div class="col-12">
                        <label class="info-label">Keperluan</label>
                        <div class="p-3 bg-light rounded-3 text-secondary border-start border-primary border-3 small">
                            {{ $pengajuan->keperluan }}
                        </div>
                    </div>

                    @if($pengajuan->data_pendukung)
                        @foreach($pengajuan->data_pendukung as $key => $value)
                        <div class="col-md-6">
                            <label class="info-label">{{ Str::title(str_replace('_', ' ', $key)) }}</label>
                            <span class="info-value">{{ $value }}</span>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Aksi / Hasil Surat</h5>
            </div>

            <div class="card-body">
                @if(in_array($pengajuan->status_pengajuan, ['selesai', 'siap_diambil', 'sudah_diambil']))
                    <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4 p-4 text-center">
                        <i class="bi bi-patch-check-fill text-success display-6 mb-2"></i>
                        <h5 class="fw-bold text-success">Surat Selesai Diproses!</h5>
                        <p class="text-muted small">Surat Anda sudah divalidasi dan ditandatangani. Silakan akses file di bawah ini:</p>
                        <div class="d-flex gap-2 justify-content-center mt-3">
                            <a href="{{ route('preview.surat', basename($pengajuan->file_hasil_pdf)) }}" target="_blank" class="btn btn-action btn-outline-success">
                                <i class="bi bi-eye me-2"></i> Preview
                            </a>
                            <a href="{{ route('download.surat', basename($pengajuan->file_hasil_pdf)) }}" class="btn btn-action btn-success shadow-sm">
                                <i class="bi bi-download me-2"></i> Download PDF
                            </a>
                        </div>
                    </div>
                @elseif($pengajuan->status_pengajuan == 'perlu_revisi')
                    <div class="alert alert-danger border-0 rounded-4 p-4 shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-exclamation-octagon-fill fs-4 me-2"></i>
                            <h6 class="mb-0 fw-bold">Pengajuan Perlu Revisi</h6>
                        </div>
                        <p class="mb-0 small opacity-75">{{ $pengajuan->catatan_revisi }}</p>
                    </div>
                @else
                    <div class="py-4 text-center border rounded-4 border-dashed bg-light bg-opacity-50">
                        <div class="spinner-grow text-warning spinner-grow-sm mb-2" role="status"></div>
                        <p class="text-muted small mb-0 fw-medium">Sedang dalam proses verifikasi & approval pejabat.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Tracking Status</h5>
                @include('partials.status_badge', ['status' => $pengajuan->status_pengajuan])
            </div>

            <div class="card-body p-4">
                <div class="timeline-container">
                    
                    <div class="timeline-item active">
                        <div class="timeline-dot"></div>
                        <div class="fw-bold text-dark small">Pengajuan Dibuat</div>
                        <div class="text-muted" style="font-size: 0.7rem;">
                            {{ $pengajuan->tanggal_pengajuan->format('d M Y, H:i') }} WIB
                        </div>
                    </div>

                    @php $isStaffDone = $pengajuan->admin_validator_id; @endphp
                    <div class="timeline-item {{ $isStaffDone ? 'active' : 'pending' }}">
                        <div class="timeline-dot"></div>
                        <div class="fw-bold small {{ $isStaffDone ? 'text-dark' : 'text-muted' }}">Validasi Staff Jurusan</div>
                        <div class="text-muted" style="font-size: 0.7rem;">
                            @if($isStaffDone)
                                <span class="text-success fw-medium"><i class="bi bi-check2"></i> Dokumen telah divalidasi</span>
                            @else
                                <span class="text-warning fw-medium"><i class="bi bi-clock"></i> Menunggu antrian validasi</span>
                            @endif
                        </div>
                    </div>

                    @foreach($pengajuan->approvalPejabats->sortBy('urutan_approval') as $approval)
                        @php 
                            $status = $approval->status_approval;
                            $class = $status == 'disetujui' ? 'active' : ($status == 'ditolak' ? 'rejected' : 'pending');
                        @endphp
                        <div class="timeline-item {{ $class }}">
                            <div class="timeline-dot"></div>
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold small {{ $class != 'pending' ? 'text-dark' : 'text-muted' }}">
                                        {{ $approval->pejabat->masterJabatan->nama_jabatan }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.7rem;">
                                        {{ $approval->pejabat->nama_lengkap }}
                                    </div>
                                </div>
                                @if($status == 'disetujui')
                                    <span class="badge bg-success-subtle text-success rounded-pill border-0" style="font-size: 0.6rem;">SETUJU</span>
                                @elseif($status == 'ditolak')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill border-0" style="font-size: 0.6rem;">DITOLAK</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if(in_array($pengajuan->status_pengajuan, ['selesai', 'siap_diambil', 'sudah_diambil']))
                    <div class="timeline-item active">
                        <div class="timeline-dot"></div>
                        <div class="fw-bold text-success small">Surat Selesai</div>
                        <div class="text-muted" style="font-size: 0.7rem;">Silakan download pada panel sebelah kiri.</div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@endsection