@extends('layouts.app')

@section('title', 'Detail Pengajuan Surat')

@push('styles')
<style>
    .card { border: none; border-radius: 15px; }
    .card-header { border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; }
    
    /* Timeline Styling */
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
    .timeline-item.active .timeline-dot { border-color: #10b981; background: #10b981; }
    .timeline-item.pending .timeline-dot { border-color: #f59e0b; background: #fff; }
    .timeline-item.rejected .timeline-dot { border-color: #ef4444; background: #ef4444; }

    /* Label Styling */
    .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: 700; }
    .info-value { color: #1e293b; font-weight: 600; }
    
    .btn-action { border-radius: 10px; font-weight: 600; padding: 0.6rem 1.25rem; transition: 0.2s; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex align-items-center">
                <i class="bi bi-file-earmark-text text-primary fs-4 me-3"></i>
                <h5 class="mb-0 fw-bold">Rincian Pengajuan</h5>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <label class="info-label d-block mb-1">Nama Mahasiswa</label>
                        <span class="info-value">{{ $pengajuan->mahasiswa->nama_lengkap }}</span>
                        <div class="text-muted small">NIM: {{ $pengajuan->mahasiswa->nim }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="info-label d-block mb-1">Program Studi</label>
                        <span class="info-value text-secondary">{{ $pengajuan->mahasiswa->programStudi->nama_prodi ?? '-' }}</span>
                    </div>
                    
                    <div class="col-12"><hr class="my-0 opacity-50"></div>

                    <div class="col-sm-6">
                        <label class="info-label d-block mb-1">Jenis Surat</label>
                        <span class="badge bg-light text-primary border px-3 py-2 fw-bold">
                            {{ $pengajuan->jenisSurat->nama_surat }}
                        </span>
                    </div>
                    <div class="col-sm-6">
                        <label class="info-label d-block mb-1">Tanggal Pengajuan</label>
                        <span class="info-value">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</span>
                        <div class="small text-muted">Pukul {{ $pengajuan->tanggal_pengajuan->format('H:i') }} WIB</div>
                    </div>

                    <div class="col-12">
                        <label class="info-label d-block mb-1">Keperluan / Perihal</label>
                        <div class="p-3 bg-light rounded-3 text-secondary small italic">
                            "{{ $pengajuan->keperluan }}"
                        </div>
                    </div>

                    @if($pengajuan->data_pendukung)
    @foreach($pengajuan->data_pendukung as $key => $value)
    <div class="col-md-6">
        <label class="info-label">{{ Str::title(str_replace('_', ' ', $key)) }}</label>
        @if(is_string($value) && Str::contains($value, 'dokumen_pengajuan'))
            <div class="mt-1 d-flex gap-2">
                <a href="{{ asset('storage/'.$value) }}" target="_blank" 
                   class="btn btn-sm btn-outline-primary py-1 px-2" 
                   style="font-size: 0.75rem; border-radius: 8px;">
                    <i class="bi bi-eye me-1"></i> Preview
                </a>
                <a href="{{ asset('storage/'.$value) }}" download 
                   class="btn btn-sm btn-outline-secondary py-1 px-2" 
                   style="font-size: 0.75rem; border-radius: 8px;">
                    <i class="bi bi-download me-1"></i> Unduh
                </a>
            </div>
        @elseif(is_array($value))
            <span class="info-value">{{ implode(', ', $value) }}</span>
        @else
            <span class="info-value">{{ $value }}</span>
        @endif
    </div>
    @endforeach
@endif
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-4">
                @if(in_array($pengajuan->status_pengajuan, ['selesai','siap_diambil','sudah_diambil']))
                    <div class="d-flex align-items-center bg-success bg-opacity-10 p-4 rounded-4 border border-success border-opacity-20">
                        <div class="me-4 d-none d-md-block">
                            <i class="bi bi-file-earmark-check-fill text-success" style="font-size: 3rem;"></i>
                        </div>
                        <div>
                            <h5 class="text-success fw-bold">Surat Telah Terbit!</h5>
                            <p class="text-secondary small mb-3">Dokumen digital Anda sudah tersedia dan dapat diunduh sekarang.</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('preview.surat', basename($pengajuan->file_hasil_pdf)) }}" target="_blank" class="btn btn-action btn-outline-success">
                                    <i class="bi bi-eye me-2"></i> Preview
                                </a>
                                <a href="{{ route('download.surat', basename($pengajuan->file_hasil_pdf)) }}" class="btn btn-action btn-success">
                                    <i class="bi bi-download me-2"></i> Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif($pengajuan->status_pengajuan == 'perlu_revisi')
                    <div class="alert alert-danger border-0 shadow-sm p-4 rounded-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <h6 class="mb-0 fw-bold">Perlu Revisi / Ditolak</h6>
                        </div>
                        <p class="mb-0 small opacity-75">{{ $pengajuan->catatan_revisi }}</p>
                    </div>
                @else
                    <div class="text-center py-4 border rounded-4 border-dashed">
                        <div class="spinner-border text-warning spinner-border-sm mb-2" role="status"></div>
                        <p class="text-muted small mb-0">Surat saat ini masih dalam proses validasi atau approval.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Tracking Status</h5>
            </div>
            <div class="card-body">
                <div class="timeline-container mt-2">
                    
                    <div class="timeline-item active">
                        <div class="timeline-dot"></div>
                        <div class="fw-bold text-dark small">Pengajuan Dikirim</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ $pengajuan->tanggal_pengajuan->format('d M Y, H:i') }}</div>
                    </div>

                    @php $isStaffDone = $pengajuan->admin_validator_id; @endphp
                    <div class="timeline-item {{ $isStaffDone ? 'active' : 'pending' }}">
                        <div class="timeline-dot"></div>
                        <div class="fw-bold small {{ $isStaffDone ? 'text-dark' : 'text-muted' }}">Validasi Staff Jurusan</div>
                        <div class="text-muted" style="font-size: 0.7rem;">
                            {{ $isStaffDone ? 'Verifikasi dokumen selesai' : 'Menunggu antrian validasi' }}
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
                                        @if(auth()->user()->pejabat && $approval->pejabat_id == auth()->user()->pejabat->id)
                                            <span class="badge bg-primary-subtle text-primary ms-1" style="font-size: 0.6rem;">ANDA</span>
                                        @endif
                                    </div>
                                </div>
                                @if($status == 'disetujui')
                                    <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 0.6rem;">SETUJU</span>
                                @elseif($status == 'ditolak')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 0.6rem;">DITOLAK</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($pengajuan->status_pengajuan == 'selesai')
                    <div class="timeline-item active">
                        <div class="timeline-dot"></div>
                        <div class="fw-bold text-success small">Surat Selesai</div>
                        <div class="text-muted" style="font-size: 0.7rem;">Dokumen siap diunduh</div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection