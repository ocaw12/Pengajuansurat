@extends('layouts.app')

@section('title', 'Dashboard Admin Akademik')
@section('page-title', 'Dashboard Admin Akademik')

@section('content')

{{-- =========================== --}}
{{--  CARD OVERVIEW QUICK STATS   --}}
{{-- =========================== --}}
<div class="row g-3 mb-4">

    {{-- Total Jenis Surat --}}
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="badge bg-primary rounded-circle p-3">
                        <i class="bi bi-files fs-5 text-white"></i>
                    </span>
                </div>
                <div>
                    <p class="text-muted mb-1 small">Total Jenis Surat</p>
                    <h3 class="fw-bold mb-0">{{ $totalJenisSurat }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Pejabat --}}
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="badge bg-warning rounded-circle p-3">
                        <i class="bi bi-person-badge fs-5 text-dark"></i>
                    </span>
                </div>
                <div>
                    <p class="text-muted mb-1 small">Total Pejabat</p>
                    <h3 class="fw-bold mb-0">{{ $totalPejabat }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Staff Jurusan --}}
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="badge bg-success rounded-circle p-3">
                        <i class="bi bi-people fs-5 text-white"></i>
                    </span>
                </div>
                <div>
                    <p class="text-muted mb-1 small">Total Staff Jurusan</p>
                    <h3 class="fw-bold mb-0">{{ $totalAdminStaff }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Mahasiswa --}}
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="badge bg-info rounded-circle p-3">
                        <i class="bi bi-person-vcard fs-5 text-white"></i>
                    </span>
                </div>
                <div>
                    <p class="text-muted mb-1 small">Total Mahasiswa</p>
                    <h3 class="fw-bold mb-0">{{ $totalMahasiswa }}</h3>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- =========================== --}}
{{--        RINGKASAN SYSTEM     --}}
{{-- =========================== --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0 card-title">
            <i class="bi bi-speedometer2 me-2"></i>
            Ringkasan Sistem
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-2">
            Selamat datang di <strong>Dashboard Admin Akademik</strong>. 
            Berikut ini gambaran umum dari aktivitas sistem SISURAT UP45.
        </p>

        <ul class="small text-muted mb-0">
            <li>Pantau data master seperti Fakultas, Prodi, Pejabat, Jenis Surat.</li>
            <li>Kelola alur approval setiap jenis surat.</li>
            <li>Atur pengguna seperti staff jurusan, mahasiswa, dan pejabat.</li>
            <li>Awasi aktivitas pengguna melalui Log Aktivitas.</li>
        </ul>
    </div>
</div>

{{-- =========================== --}}
{{--  (OPTIONAL placeholder grafik) --}}
{{-- =========================== --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0 card-title">
            <i class="bi bi-bar-chart-line me-2"></i>
            Grafik Aktivitas (Coming Soon)
        </h5>
    </div>
    <div class="card-body text-center text-muted py-5">
        <i class="bi bi-graph-up fs-1 mb-3 text-secondary"></i>
        <p>Fitur grafik pengajuan & tren sistem akan ditambahkan di versi berikutnya.</p>
    </div>
</div>

@endsection
