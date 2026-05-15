@extends('layouts.app')

@section('title', 'Dashboard Admin Akademik')
@section('page-title', 'Dashboard Admin Akademik')

@push('styles')
<style>
    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .welcome-card {
        background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%);
        border-radius: 16px;
        overflow: hidden;
        position: relative;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -60px;
        right: 80px;
        width: 220px;
        height: 220px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1rem;
        border-radius: 10px;
        background: #fffbf0;
        border: 1px solid #fde68a;
        transition: background 0.2s;
    }

    .info-item:hover {
        background: #fef3c7;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        flex-shrink: 0;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')

{{-- WELCOME BANNER --}}
<div class="welcome-card shadow p-4 mb-4">
    <div class="row align-items-center position-relative" style="z-index:1;">
        <div class="col">
            <p class="text-white-50 mb-1 small fw-medium text-uppercase">
                <i class="bi bi-shield-check me-1"></i> Admin Akademik
            </p>
            <h3 class="fw-bold text-white mb-1">Selamat Datang, {{ Auth::user()->name }} 👋</h3>
            <p class="text-white-50 mb-0 small">
                <i class="bi bi-calendar3 me-1"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                &nbsp;•&nbsp;
                <i class="bi bi-clock me-1"></i>
                <span id="live-clock"></span>
            </p>
        </div>
        <div class="col-auto d-none d-md-block">
            <i class="bi bi-mortarboard text-white opacity-25" style="font-size: 5rem;"></i>
        </div>
    </div>
</div>

{{-- QUICK STATS --}}
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Jenis Surat</h6>
                        <h2 class="fw-bold mb-0">{{ $totalJenisSurat }}</h2>
                        <small class="text-muted">Template tersedia</small>
                    </div>
                    <div class="icon-shape bg-primary-subtle text-primary">
                        <i class="bi bi-files fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Pejabat</h6>
                        <h2 class="fw-bold mb-0">{{ $totalPejabat }}</h2>
                        <small class="text-muted">Penanda tangan aktif</small>
                    </div>
                    <div class="icon-shape bg-warning-subtle text-warning">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Staff Jurusan</h6>
                        <h2 class="fw-bold mb-0">{{ $totalAdminStaff }}</h2>
                        <small class="text-muted">Unit kerja aktif</small>
                    </div>
                    <div class="icon-shape bg-success-subtle text-success">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Mahasiswa</h6>
                        <h2 class="fw-bold mb-0">{{ $totalMahasiswa }}</h2>
                        <small class="text-muted">Terdaftar di sistem</small>
                    </div>
                    <div class="icon-shape bg-info-subtle text-info">
                        <i class="bi bi-person-vcard fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- INFO AKUN --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0">
            <i class="bi bi-info-circle me-2" style="color:#f59e0b;"></i>
            Informasi Akun
        </h6>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="row g-3">
            <div class="col-md-6 col-lg-4">
                <div class="info-item">
                    <div class="info-icon bg-warning-subtle text-warning">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Role</div>
                        <div class="fw-semibold text-dark text-capitalize">
                            @php
                                $role = Auth::user()->role;
                                echo is_object($role) ? ($role->Nama_role ?? $role->name ?? 'Admin Akademik')
                                   : (is_array($role)  ? ($role['Nama_role'] ?? $role['name'] ?? 'Admin Akademik')
                                   : ($role            ?? 'Admin Akademik'));
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="info-item">
                    <div class="info-icon bg-success-subtle text-success">
                        <i class="bi bi-envelope-at"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Email</div>
                        <div class="fw-semibold text-dark text-truncate" style="max-width:140px;">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="info-item">
                    <div class="info-icon bg-info-subtle text-info">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Akun dibuat</div>
                        <div class="fw-semibold text-dark">{{ Auth::user()->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('live-clock');
        if (el) el.textContent = `${h}:${m}:${s} WIB`;
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
@endpush