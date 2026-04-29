@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Overview')

@push('styles')
<style>
    /* ── Desktop styles (unchanged) ── */
    .card { border-radius: 15px; transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-5px); }

    .icon-shape {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #2dce89 0%, #2dcecc 100%); }
    .bg-gradient-danger  { background: linear-gradient(135deg, #f5365c 0%, #f56036 100%); }

    .table thead th {
        background-color: #f8fafc; text-transform: uppercase;
        font-size: 0.75rem; letter-spacing: 0.05em;
        font-weight: 700; border: none; color: #64748b;
    }
    .table tbody td { border-bottom: 1px solid #f1f5f9; padding: 1.2rem 0.75rem; }
    .table tbody tr:last-child td { border: none; }

    .status-badge {
        padding: 0.5em 1em; border-radius: 50px;
        font-weight: 600; font-size: 0.75rem;
    }

    /* ══════════════════════════════════════════
       MOBILE dashboard overrides
    ══════════════════════════════════════════ */
    @media (max-width: 767.98px) {

        /* Hide desktop table section */
        .desktop-table-section { display: none !important; }

        /* Welcome strip */
        .welcome-strip {
            background: #fff;
            padding: 1.1rem 1.25rem 1rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .welcome-strip h5 { font-size: 1rem; font-weight: 800; margin-bottom: 2px; }
        .welcome-strip p { font-size: 0.8rem; color: #94a3b8; margin: 0; }

        /* Stat pills row */
        .stat-pills-row {
            display: flex;
            gap: 10px;
            padding: 1rem 1.25rem;
            background: #f8fafc;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .stat-pills-row::-webkit-scrollbar { display: none; }

        .stat-pill {
            flex: 0 0 auto;
            background: #fff;
            border-radius: 14px;
            padding: 0.8rem 1rem;
            min-width: 110px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            text-align: center;
        }
        .stat-pill .stat-num { font-size: 1.6rem; font-weight: 800; line-height: 1; }
        .stat-pill .stat-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-top: 4px; }
        .stat-pill.primary .stat-num { color: #7c3aed; }
        .stat-pill.success .stat-num { color: #059669; }
        .stat-pill.danger  .stat-num { color: #dc2626; }

        /* Section header */
        .m-section-header {
            padding: 1.1rem 1.25rem 0.5rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .m-section-title { font-size: 0.9rem; font-weight: 700; color: #1e293b; }
        .m-section-link  { font-size: 0.8rem; font-weight: 600; color: #d97706; text-decoration: none; }

        /* Mobile list */
        .m-list-item {
            background: #fff;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f8fafc;
            display: flex; align-items: center; gap: 0.8rem;
            text-decoration: none; color: inherit;
            transition: background 0.12s;
            -webkit-tap-highlight-color: transparent;
        }
        .m-list-item:active { background: #fef9f0; }
        .m-list-icon {
            width: 40px; height: 40px; border-radius: 11px;
            background: #eff6ff; display: flex;
            align-items: center; justify-content: center; flex-shrink: 0;
        }
        .m-list-title { font-size: 0.875rem; font-weight: 700; color: #1e293b; }
        .m-list-sub   { font-size: 0.72rem; color: #94a3b8; margin-top: 1px; }

        /* WA Contact card */
        .m-contact-card {
            margin: 0 1.25rem 1rem;
            background: #1e293b;
            border-radius: 18px;
            padding: 1.25rem;
            display: flex; align-items: center; gap: 1rem;
            position: relative; overflow: hidden;
        }
        .m-contact-card::after {
            content: '\F258';
            font-family: 'bootstrap-icons';
            position: absolute; right: -10px; top: -8px;
            font-size: 80px; opacity: 0.05; color: #fff;
        }
        .m-contact-text { flex: 1; }
        .m-contact-text h6 { color: #fff; font-size: 0.85rem; font-weight: 700; margin: 0 0 4px; }
        .m-contact-text p  { color: #94a3b8; font-size: 0.72rem; margin: 0; }

        /* Status guide pill */
        .m-guide-row {
            display: flex; gap: 8px;
            padding: 0.75rem 1.25rem 1rem;
            background: #f8fafc;
            flex-wrap: wrap;
        }
        .m-guide-pill {
            display: flex; align-items: center; gap: 5px;
            background: #fff; border-radius: 20px;
            padding: 5px 10px; font-size: 0.72rem; font-weight: 600;
            border: 1px solid #f1f5f9;
        }
        .m-guide-dot { width: 8px; height: 8px; border-radius: 50%; }

        /* Empty state */
        .m-empty {
            padding: 2.5rem 1.25rem;
            text-align: center;
            background: #fff;
        }
        .m-empty-icon { font-size: 2.5rem; color: #e2e8f0; margin-bottom: 0.75rem; }
        .m-empty p { font-size: 0.85rem; color: #94a3b8; margin: 0; }
        .m-empty a { display: inline-block; margin-top: 1rem; }

        /* Show mobile section */
        .mobile-dashboard-section { display: block !important; }
    }

    @media (min-width: 768px) {
        .mobile-dashboard-section { display: none !important; }
    }
</style>
@endpush

@section('content')

{{-- ══ DESKTOP LAYOUT (≥768px) ══ --}}
<div class="d-none d-md-block">
<div class="container-fluid py-2">

    {{-- Welcome Header --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold mb-1 text-dark">Selamat Datang, {{ Auth::user()->name ?? 'Mahasiswa' }}! 👋</h3>
            <p class="text-muted">Pantau status pengajuan suratmu secara real-time di sini.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary btn-lg shadow-sm" style="border-radius: 12px;">
                <i class="bi bi-plus-lg me-2"></i>Buat Pengajuan
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-gradient-primary text-white shadow-sm me-3">
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Total Pengajuan</span>
                            <h3 class="fw-bold mb-0 mt-1">{{ $totalPengajuan }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-gradient-success text-white shadow-sm me-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Selesai / Terbit</span>
                            <h3 class="fw-bold mb-0 mt-1 text-success">{{ $totalSelesai }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-gradient-danger text-white shadow-sm me-3">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Perlu Revisi</span>
                            <h3 class="fw-bold mb-0 mt-1 text-danger">{{ $totalDitolak }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Table --}}
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                    <h5 class="fw-bold mb-0">Pengajuan Terbaru</h5>
                    <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-sm btn-light text-primary fw-bold">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body px-0 pt-0">
                    @if($pengajuanTerbaru->isEmpty())
                        <div class="text-center py-5">
                            <img src="https://illustrations.popsy.co/gray/data-report.svg" alt="empty" style="width: 150px;" class="mb-3 opacity-50">
                            <p class="text-muted">Belum ada aktivitas pengajuan.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Jenis Surat</th>
                                        <th>Tgl. Pengajuan</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuanTerbaru as $pengajuan)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 text-center" style="width:40px;height:40px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                                    <i class="bi bi-file-text text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold small">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</h6>
                                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($pengajuan->keperluan, 25) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small text-muted">{{ optional($pengajuan->tanggal_pengajuan)->format('d M Y') }}</td>
                                        <td>@include('partials.status_badge', ['status' => $pengajuan->status_pengajuan])</td>
                                        <td class="text-center">
                                            <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}" class="btn btn-sm btn-outline-light border text-dark shadow-sm px-3">
                                                <i class="bi bi-search me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Side Info --}}
        <div class="col-lg-4">
            <div class="card border-0 bg-dark text-white shadow mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="position-relative" style="z-index: 2;">
                        <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-warning"></i>Butuh Bantuan?</h5>
                        <p class="small opacity-75 mb-4">Jika kamu mengalami kendala atau butuh informasi lebih lanjut mengenai surat-surat akademik, silakan hubungi Staff TU.</p>
                        @if($linkWa)
                            <a href="{{ $linkWa }}" target="_blank" class="btn btn-warning btn-sm fw-bold px-4 py-2 shadow-sm" style="border-radius: 8px;">
                                Hubungi Admin
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm fw-bold px-4 py-2" disabled>Admin belum tersedia</button>
                        @endif
                    </div>
                    <div class="position-absolute" style="top:-20px;right:-20px;font-size:150px;opacity:0.1;">
                        <i class="bi bi-question-circle"></i>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 small text-uppercase">Panduan Warna</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div style="width:12px;height:12px;border-radius:50%;background:#f59e0b;" class="me-3"></div>
                        <span class="small text-muted"><strong>Menunggu:</strong> Sedang divalidasi.</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div style="width:12px;height:12px;border-radius:50%;background:#10b981;" class="me-3"></div>
                        <span class="small text-muted"><strong>Selesai:</strong> Surat bisa diunduh/ambil.</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div style="width:12px;height:12px;border-radius:50%;background:#ef4444;" class="me-3"></div>
                        <span class="small text-muted"><strong>Ditolak:</strong> Perlu perbaikan data.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>{{-- /desktop --}}


{{-- ══ MOBILE LAYOUT (<768px) ══ --}}
<div class="mobile-dashboard-section d-md-none">

    {{-- Welcome strip --}}
    <div class="welcome-strip">
        <h5>Hai, {{ Str::words(Auth::user()->name ?? 'Mahasiswa', 1, '') }}! 👋</h5>
        <p>Pantau pengajuan suratmu di sini.</p>
    </div>

    {{-- Stat pills --}}
    <div class="stat-pills-row">
        <div class="stat-pill primary">
            <div class="stat-num">{{ $totalPengajuan }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-pill success">
            <div class="stat-num">{{ $totalSelesai }}</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-pill danger">
            <div class="stat-num">{{ $totalDitolak }}</div>
            <div class="stat-label">Revisi</div>
        </div>
        @php
            $totalProses = $totalPengajuan - $totalSelesai - $totalDitolak;
        @endphp
        <div class="stat-pill" style="">
            <div class="stat-num" style="color:#d97706;">{{ max(0,$totalProses) }}</div>
            <div class="stat-label">Proses</div>
        </div>
    </div>

    {{-- Pengajuan Terbaru --}}
    <div class="m-section-header">
        <span class="m-section-title">Pengajuan Terbaru</span>
        <a href="{{ route('mahasiswa.riwayat.index') }}" class="m-section-link">Lihat semua →</a>
    </div>

    <div style="background:#fff;border-radius:0;overflow:hidden;margin-bottom:0.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        @if($pengajuanTerbaru->isEmpty())
            <div class="m-empty">
                <div class="m-empty-icon"><i class="bi bi-folder-x"></i></div>
                <p>Belum ada pengajuan surat.</p>
                <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-warning btn-sm fw-bold px-4" style="border-radius:10px;">
                    <i class="bi bi-plus-lg me-1"></i> Buat Sekarang
                </a>
            </div>
        @else
            @foreach($pengajuanTerbaru as $pengajuan)
            <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}" class="m-list-item">
                <div class="m-list-icon">
                    <i class="bi bi-file-earmark-text" style="color:#3b82f6;font-size:1.1rem;"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="m-list-title text-truncate">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</div>
                    <div class="m-list-sub">{{ optional($pengajuan->tanggal_pengajuan)->format('d M Y') }}</div>
                </div>
                <div>
                    @php
                        $s = $pengajuan->status_pengajuan;
                        $pill = match(true) {
                            $s === 'selesai' || $s === 'sudah_diambil' => ['#ecfdf5','#059669','Selesai'],
                            $s === 'ditolak' || $s === 'perlu_revisi'  => ['#fef2f2','#dc2626','Revisi'],
                            $s === 'siap_diambil'                       => ['#eff6ff','#2563eb','Ambil'],
                            default                                     => ['#fffbeb','#d97706','Proses'],
                        };
                    @endphp
                    <span style="background:{{ $pill[0] }};color:{{ $pill[1] }};font-size:0.65rem;font-weight:700;padding:3px 9px;border-radius:20px;white-space:nowrap;">
                        {{ $pill[2] }}
                    </span>
                    <i class="bi bi-chevron-right ms-2" style="color:#cbd5e1;font-size:0.7rem;"></i>
                </div>
            </a>
            @endforeach
        @endif
    </div>

    {{-- Hubungi Admin --}}
    @if($linkWa)
    <div style="padding:0 1.25rem;margin-bottom:0.5rem;">
        <div class="m-contact-card">
            <div style="width:40px;height:40px;background:rgba(255,255,255,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-whatsapp" style="color:#4ade80;font-size:1.2rem;"></i>
            </div>
            <div class="m-contact-text">
                <h6>Butuh bantuan?</h6>
                <p>Hubungi admin prodi via WhatsApp</p>
            </div>
            <a href="{{ $linkWa }}" target="_blank"
               style="background:#25d366;color:#fff;font-size:0.75rem;font-weight:700;padding:8px 14px;border-radius:10px;text-decoration:none;white-space:nowrap;flex-shrink:0;">
                Chat
            </a>
        </div>
    </div>
    @endif

    {{-- Status guide --}}
    <div class="m-section-header" style="padding-bottom:0.25rem;">
        <span class="m-section-title" style="font-size:0.8rem;">Panduan Status</span>
    </div>
    <div class="m-guide-row">
        <div class="m-guide-pill">
            <div class="m-guide-dot" style="background:#f59e0b;"></div>
            <span style="color:#92400e;">Menunggu</span>
        </div>
        <div class="m-guide-pill">
            <div class="m-guide-dot" style="background:#3b82f6;"></div>
            <span style="color:#1d4ed8;">Diproses</span>
        </div>
        <div class="m-guide-pill">
            <div class="m-guide-dot" style="background:#10b981;"></div>
            <span style="color:#065f46;">Selesai</span>
        </div>
        <div class="m-guide-pill">
            <div class="m-guide-dot" style="background:#ef4444;"></div>
            <span style="color:#991b1b;">Ditolak</span>
        </div>
    </div>

    {{-- Spacer untuk bottom nav --}}
    <div style="height:1rem;"></div>

</div>{{-- /mobile --}}

@endsection