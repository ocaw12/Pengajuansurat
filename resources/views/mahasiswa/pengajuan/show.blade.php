@extends('layouts.app')

@section('title', 'Detail Pengajuan')
@section('page-title', 'Detail #' . ($pengajuan->nomor_surat ?? $pengajuan->id))

@push('styles')
<style>
    /* Desktop styles (unchanged) */
    .card { border: none; border-radius: 15px; }
    .card-header { border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; }
    .timeline-container { position: relative; padding-left: 30px; }
    .timeline-container::before { content:''; position:absolute; left:7px; top:5px; width:2px; height:92%; background:#e2e8f0; }
    .timeline-item { position:relative; padding-bottom:1.5rem; }
    .timeline-dot { position:absolute; left:-30px; top:4px; width:16px; height:16px; border-radius:50%; background:#fff; border:3px solid #cbd5e1; z-index:2; }
    .timeline-item.active .timeline-dot  { border-color:#10b981; background:#10b981; }
    .timeline-item.pending .timeline-dot { border-color:#f59e0b; background:#fff; }
    .timeline-item.rejected .timeline-dot{ border-color:#ef4444; background:#ef4444; }
    .info-label { font-size:0.7rem; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; font-weight:700; }
    .info-value { color:#1e293b; font-weight:600; display:block; }
    .btn-action { border-radius:10px; font-weight:600; padding:0.6rem 1.25rem; transition:0.2s; }

    /* ══ MOBILE SHOW ══ */
    @media (max-width: 767.98px) {
        .desktop-show { display: none !important; }
        .mobile-show  { display: block !important; }

        /* Sticky status strip at top */
        .m-status-strip {
            position: sticky;
            top: 58px;
            z-index: 90;
            padding: 0.7rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
        }
        .m-status-strip-title { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Section cards */
        .m-card {
            background: #fff;
            margin-bottom: 8px;
        }
        .m-card-header {
            padding: 1rem 1.25rem 0.75rem;
            border-bottom: 1px solid #f8fafc;
            display: flex; align-items: center; gap: 0.6rem;
        }
        .m-card-header i { font-size: 1rem; }
        .m-card-header span { font-size: 0.9rem; font-weight: 700; color: #1e293b; }
        .m-card-body { padding: 0.9rem 1.25rem; }

        /* Info rows */
        .m-info-row {
            display: flex; align-items: flex-start;
            padding: 0.65rem 0;
            border-bottom: 1px solid #f8fafc;
        }
        .m-info-row:last-child { border-bottom: none; }
        .m-info-label {
            width: 110px; flex-shrink: 0;
            font-size: 0.75rem; font-weight: 600;
            color: #94a3b8; text-transform: uppercase; letter-spacing: 0.3px;
            padding-top: 1px;
        }
        .m-info-value { font-size: 0.875rem; font-weight: 600; color: #1e293b; flex: 1; }

        /* Timeline mobile */
        .m-timeline { padding: 0.5rem 0; }
        .m-tl-item {
            display: flex; gap: 1rem;
            padding: 0.7rem 0;
            position: relative;
        }
        .m-tl-item:not(:last-child)::after {
            content:'';
            position: absolute;
            left: 10px; top: 32px;
            width: 1.5px; height: calc(100% - 10px);
            background: #e2e8f0;
        }
        .m-tl-dot {
            width: 21px; height: 21px; border-radius: 50%; flex-shrink: 0;
            border: 2.5px solid #cbd5e1; background: #fff;
            margin-top: 1px; position: relative; z-index: 1;
        }
        .m-tl-dot.active   { border-color: #10b981; background: #10b981; }
        .m-tl-dot.pending  { border-color: #f59e0b; background: #fff; }
        .m-tl-dot.rejected { border-color: #ef4444; background: #ef4444; }
        .m-tl-label  { font-size: 0.875rem; font-weight: 700; color: #1e293b; }
        .m-tl-sub    { font-size: 0.72rem; color: #94a3b8; margin-top: 1px; }

        /* Result section */
        .m-result-box {
            margin: 0;
            border-radius: 14px;
            padding: 1.1rem;
        }

        /* Action buttons full width on mobile */
        .btn-action { width: 100%; justify-content: center; margin-bottom: 8px; }
        .btn-action:last-child { margin-bottom: 0; }

        /* Back button */
        .m-back-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.6rem 1.25rem;
            font-size: 0.85rem; font-weight: 600;
            color: #64748b;
            text-decoration: none;
            background: #f8fafc;
            border-radius: 10px;
            margin: 0.75rem 1.25rem;
        }
    }

    @media (min-width: 768px) {
        .mobile-show { display: none !important; }
    }
</style>
@endpush

@section('content')

{{-- ══ DESKTOP ══ --}}
<div class="row desktop-show">
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex align-items-center">
                <i class="bi bi-file-earmark-text text-primary fs-5 me-2"></i>
                <h5 class="mb-0 fw-bold">Rincian Surat</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="info-label">Jenis Surat</label>
                        <span class="info-value text-primary">{{ $pengajuan->jenisSurat->nama_surat }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="info-label">Metode Pengambilan</label>
                        <span class="info-value"><i class="bi bi-mailbox me-1"></i> {{ Str::title($pengajuan->metode_pengambilan) }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="info-label">Tanggal Diajukan</label>
                        <span class="info-value text-dark">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</span>
                        <small class="text-muted">Pukul {{ $pengajuan->tanggal_pengajuan->format('H:i') }} WIB</small>
                    </div>
                    <div class="col-md-6">
                        <label class="info-label">Status Saat Ini</label>
                        <div class="mt-1">@include('partials.status_badge', ['status' => $pengajuan->status_pengajuan])</div>
                    </div>
                    <div class="col-12"><hr class="my-1 opacity-25"></div>
                    <div class="col-12">
                        <label class="info-label">Keperluan</label>
                        <div class="p-3 bg-light rounded-3 text-secondary border-start border-primary border-3 small italic">
                            "{{ $pengajuan->keperluan }}"
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
                <h5 class="mb-0 fw-bold">Hasil Pengajuan</h5>
            </div>
            <div class="card-body">
                @if($pengajuan->status_pengajuan == 'selesai')
                    <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4 p-4 text-center">
                        <i class="bi bi-cloud-check text-success display-6 mb-2"></i>
                        <h5 class="fw-bold text-success">Surat Digital Tersedia</h5>
                        <p class="text-muted small">Surat Anda telah ditandatangani secara elektronik dan siap digunakan.</p>
                        <div class="d-flex gap-2 justify-content-center mt-3">
                            <a href="{{ route('preview.surat', basename($pengajuan->file_hasil_pdf)) }}" target="_blank" class="btn btn-action btn-outline-success">
                                <i class="bi bi-eye me-2"></i> Preview
                            </a>
                            <a href="{{ route('download.surat', basename($pengajuan->file_hasil_pdf)) }}" class="btn btn-action btn-success shadow-sm">
                                <i class="bi bi-download me-2"></i> Download PDF
                            </a>
                        </div>
                    </div>
                @elseif($pengajuan->status_pengajuan == 'siap_diambil')
                    <div class="bg-info bg-opacity-10 border border-info border-opacity-25 rounded-4 p-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-info fs-1 me-3"></i>
                            <div>
                                <h5 class="fw-bold text-info mb-1">Siap Diambil!</h5>
                                <p class="text-secondary small mb-0">Silakan ambil versi cetak di <strong>Ruang Staff Jurusan</strong> dengan menyebutkan Nomor Surat: <strong>{{ $pengajuan->nomor_surat }}</strong></p>
                            </div>
                        </div>
                    </div>
                @elseif($pengajuan->status_pengajuan == 'sudah_diambil')
                    <div class="alert alert-secondary border-0 rounded-4 p-4">
                        <h6 class="fw-bold"><i class="bi bi-archive me-2"></i> Arsip: Sudah Diambil</h6>
                        <p class="small mb-0">Surat ini telah diserahkan kepada Anda pada tanggal {{ $pengajuan->tanggal_diambil->format('d M Y') }}.</p>
                    </div>
                @elseif($pengajuan->status_pengajuan == 'perlu_revisi' || $pengajuan->status_pengajuan == 'ditolak')
                    <div class="alert alert-danger border-0 rounded-4 p-4 shadow-sm">
                        <h6 class="fw-bold"><i class="bi bi-exclamation-triangle me-2"></i> Pengajuan Perlu Perbaikan</h6>
                        <div class="bg-white bg-opacity-50 p-3 rounded-3 mt-2 mb-3">
                            <small class="text-dark fw-bold d-block">Catatan Petugas:</small>
                            <span class="small italic">"{{ $pengajuan->catatan_revisi }}"</span>
                        </div>
                        <p class="small mb-0 opacity-75">Silakan perbaiki data sesuai instruksi di atas dan ajukan kembali.</p>
                    </div>
                @else
                    <div class="py-4 text-center border rounded-4 border-dashed bg-light bg-opacity-50">
                        <div class="spinner-grow text-warning spinner-grow-sm mb-2" role="status"></div>
                        <p class="text-muted small mb-0 fw-medium">Surat sedang dalam antrian proses verifikasi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Lacak Status</h5>
            </div>
            <div class="card-body p-4">
                <div class="timeline-container">
                    <div class="timeline-item active">
                        <div class="timeline-dot"></div>
                        <div class="fw-bold text-dark small">Pengajuan Dibuat</div>
                        <div class="text-muted" style="font-size:0.7rem;">{{ $pengajuan->tanggal_pengajuan->format('d M Y, H:i') }} WIB</div>
                    </div>
                    @php $isStaffDone = $pengajuan->admin_validator_id; @endphp
                    <div class="timeline-item {{ $isStaffDone ? 'active' : 'pending' }}">
                        <div class="timeline-dot"></div>
                        <div class="fw-bold small {{ $isStaffDone ? 'text-dark' : 'text-muted' }}">Validasi Staff Jurusan</div>
                        <div class="text-muted" style="font-size:0.7rem;">
                            @if($isStaffDone)
                                <span class="text-success fw-medium"><i class="bi bi-person-check me-1"></i>Telah divalidasi</span>
                            @else
                                <span class="text-warning fw-medium">Menunggu verifikasi dokumen</span>
                            @endif
                        </div>
                    </div>
                    @foreach($pengajuan->approvalPejabats->sortBy('urutan_approval') as $approval)
                        @php
                            $status = $approval->status_approval;
                            $class  = $status == 'disetujui' ? 'active' : ($status == 'ditolak' ? 'rejected' : 'pending');
                        @endphp
                        <div class="timeline-item {{ $class }}">
                            <div class="timeline-dot"></div>
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold small {{ $class != 'pending' ? 'text-dark' : 'text-muted' }}">
                                        Approval {{ $approval->pejabat->masterJabatan->nama_jabatan }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.7rem;">{{ $approval->pejabat->nama_lengkap }}</div>
                                </div>
                                @if($status == 'disetujui')
                                    <span class="badge bg-success-subtle text-success rounded-pill border-0 px-2" style="font-size:0.6rem;">SETUJU</span>
                                @elseif($status == 'ditolak')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill border-0 px-2" style="font-size:0.6rem;">DITOLAK</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @php $isFinal = in_array($pengajuan->status_pengajuan, ['selesai','siap_diambil','sudah_diambil']); @endphp
                    <div class="timeline-item {{ $isFinal ? 'active' : 'pending' }}">
                        <div class="timeline-dot"></div>
                        <div class="fw-bold small {{ $isFinal ? 'text-dark' : 'text-muted' }}">Selesai</div>
                        <div class="text-muted" style="font-size:0.7rem;">{{ $isFinal ? 'Surat telah diterbitkan' : 'Menunggu tahap akhir' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ MOBILE ══ --}}
<div class="mobile-show">

    {{-- Back --}}
    <a href="{{ route('mahasiswa.riwayat.index') }}" class="m-back-btn">
        <i class="bi bi-chevron-left"></i> Riwayat
    </a>

    {{-- Sticky status strip --}}
    @php
        $s = $pengajuan->status_pengajuan;
        $stripBg = match(true) {
            $s === 'selesai' || $s === 'sudah_diambil' => '#ecfdf5',
            $s === 'ditolak' || $s === 'perlu_revisi'  => '#fef2f2',
            $s === 'siap_diambil'                       => '#eff6ff',
            default                                     => '#fffbeb',
        };
        $stripColor = match(true) {
            $s === 'selesai' || $s === 'sudah_diambil' => '#059669',
            $s === 'ditolak' || $s === 'perlu_revisi'  => '#dc2626',
            $s === 'siap_diambil'                       => '#2563eb',
            default                                     => '#d97706',
        };
        $stripLabel = match(true) {
            $s === 'selesai'       => 'Selesai — Surat terbit',
            $s === 'sudah_diambil' => 'Sudah diambil',
            $s === 'ditolak'       => 'Ditolak',
            $s === 'perlu_revisi'  => 'Perlu revisi',
            $s === 'siap_diambil'  => 'Siap diambil di TU',
            $s === 'pending'       => 'Menunggu validasi staff',
            default                => Str::title(str_replace('_',' ',$s)),
        };
    @endphp
    <div class="m-status-strip" style="background:{{ $stripBg }};">
        <span class="m-status-strip-title">Status</span>
        <span style="font-size:0.85rem;font-weight:700;color:{{ $stripColor }};">{{ $stripLabel }}</span>
    </div>

    {{-- Rincian surat --}}
    <div class="m-card">
        <div class="m-card-header">
            <i class="bi bi-file-earmark-text" style="color:#3b82f6;"></i>
            <span>Rincian Surat</span>
        </div>
        <div class="m-card-body">
            <div class="m-info-row">
                <span class="m-info-label">Jenis</span>
                <span class="m-info-value" style="color:#3b82f6;">{{ $pengajuan->jenisSurat->nama_surat }}</span>
            </div>
            <div class="m-info-row">
                <span class="m-info-label">Tanggal</span>
                <span class="m-info-value">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}, {{ $pengajuan->tanggal_pengajuan->format('H:i') }} WIB</span>
            </div>
            <div class="m-info-row">
                <span class="m-info-label">Metode</span>
                <span class="m-info-value">{{ Str::title($pengajuan->metode_pengambilan) }}</span>
            </div>
            <div class="m-info-row" style="border-bottom:none;">
                <span class="m-info-label">Keperluan</span>
                <span class="m-info-value" style="font-weight:400;color:#475569;font-size:0.83rem;">{{ $pengajuan->keperluan }}</span>
            </div>
            @if($pengajuan->data_pendukung)
                @foreach($pengajuan->data_pendukung as $key => $value)
                <div class="m-info-row">
                    <span class="m-info-label">{{ Str::title(str_replace('_',' ',$key)) }}</span>
                    <span class="m-info-value">{{ $value }}</span>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Hasil --}}
    <div class="m-card">
        <div class="m-card-header">
            <i class="bi bi-star" style="color:#d97706;"></i>
            <span>Hasil Pengajuan</span>
        </div>
        <div class="m-card-body">
            @if($pengajuan->status_pengajuan == 'selesai')
                <div class="m-result-box text-center" style="background:#ecfdf5;border-radius:12px;">
                    <i class="bi bi-cloud-check" style="font-size:2rem;color:#059669;display:block;margin-bottom:0.5rem;"></i>
                    <div style="font-weight:800;color:#065f46;margin-bottom:4px;">Surat Siap Diunduh</div>
                    <p style="font-size:0.8rem;color:#6ee7b7;margin-bottom:1rem;">Surat telah ditandatangani secara elektronik.</p>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('preview.surat', basename($pengajuan->file_hasil_pdf)) }}" target="_blank" class="btn btn-action btn-outline-success d-flex align-items-center">
                            <i class="bi bi-eye me-2"></i> Preview Surat
                        </a>
                        <a href="{{ route('download.surat', basename($pengajuan->file_hasil_pdf)) }}" class="btn btn-action btn-success d-flex align-items-center">
                            <i class="bi bi-download me-2"></i> Download PDF
                        </a>
                    </div>
                </div>
            @elseif($pengajuan->status_pengajuan == 'siap_diambil')
                <div class="m-result-box" style="background:#eff6ff;border-radius:12px;">
                    <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                        <i class="bi bi-info-circle-fill" style="color:#3b82f6;font-size:1.5rem;margin-top:2px;flex-shrink:0;"></i>
                        <div>
                            <div style="font-weight:800;color:#1d4ed8;margin-bottom:4px;">Siap Diambil!</div>
                            <p style="font-size:0.8rem;color:#60a5fa;margin:0;">Ambil di <strong style="color:#1d4ed8;">Ruang Staff Jurusan</strong>. Nomor: <strong style="color:#1d4ed8;">{{ $pengajuan->nomor_surat }}</strong></p>
                        </div>
                    </div>
                </div>
            @elseif($pengajuan->status_pengajuan == 'sudah_diambil')
                <div class="m-result-box" style="background:#f8fafc;border-radius:12px;">
                    <div style="font-weight:700;color:#64748b;margin-bottom:4px;"><i class="bi bi-archive me-2"></i>Sudah Diambil</div>
                    <p style="font-size:0.8rem;color:#94a3b8;margin:0;">Diserahkan: {{ $pengajuan->tanggal_diambil->format('d M Y') }}</p>
                </div>
            @elseif($pengajuan->status_pengajuan == 'perlu_revisi' || $pengajuan->status_pengajuan == 'ditolak')
                <div class="m-result-box" style="background:#fef2f2;border-radius:12px;">
                    <div style="font-weight:800;color:#dc2626;margin-bottom:8px;"><i class="bi bi-exclamation-triangle me-2"></i>Perlu Perbaikan</div>
                    <div style="background:#fff;border-radius:8px;padding:0.75rem;margin-bottom:8px;">
                        <div style="font-size:0.72rem;font-weight:700;color:#94a3b8;margin-bottom:4px;">Catatan Petugas:</div>
                        <div style="font-size:0.85rem;color:#475569;font-style:italic;">"{{ $pengajuan->catatan_revisi }}"</div>
                    </div>
                    <p style="font-size:0.78rem;color:#fca5a5;margin:0;">Perbaiki data dan ajukan kembali.</p>
                </div>
            @else
                <div style="text-align:center;padding:1.5rem 0;">
                    <div class="spinner-grow spinner-grow-sm text-warning mb-2" role="status"></div>
                    <p style="font-size:0.82rem;color:#94a3b8;margin:0;">Sedang dalam antrian verifikasi...</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Tracking --}}
    <div class="m-card">
        <div class="m-card-header">
            <i class="bi bi-geo-alt" style="color:#8b5cf6;"></i>
            <span>Lacak Status</span>
        </div>
        <div class="m-card-body">
            <div class="m-timeline">
                <div class="m-tl-item">
                    <div class="m-tl-dot active"></div>
                    <div>
                        <div class="m-tl-label">Pengajuan dibuat</div>
                        <div class="m-tl-sub">{{ $pengajuan->tanggal_pengajuan->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>
                @php $isStaffDone = $pengajuan->admin_validator_id; @endphp
                <div class="m-tl-item">
                    <div class="m-tl-dot {{ $isStaffDone ? 'active' : 'pending' }}"></div>
                    <div>
                        <div class="m-tl-label" style="{{ $isStaffDone ? 'color:#1e293b' : 'color:#94a3b8' }}">Validasi staff jurusan</div>
                        <div class="m-tl-sub" style="{{ $isStaffDone ? 'color:#059669' : 'color:#d97706' }}">
                            {{ $isStaffDone ? 'Telah divalidasi' : 'Menunggu verifikasi' }}
                        </div>
                    </div>
                </div>
                @foreach($pengajuan->approvalPejabats->sortBy('urutan_approval') as $approval)
                    @php
                        $apStatus = $approval->status_approval;
                        $dotClass = $apStatus == 'disetujui' ? 'active' : ($apStatus == 'ditolak' ? 'rejected' : 'pending');
                    @endphp
                    <div class="m-tl-item">
                        <div class="m-tl-dot {{ $dotClass }}"></div>
                        <div style="flex:1;">
                            <div class="m-tl-label" style="{{ $dotClass != 'pending' ? 'color:#1e293b' : 'color:#94a3b8' }}">
                                Approval {{ $approval->pejabat->masterJabatan->nama_jabatan }}
                            </div>
                            <div class="m-tl-sub">{{ $approval->pejabat->nama_lengkap }}</div>
                        </div>
                        @if($apStatus == 'disetujui')
                            <span style="font-size:0.62rem;font-weight:700;background:#ecfdf5;color:#059669;padding:2px 8px;border-radius:20px;">SETUJU</span>
                        @elseif($apStatus == 'ditolak')
                            <span style="font-size:0.62rem;font-weight:700;background:#fef2f2;color:#dc2626;padding:2px 8px;border-radius:20px;">DITOLAK</span>
                        @endif
                    </div>
                @endforeach
                @php $isFinal = in_array($pengajuan->status_pengajuan, ['selesai','siap_diambil','sudah_diambil']); @endphp
                <div class="m-tl-item">
                    <div class="m-tl-dot {{ $isFinal ? 'active' : 'pending' }}"></div>
                    <div>
                        <div class="m-tl-label" style="{{ $isFinal ? 'color:#1e293b' : 'color:#94a3b8' }}">Selesai</div>
                        <div class="m-tl-sub">{{ $isFinal ? 'Surat telah diterbitkan' : 'Menunggu tahap akhir' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="height:1rem;"></div>
</div>

@endsection