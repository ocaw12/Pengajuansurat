@extends('layouts.app')

@section('title', 'Detail Pengajuan')
@section('page-title', 'Detail #' . ($pengajuan->nomor_surat ?? $pengajuan->id))

@push('styles')
<style>
    :root{
        --primary:#2563eb;
        --success:#10b981;
        --warning:#f59e0b;
        --danger:#ef4444;
        --dark:#0f172a;
        --muted:#64748b;
        --border:#e2e8f0;
        --soft:#f8fafc;
    }

    body{
        background:#f1f5f9;
    }

    .detail-wrapper{
        max-width:1400px;
        margin:auto;
    }

    .detail-card{
        background:#fff;
        border:none;
        border-radius:24px;
        overflow:hidden;
        box-shadow:0 10px 30px rgba(15,23,42,.05);
        margin-bottom:1.5rem;
    }

    .detail-header{
        padding:1.5rem 2rem;
        border-bottom:1px solid #f1f5f9;
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:1rem;
    }

    .detail-title{
        display:flex;
        align-items:center;
        gap:1rem;
    }

    .detail-icon{
        width:54px;
        height:54px;
        border-radius:18px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:linear-gradient(135deg,#3b82f6,#2563eb);
        color:#fff;
        font-size:1.3rem;
    }

    .detail-title h4{
        margin:0;
        font-weight:800;
        color:var(--dark);
    }

    .detail-title p{
        margin:0;
        color:#94a3b8;
        font-size:.88rem;
    }

    .status-pill{
        padding:.7rem 1.2rem;
        border-radius:999px;
        font-size:.8rem;
        font-weight:700;
        display:inline-flex;
        align-items:center;
        gap:.5rem;
    }

    .status-success{
        background:#ecfdf5;
        color:#059669;
    }

    .status-warning{
        background:#fffbeb;
        color:#d97706;
    }

    .status-danger{
        background:#fef2f2;
        color:#dc2626;
    }

    .status-info{
        background:#eff6ff;
        color:#2563eb;
    }

    .content-grid{
        display:grid;
        grid-template-columns:1.4fr .9fr;
        gap:1.5rem;
        align-items:start;
    }

    .section-body{
        padding:2rem;
    }

    .info-grid{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:1.25rem;
    }

    .info-box{
        background:var(--soft);
        border-radius:18px;
        padding:1rem 1.1rem;
        border:1px solid #eef2f7;
    }

    .info-label{
        font-size:.72rem;
        font-weight:700;
        letter-spacing:.05em;
        text-transform:uppercase;
        color:#94a3b8;
        margin-bottom:.45rem;
        display:block;
    }

    .info-value{
        color:var(--dark);
        font-weight:700;
        font-size:.95rem;
    }

    .purpose-box{
        margin-top:1.5rem;
        background:#f8fafc;
        border-left:4px solid var(--primary);
        border-radius:18px;
        padding:1.2rem;
        color:#475569;
        line-height:1.7;
    }

    .support-list{
        margin-top:1.5rem;
    }

    .support-item{
        background:#fff;
        border:1px solid #eef2f7;
        border-radius:16px;
        padding:1rem;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:1rem;
        margin-bottom:.8rem;
    }

    .support-item:last-child{
        margin-bottom:0;
    }

    .doc-btn{
        display:inline-flex;
        align-items:center;
        gap:.5rem;
        padding:.65rem 1rem;
        border-radius:12px;
        background:#eff6ff;
        color:#2563eb;
        text-decoration:none;
        font-weight:700;
        font-size:.82rem;
        transition:.2s;
    }

    .doc-btn:hover{
        background:#dbeafe;
        color:#1d4ed8;
    }

    .timeline{
        position:relative;
        padding-left:2rem;
    }

    .timeline::before{
        content:'';
        position:absolute;
        left:8px;
        top:4px;
        width:2px;
        height:calc(100% - 10px);
        background:#e2e8f0;
    }

    .timeline-item{
        position:relative;
        padding-bottom:1.7rem;
    }

    .timeline-item:last-child{
        padding-bottom:0;
    }

    .timeline-dot{
        width:18px;
        height:18px;
        border-radius:50%;
        background:#fff;
        border:3px solid #cbd5e1;
        position:absolute;
        left:-2rem;
        top:3px;
        z-index:2;
    }

    .timeline-dot.active{
        background:var(--success);
        border-color:var(--success);
    }

    .timeline-dot.pending{
        border-color:var(--warning);
    }

    .timeline-dot.rejected{
        background:var(--danger);
        border-color:var(--danger);
    }

    .timeline-title{
        font-weight:700;
        color:var(--dark);
        font-size:.92rem;
    }

    .timeline-sub{
        font-size:.8rem;
        color:#94a3b8;
        margin-top:2px;
    }

    .result-box{
        border-radius:24px;
        padding:1.8rem;
    }

    .btn-action{
        border:none;
        border-radius:14px;
        padding:.85rem 1.3rem;
        font-weight:700;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:.6rem;
        transition:.2s;
        text-decoration:none;
    }

    .btn-primary-soft{
        background:#eff6ff;
        color:#2563eb;
    }

    .btn-primary-soft:hover{
        background:#dbeafe;
        color:#1d4ed8;
    }

    .btn-success-main{
        background:#10b981;
        color:#fff;
    }

    .btn-success-main:hover{
        background:#059669;
        color:#fff;
    }

    @media(max-width:991px){

        .content-grid{
            grid-template-columns:1fr;
        }

        .section-body{
            padding:1.3rem;
        }

        .detail-header{
            padding:1.2rem 1.3rem;
        }

        .info-grid{
            grid-template-columns:1fr;
        }

        .support-item{
            flex-direction:column;
            align-items:flex-start;
        }

        .btn-action{
            width:100%;
        }

    }
</style>
@endpush

@section('content')

@php
    $status = $pengajuan->status_pengajuan;

    $statusClass = match(true) {
        $status === 'selesai' || $status === 'sudah_diambil' => 'status-success',
        $status === 'ditolak' || $status === 'perlu_revisi' => 'status-danger',
        $status === 'siap_diambil' => 'status-info',
        default => 'status-warning',
    };

    $statusText = match(true) {
        $status === 'selesai' => 'Surat Selesai',
        $status === 'sudah_diambil' => 'Sudah Diambil',
        $status === 'ditolak' => 'Ditolak',
        $status === 'perlu_revisi' => 'Perlu Revisi',
        $status === 'siap_diambil' => 'Siap Diambil',
        default => 'Sedang Diproses',
    };
@endphp

<div class="detail-wrapper">

    {{-- HEADER --}}
    <div class="detail-card">
        <div class="detail-header">

            <div class="detail-title">
                <div class="detail-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <div>
                    <h4>{{ $pengajuan->jenisSurat->nama_surat }}</h4>
                    <p>
                        Nomor Pengajuan :
                        <strong>#{{ $pengajuan->nomor_surat ?? $pengajuan->id }}</strong>
                    </p>
                </div>
            </div>

            <div class="status-pill {{ $statusClass }}">
                <i class="bi bi-check-circle-fill"></i>
                {{ $statusText }}
            </div>

        </div>
    </div>

    <div class="content-grid">

        {{-- LEFT --}}
        <div>

            {{-- INFORMASI --}}
            <div class="detail-card">
                <div class="section-body">

                    <h5 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-info-circle me-2 text-primary"></i>
                        Informasi Pengajuan
                    </h5>

                    <div class="info-grid">

                        <div class="info-box">
                            <span class="info-label">Jenis Surat</span>
                            <div class="info-value text-primary">
                                {{ $pengajuan->jenisSurat->nama_surat }}
                            </div>
                        </div>

                        <div class="info-box">
                            <span class="info-label">Metode Pengambilan</span>
                            <div class="info-value">
                                {{ Str::title($pengajuan->metode_pengambilan) }}
                            </div>
                        </div>

                        <div class="info-box">
                            <span class="info-label">Tanggal Pengajuan</span>
                            <div class="info-value">
                                {{ $pengajuan->tanggal_pengajuan->format('d M Y') }}
                            </div>
                            <small class="text-muted">
                                {{ $pengajuan->tanggal_pengajuan->format('H:i') }} WIB
                            </small>
                        </div>

                        <div class="info-box">
                            <span class="info-label">Status</span>
                            <div class="mt-1">
                                @include('partials.status_badge', ['status' => $pengajuan->status_pengajuan])
                            </div>
                        </div>

                    </div>

                    <div class="purpose-box">
                        <div class="info-label mb-2">Keperluan</div>
                        “{{ $pengajuan->keperluan }}”
                    </div>

                    {{-- DOKUMEN --}}
                    @if($pengajuan->data_pendukung)
                        <div class="support-list">

                            <div class="info-label mb-3">
                                Dokumen Pendukung
                            </div>

                            @foreach($pengajuan->data_pendukung as $key => $value)

                                <div class="support-item">

                                    <div>
                                        <div class="info-label mb-1">
                                            {{ Str::title(str_replace('_', ' ', $key)) }}
                                        </div>

                                        @if(!is_string($value) || !Str::contains($value, 'dokumen_pengajuan'))
                                            <div class="info-value">
                                                {{ is_array($value) ? implode(', ', $value) : $value }}
                                            </div>
                                        @endif
                                    </div>

                                    @if(is_string($value) && Str::contains($value, 'dokumen_pengajuan'))
                                        <a href="{{ route('preview.dokumen', basename($value)) }}"
                                           target="_blank"
                                           class="doc-btn">
                                            <i class="bi bi-eye"></i>
                                            Preview Dokumen
                                        </a>
                                    @endif

                                </div>

                            @endforeach

                        </div>
                    @endif

                </div>
            </div>

            {{-- HASIL --}}
            <div class="detail-card">
                <div class="section-body">

                    <h5 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-stars me-2 text-warning"></i>
                        Hasil Pengajuan
                    </h5>

                    @if($pengajuan->status_pengajuan == 'selesai')

                        <div class="result-box" style="background:#ecfdf5;">

                            <div class="text-center">

                                <i class="bi bi-cloud-check-fill"
                                   style="font-size:3rem;color:#10b981;"></i>

                                <h4 class="fw-bold mt-3 text-success">
                                    Surat Digital Tersedia
                                </h4>

                                <p class="text-muted mb-4">
                                    Surat telah selesai diproses dan siap digunakan.
                                </p>

                                <div class="d-flex flex-wrap gap-3 justify-content-center">

                                    <a href="{{ route('preview.surat', basename($pengajuan->file_hasil_pdf)) }}"
                                       target="_blank"
                                       class="btn-action btn-primary-soft">

                                        <i class="bi bi-eye"></i>
                                        Preview Surat
                                    </a>

                                    <a href="{{ route('download.surat', basename($pengajuan->file_hasil_pdf)) }}"
                                       class="btn-action btn-success-main">

                                        <i class="bi bi-download"></i>
                                        Download PDF
                                    </a>

                                </div>

                            </div>

                        </div>

                    @elseif($pengajuan->status_pengajuan == 'siap_diambil')

                        <div class="result-box" style="background:#eff6ff;">

                            <div class="d-flex gap-3">

                                <i class="bi bi-info-circle-fill"
                                   style="font-size:2rem;color:#2563eb;"></i>

                                <div>
                                    <h5 class="fw-bold text-primary">
                                        Surat Siap Diambil
                                    </h5>

                                    <p class="mb-0 text-secondary">
                                        Silakan ambil surat di
                                        <strong>Ruang Staff Jurusan</strong>
                                        dengan menyebutkan nomor:
                                        <strong>{{ $pengajuan->nomor_surat }}</strong>
                                    </p>
                                </div>

                            </div>

                        </div>

                    @elseif($pengajuan->status_pengajuan == 'ditolak' || $pengajuan->status_pengajuan == 'perlu_revisi')

                        <div class="result-box" style="background:#fef2f2;">

                            <h5 class="fw-bold text-danger mb-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Pengajuan Perlu Perbaikan
                            </h5>

                            <div class="bg-white rounded-4 p-3">
                                <div class="info-label mb-2">
                                    Catatan Petugas
                                </div>

                                <div class="text-secondary fst-italic">
                                    “{{ $pengajuan->catatan_revisi }}”
                                </div>
                            </div>

                        </div>

                    @else

                        <div class="result-box text-center" style="background:#fffbeb;">

                            <div class="spinner-grow spinner-grow-sm text-warning mb-3"></div>

                            <h6 class="fw-bold text-warning">
                                Sedang Diproses
                            </h6>

                            <p class="mb-0 text-muted">
                                Pengajuan sedang berada dalam tahap verifikasi.
                            </p>

                        </div>

                    @endif

                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div>

            <div class="detail-card">

                <div class="section-body">

                    <h5 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-geo-alt-fill me-2 text-purple"></i>
                        Tracking Pengajuan
                    </h5>

                    <div class="timeline">

                        {{-- CREATED --}}
                        <div class="timeline-item">

                            <div class="timeline-dot active"></div>

                            <div class="timeline-title">
                                Pengajuan Dibuat
                            </div>

                            <div class="timeline-sub">
                                {{ $pengajuan->tanggal_pengajuan->format('d M Y, H:i') }} WIB
                            </div>

                        </div>

                        {{-- STAFF --}}
                        @php
                            $isStaffDone = $pengajuan->admin_validator_id;
                        @endphp

                        <div class="timeline-item">

                            <div class="timeline-dot {{ $isStaffDone ? 'active' : 'pending' }}"></div>

                            <div class="timeline-title">
                                Validasi Staff Jurusan
                            </div>

                            <div class="timeline-sub">
                                {{ $isStaffDone ? 'Telah diverifikasi staff jurusan' : 'Menunggu validasi staff' }}
                            </div>

                        </div>

                        {{-- APPROVAL --}}
                        @foreach($pengajuan->approvalPejabats->sortBy('urutan_approval') as $approval)

                            @php
                                $statusApproval = $approval->status_approval;

                                $dotClass = $statusApproval == 'disetujui'
                                    ? 'active'
                                    : ($statusApproval == 'ditolak'
                                        ? 'rejected'
                                        : 'pending');
                            @endphp

                            <div class="timeline-item">

                                <div class="timeline-dot {{ $dotClass }}"></div>

                                <div class="timeline-title">
                                    Approval {{ $approval->pejabat->masterJabatan->nama_jabatan }}
                                </div>

                                <div class="timeline-sub">
                                    {{ $approval->pejabat->nama_lengkap }}
                                </div>

                            </div>

                        @endforeach

                        {{-- FINAL --}}
                        @php
                            $isFinal = in_array(
                                $pengajuan->status_pengajuan,
                                ['selesai','siap_diambil','sudah_diambil']
                            );
                        @endphp

                        <div class="timeline-item">

                            <div class="timeline-dot {{ $isFinal ? 'active' : 'pending' }}"></div>

                            <div class="timeline-title">
                                Selesai
                            </div>

                            <div class="timeline-sub">
                                {{ $isFinal ? 'Surat berhasil diterbitkan' : 'Menunggu tahap akhir' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection