@extends('layouts.app')

@section('title', 'Dashboard Admin Akademik')
@section('page-title', 'Dashboard Admin Akademik')

@push('styles')
<style>
    /* Efek Hover untuk Stats Cards */
    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }

    /* Gradient Icons Background */
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    /* List Group Modern Style */
    .list-group-flush .list-group-item {
        border-left: 0;
        border-right: 0;
        padding: 1rem 0;
        background: transparent;
    }

    .activity-dot {
        height: 8px;
        width: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
    }
</style>
@endpush

@section('content')

{{-- =========================== --}}
{{--   CARD OVERVIEW QUICK STATS   --}}
{{-- =========================== --}}
<div class="row g-4 mb-4">
    {{-- Total Jenis Surat --}}
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card shadow-sm h-100 overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Total Jenis Surat</h6>
                        <h2 class="fw-bold mb-0">{{ $totalJenisSurat }}</h2>
                    </div>
                    <div class="icon-shape bg-primary-subtle text-primary">
                        <i class="bi bi-files fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-success small fw-medium"><i class="bi bi-arrow-up"></i> Teraktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Pejabat --}}
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card shadow-sm h-100 overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Total Pejabat</h6>
                        <h2 class="fw-bold mb-0">{{ $totalPejabat }}</h2>
                    </div>
                    <div class="icon-shape bg-warning-subtle text-warning">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-muted small">Tersedia di sistem</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Staff Jurusan --}}
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card shadow-sm h-100 overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Staff Jurusan</h6>
                        <h2 class="fw-bold mb-0">{{ $totalAdminStaff }}</h2>
                    </div>
                    <div class="icon-shape bg-success-subtle text-success">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-muted small">Unit Kerja Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Mahasiswa --}}
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card shadow-sm h-100 overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Total Mahasiswa</h6>
                        <h2 class="fw-bold mb-0">{{ $totalMahasiswa }}</h2>
                    </div>
                    <div class="icon-shape bg-info-subtle text-info">
                        <i class="bi bi-person-vcard fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-muted small">Terdaftar</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =========================== --}}
{{--         WELCOME AREA        --}}
{{-- =========================== --}}
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(to right, #ffffff, #fffdf5);">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="fw-bold text-dark mb-2">Selamat Datang di SISURAT UP45</h4>
                <p class="text-muted mb-0">Kelola administrasi persuratan dengan lebih cepat dan terorganisir melalui dashboard kendali utama.</p>
                <div class="d-flex gap-3 mt-3">
                    <div class="d-flex align-items-center small text-muted"><i class="bi bi-check2-circle text-success me-1"></i> Data Master</div>
                    <div class="d-flex align-items-center small text-muted"><i class="bi bi-check2-circle text-success me-1"></i> Alur Approval</div>
                    <div class="d-flex align-items-center small text-muted"><i class="bi bi-check2-circle text-success me-1"></i> Log Aktivitas</div>
                </div>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="bi bi-speedometer2 text-primary opacity-25" style="font-size: 5rem;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Notification Card --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                <div class="icon-shape bg-primary text-white me-3 shadow-sm" style="width: 32px; height: 32px;">
                    <i class="bi bi-bell-fill small"></i>
                </div>
                <h5 class="mb-0 fw-bold">Pemberitahuan</h5>
            </div>
            <div class="card-body pt-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-medium d-block">Pembaruan Sistem</span>
                            <small class="text-muted">Versi 2.0 telah dirilis</small>
                        </div>
                        <span class="badge rounded-pill bg-success-subtle text-success border border-success">Baru</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-medium d-block">Pengajuan Surat Tertunda</span>
                            <small class="text-muted">Butuh perhatian segera</small>
                        </div>
                        <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning">Pending</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-medium d-block">Persetujuan Mahasiswa Baru</span>
                            <small class="text-muted">Data masuk hari ini</small>
                        </div>
                        <span class="badge rounded-pill bg-info-subtle text-info border border-info">Proses</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Latest Activities --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                <div class="icon-shape bg-dark text-white me-3 shadow-sm" style="width: 32px; height: 32px;">
                    <i class="bi bi-clock-history small"></i>
                </div>
                <h5 class="mb-0 fw-bold">Aktivitas Terbaru</h5>
            </div>
            <div class="card-body pt-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <span class="activity-dot bg-primary"></span>
                                <span class="text-dark">Mahasiswa A mengajukan surat</span>
                            </div>
                            <small class="text-muted">1 jam lalu</small>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <span class="activity-dot bg-warning"></span>
                                <span class="text-dark">Pejabat B menyetujui pengajuan</span>
                            </div>
                            <small class="text-muted">3 jam lalu</small>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <span class="activity-dot bg-secondary"></span>
                                <span class="text-dark">Staff C melakukan pengambilan</span>
                            </div>
                            <small class="text-muted">2 hari lalu</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection