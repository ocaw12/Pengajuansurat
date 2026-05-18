<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'SISURAT UP45')</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #ffc107;
            --sidebar-bg: #ffffff;
            --content-bg: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --transition-speed: 0.3s;
            --border-radius: 12px;
            --bottom-nav-h: 68px;
            --safe-bottom: env(safe-area-inset-bottom, 0px);
        }

        * { -webkit-tap-highlight-color: transparent; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--content-bg);
            display: flex;
            min-height: 100vh;
            color: var(--text-main);
            overscroll-behavior: none;
        }

        /* ── Sidebar (Desktop only) ── */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: all var(--transition-speed);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .sidebar-header img { height: 32px; margin-right: 12px; }
        .brand-text { font-weight: 800; font-size: 1.1rem; letter-spacing: -0.5px; color: var(--text-main); }
        .menu-wrapper { flex-grow: 1; overflow-y: auto; padding: 1rem 0; }
        .nav-heading {
            color: #94a3b8; font-size: 0.65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.2px;
            padding: 1.5rem 1.5rem 0.5rem;
        }
        .sidebar .nav-link {
            color: var(--text-muted);
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 1rem;
            border-radius: 10px;
            font-weight: 500; font-size: 0.9rem;
            transition: all 0.2s;
            display: flex; align-items: center;
        }
        .sidebar .nav-link i { font-size: 1.2rem; margin-right: 12px; color: #94a3b8; }
        .sidebar .nav-link:hover { color: var(--text-main); background: #f1f5f9; }
        .sidebar .nav-link.active {
            color: #92400e !important;
            background-color: #fef3c7 !important;
            font-weight: 700;
        }
        .sidebar .nav-link.active i { color: #d97706; }
        .sidebar-footer { padding: 1.25rem; border-top: 1px solid #f1f5f9; }
        .btn-logout-sidebar {
            width: 100%; background: #fff1f2; color: #e11d48;
            border: 1px solid #ffe4e6; padding: 0.6rem; border-radius: 10px;
            font-weight: 600; font-size: 0.85rem; transition: 0.2s;
        }
        .btn-logout-sidebar:hover { background: #e11d48; color: #fff; }

        /* ── Main Content ── */
        .main-content {
            margin-left: 280px;
            flex-grow: 1;
            width: calc(100% - 280px);
            transition: all var(--transition-speed);
            display: flex; flex-direction: column;
        }

        /* ── Navbar ── */
        .navbar {
            height: 70px;
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 999;
        }
        .profile-pill {
            background: #ffffff; border: 1px solid #e2e8f0;
            padding: 5px 15px 5px 6px; border-radius: 50px;
            display: flex; align-items: center; transition: all 0.2s;
        }
        .profile-pill:hover { background: #f8fafc; }
        .avatar-circle {
            width: 32px; height: 32px;
            background: var(--bs-primary); color: #000;
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            font-weight: 800; font-size: 0.75rem; margin-right: 10px;
        }

        /* ── Scrollbar ── */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        /* ── Page transition ── */
        .page-fade { animation: pageFadeIn 0.22s ease; }
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ══════════════════════════════════════════
           MOBILE — Native App Experience (<768px)
        ══════════════════════════════════════════ */
        @media (max-width: 767.98px) {
            body { background: #f0f4f8; }

            /* Hide desktop sidebar */
            .sidebar { display: none !important; }

            /* Main fills screen, bottom padding for nav */
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding-bottom: calc(var(--bottom-nav-h) + var(--safe-bottom));
            }

            /* Mobile top bar — compact */
            .navbar {
                height: 58px;
                padding: 0 1rem;
                border-radius: 0;
                background: #ffffff !important;
                border-bottom: 1px solid #e8edf2 !important;
            }
            .navbar .breadcrumb { display: none !important; }

            /* Mobile avatar pill — stripped down */
            .profile-pill {
                padding: 4px 10px 4px 5px;
                border-radius: 40px;
                border-color: #f1f5f9;
            }
            .avatar-circle { width: 28px; height: 28px; margin-right: 7px; font-size: 0.7rem; }

            /* Content area — no top padding, cards go edge-to-edge */
            main.container-fluid {
                padding: 0 !important;
                padding-top: 0 !important;
            }

            /* Alert on mobile */
            .alert {
                border-radius: 0 !important;
                margin-bottom: 0 !important;
                border-left: none !important;
                border-right: none !important;
                font-size: 0.875rem;
            }

            /* ── Bottom Navigation Bar ── */
            .bottom-nav {
                display: flex !important;
                position: fixed;
                bottom: 0; left: 0; right: 0;
                height: calc(var(--bottom-nav-h) + var(--safe-bottom));
                padding-bottom: var(--safe-bottom);
                background: rgba(255,255,255,0.96);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-top: 0.5px solid #e2e8f0;
                z-index: 1050;
                box-shadow: 0 -4px 24px rgba(0,0,0,0.06);
            }
            .bottom-nav-item {
                flex: 1;
                display: flex; flex-direction: column;
                align-items: center; justify-content: center;
                gap: 3px;
                padding: 8px 4px;
                color: #94a3b8;
                text-decoration: none;
                font-size: 0.6rem;
                font-weight: 600;
                letter-spacing: 0.3px;
                text-transform: uppercase;
                transition: color 0.15s;
                position: relative;
                -webkit-tap-highlight-color: transparent;
            }
            .bottom-nav-item i {
                font-size: 1.3rem;
                transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.15s;
            }
            .bottom-nav-item.active { color: #d97706; }
            .bottom-nav-item.active i {
                transform: translateY(-2px) scale(1.08);
                color: #d97706;
            }
            .bottom-nav-item.active::before {
                content: '';
                position: absolute;
                top: 0; left: 50%;
                transform: translateX(-50%);
                width: 32px; height: 3px;
                background: #fbbf24;
                border-radius: 0 0 3px 3px;
            }

            /* CTA button in nav */
            .bottom-nav-cta {
                flex: 0 0 68px;
                display: flex; flex-direction: column;
                align-items: center; justify-content: center;
                padding-bottom: 4px;
            }
            .bottom-nav-cta-btn {
                width: 48px; height: 48px;
                background: linear-gradient(135deg, #fbbf24, #f59e0b);
                border-radius: 16px;
                display: flex; align-items: center; justify-content: center;
                color: #fff;
                box-shadow: 0 4px 12px rgba(245,158,11,0.35);
                transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s;
            }
            .bottom-nav-cta-btn:active {
                transform: scale(0.92);
                box-shadow: 0 2px 6px rgba(245,158,11,0.25);
            }
            .bottom-nav-cta-btn i { font-size: 1.2rem; }
            .bottom-nav-cta span {
                font-size: 0.58rem;
                font-weight: 700;
                color: #d97706;
                margin-top: 3px;
                letter-spacing: 0.3px;
                text-transform: uppercase;
            }

            /* ── Mobile page sections ── */
            .mobile-section {
                padding: 0;
            }

            /* Mobile hero/welcome strip */
            .mobile-welcome-strip {
                background: #fff;
                padding: 1.1rem 1.25rem 1rem;
                border-bottom: 1px solid #f1f5f9;
            }

            /* Cards on mobile — rounded, with margin */
            .card {
                border-radius: 16px !important;
                border: none !important;
            }

            /* Stat cards on mobile */
            .stat-card-mobile {
                background: #fff;
                border-radius: 16px;
                padding: 1rem 1.1rem;
                display: flex;
                align-items: center;
                gap: 0.9rem;
            }
            .stat-icon-mobile {
                width: 44px; height: 44px;
                border-radius: 12px;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }

            /* Table on mobile → card list */
            .mobile-list-item {
                background: #fff;
                padding: 1rem 1.25rem;
                border-bottom: 1px solid #f8fafc;
                display: flex;
                align-items: center;
                gap: 0.8rem;
                text-decoration: none;
                color: inherit;
                transition: background 0.15s;
                -webkit-tap-highlight-color: transparent;
            }
            .mobile-list-item:active { background: #f8fafc; }
            .mobile-list-avatar {
                width: 40px; height: 40px; border-radius: 12px;
                background: #eff6ff; display: flex;
                align-items: center; justify-content: center;
                flex-shrink: 0;
            }
            .mobile-list-chevron {
                margin-left: auto;
                color: #cbd5e1;
                font-size: 0.85rem;
            }

            /* Mobile section header */
            .mobile-section-header {
                padding: 1.25rem 1.25rem 0.6rem;
                display: flex; align-items: center; justify-content: space-between;
                background: transparent;
            }
            .mobile-section-title {
                font-size: 0.95rem;
                font-weight: 700;
                color: var(--text-main);
            }
            .mobile-section-action {
                font-size: 0.8rem;
                font-weight: 600;
                color: #d97706;
                text-decoration: none;
            }

            /* Hide desktop-only elements */
            .d-desktop-only { display: none !important; }

            /* Full width card on mobile */
            .mobile-full-card {
                border-radius: 16px;
                overflow: hidden;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            }

            /* Floating FAB - only if NOT mahasiswa (mahasiswa uses bottom nav CTA) */
            .fab-mobile {
                position: fixed;
                bottom: calc(var(--bottom-nav-h) + var(--safe-bottom) + 16px);
                right: 20px;
                z-index: 1040;
            }

            /* Footer hidden on mobile */
            footer { display: none !important; }

            /* ── Sidebar overlay ketika .show (mobile) ── */
            .sidebar.show {
                display: flex !important;
                transform: translateX(0) !important;
                width: 280px !important;
                box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            }
            /* Overlay backdrop */
            .sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.35);
                z-index: 999;
                backdrop-filter: blur(2px);
            }
            .sidebar-backdrop.show { display: block; }
        }

        /* ── Tablet ── */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .sidebar { width: 80px; }
            .sidebar .nav-link span, .brand-text, .nav-heading { display: none; }
            .sidebar .nav-link { justify-content: center; margin: 0.5rem; }
            .sidebar .nav-link i { margin-right: 0; }
            .main-content { margin-left: 80px; width: calc(100% - 80px); }
            .bottom-nav { display: none !important; }
        }

        /* ── Desktop ── */
        @media (min-width: 992px) {
            .bottom-nav { display: none !important; }
        }
    </style>
    @stack('styles')
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body>

    {{-- ─────── DESKTOP SIDEBAR ─────── --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logoup45.png') }}" alt="Logo">
            <span class="brand-text">SISURAT <span class="text-warning">UP45</span></span>
        </div>

        <div class="menu-wrapper custom-scrollbar">
            <ul class="nav flex-column">
                @auth
                    @php $role = Auth::user()->role->nama_role; @endphp

                    @if($role === 'mahasiswa')
                        <li class="nav-heading">Utama</li>
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.riwayat.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.riwayat.*') ? 'active' : '' }}">
                                <i class="bi bi-clock-history"></i><span>Riwayat Pengajuan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.pengajuan.create') }}" class="nav-link {{ request()->routeIs('mahasiswa.pengajuan.create') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-plus"></i><span>Buat Pengajuan Baru</span>
                            </a>
                        </li>

                    @elseif($role === 'staff jurusan')
                        <li class="nav-heading">Utama</li>
                        <li class="nav-item">
                            <a href="{{ route('staff_jurusan.dashboard') }}" class="nav-link {{ request()->routeIs('staff_jurusan.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-heading">Validasi</li>
                        <li class="nav-item">
                            <a href="{{ route('staff_jurusan.validasi.index') }}" class="nav-link {{ request()->routeIs('staff_jurusan.validasi.index') || request()->routeIs('staff_jurusan.validasi.show') ? 'active' : '' }}">
                                <i class="bi bi-clipboard-check"></i><span>Antrian Validasi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('staff_jurusan.validasi.riwayat') }}" class="nav-link {{ request()->routeIs('staff_jurusan.validasi.riwayat') || request()->routeIs('staff_jurusan.validasi.detailRiwayat') ? 'active' : '' }}">
                                <i class="bi bi-clock-history"></i><span>Riwayat Validasi</span>
                            </a>
                        </li>
                        <li class="nav-heading">Pencetakan</li>
                        <li class="nav-item">
                            <a href="{{ route('staff_jurusan.cetak.index') }}" class="nav-link {{ request()->routeIs('staff_jurusan.cetak.index') ? 'active' : '' }}">
                                <i class="bi bi-printer"></i><span>Perlu Dicetak</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('staff_jurusan.cetak.pengambilan') }}" class="nav-link {{ request()->routeIs('staff_jurusan.cetak.pengambilan') ? 'active' : '' }}">
                                <i class="bi bi-person-check"></i><span>Antrian Pengambilan</span>
                            </a>
                        </li>

                    @elseif($role === 'pejabat')
                        <li class="nav-heading">Utama</li>
                        <li class="nav-item">
                            <a href="{{ route('pejabat.dashboard') }}" class="nav-link {{ request()->routeIs('pejabat.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-heading">Persetujuan</li>
                        <li class="nav-item">
                            <a href="{{ route('pejabat.approval.antrian') }}" class="nav-link {{ request()->routeIs('pejabat.approval.antrian') ? 'active' : '' }}">
                                <i class="bi bi-pen"></i><span>Antrian Approval</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pejabat.approval.riwayat') }}" class="nav-link {{ request()->routeIs('pejabat.approval.riwayat') ? 'active' : '' }}">
                                <i class="bi bi-list-check"></i><span>Riwayat Approval</span>
                            </a>
                        </li>

                    @elseif($role === 'admin akademik')
                        <li class="nav-heading">Utama</li>
                        <li class="nav-item">
                            <a href="{{ route('admin_akademik.dashboard') }}" class="nav-link {{ request()->routeIs('admin_akademik.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-heading">Manajemen Surat</li>
                        <li class="nav-item">
                            <a href="{{ route('admin_akademik.jenis-surat.index') }}" class="nav-link {{ request()->routeIs('admin_akademik.jenis-surat.*') ? 'active' : '' }}">
                                <i class="bi bi-files"></i><span>Jenis Surat</span>
                            </a>
                        </li>
                        <li class="nav-heading">Pengguna</li>
                        <li class="nav-item">
                            <a href="{{ route('admin_akademik.pejabat.index') }}" class="nav-link {{ request()->routeIs('admin_akademik.pejabat.*') ? 'active' : '' }}">
                                <i class="bi bi-person-badge"></i><span>Manajemen Pejabat</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin_akademik.admin-staff.index') }}" class="nav-link {{ request()->routeIs('admin_akademik.admin-staff.*') ? 'active' : '' }}">
                                <i class="bi bi-person-workspace"></i><span>Manajemen Staff</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin_akademik.mahasiswa.index') }}" class="nav-link {{ request()->routeIs('admin_akademik.mahasiswa.*') ? 'active' : '' }}">
                                <i class="bi bi-person-vcard"></i><span>Manajemen Mahasiswa</span>
                            </a>
                        </li>
                        <li class="nav-heading">Data Master</li>
                        <li class="nav-item">
                            <a href="{{ route('admin_akademik.fakultas.index') }}" class="nav-link {{ request()->routeIs('admin_akademik.fakultas.*') ? 'active' : '' }}">
                                <i class="bi bi-building"></i><span>Fakultas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin_akademik.prodi.index') }}" class="nav-link {{ request()->routeIs('admin_akademik.prodi.*') ? 'active' : '' }}">
                                <i class="bi bi-diagram-3"></i><span>Program Studi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin_akademik.master-jabatan.index') }}" class="nav-link {{ request()->routeIs('admin_akademik.master-jabatan.*') ? 'active' : '' }}">
                                <i class="bi bi-briefcase"></i><span>Master Jabatan</span>
                            </a>
                        </li>
                        <li class="nav-item">
    <a href="{{ route('admin_akademik.tahun-ajaran.index') }}" 
       class="nav-link {{ request()->routeIs('admin_akademik.tahun-ajaran.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-range"></i>
        <span>Tahun Ajaran</span>
    </a>
</li>
                    @endif
                @endauth
            </ul>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout-sidebar border-0 shadow-none">
                    <i class="bi bi-box-arrow-left me-2"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ─────── MAIN CONTENT ─────── --}}
    <div class="main-content">

        {{-- Top Navbar --}}
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                {{-- Mobile: hamburger + brand --}}
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-light border d-lg-none me-1" id="sidebarToggleBtn"
                            style="border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-list" id="sidebarToggleIcon"></i>
                    </button>
                    {{-- Mobile brand --}}
                    <span class="d-md-none fw-800" style="font-weight:800;font-size:1rem;letter-spacing:-0.5px;">SISURAT <span style="color:#f59e0b">UP45</span></span>
                </div>

                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Portal</a></li>
                        <li class="breadcrumb-item active text-dark fw-bold">@yield('page-title', 'Dashboard')</li>
                    </ol>
                </nav>

                <div class="ms-auto dropdown">
    @auth
    <div class="profile-pill dropdown-toggle" role="button" data-bs-toggle="dropdown">
        <div class="avatar-circle">
            {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
        </div>
        <div class="text-start d-none d-sm-block">
            <div class="fw-bold leading-none small text-dark" style="line-height: 1;">{{ explode('@', Auth::user()->email)[0] }}</div>
            <div class="text-muted" style="font-size: 0.65rem;">{{ Auth::user()->role->nama_role }}</div>
        </div>
    </div>
    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3" style="border-radius: 15px; min-width: 200px;">
        <li class="px-3 py-2 d-sm-none border-bottom mb-2">
            <span class="fw-bold small">{{ Auth::user()->email }}</span>
        </li>
        <li><a class="dropdown-item rounded-3 py-2 mx-2 w-auto" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
        <li><hr class="dropdown-divider mx-2"></li>
        <li>
           <form method="POST" action="{{ route('logout') }}" id="logoutFormDropdown">
    @csrf
    <button type="submit" class="dropdown-item rounded-3 py-2 mx-2 w-auto text-danger" id="logoutBtnDropdown">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </button>
</form>
        </li>
    </ul>
    @endauth
</div>
            </div>
        </nav>

        {{-- Main content --}}
        <main class="container-fluid p-4 page-fade">

            {{-- Success/Error alerts --}}
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="border-radius: 12px; background: #ecfdf5; color: #065f46;">
                    <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="border-radius: 12px; background: #fef2f2; color: #991b1b;">
                    <i class="bi bi-exclamation-circle-fill me-3 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="py-3 px-4 bg-white border-top mt-auto">
            <div class="d-flex justify-content-between align-items-center small text-muted">
                <span>&copy; {{ date('Y') }} Universitas Proklamasi 45</span>
                <span class="d-none d-md-inline fw-medium">SISURAT v2.0</span>
            </div>
        </footer>
    </div>

    {{-- ─────── MOBILE BOTTOM NAVIGATION (Mahasiswa only) ─────── --}}
    @auth
        @php $roleNav = Auth::user()->role->nama_role; @endphp
        @if($roleNav === 'mahasiswa')
        <nav class="bottom-nav" id="bottomNav" aria-label="Navigasi utama">
            <a href="{{ route('mahasiswa.dashboard') }}"
               class="bottom-nav-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                <i class="bi {{ request()->routeIs('mahasiswa.dashboard') ? 'bi-house-fill' : 'bi-house' }}"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('mahasiswa.riwayat.index') }}"
               class="bottom-nav-item {{ request()->routeIs('mahasiswa.riwayat.*') ? 'active' : '' }}">
                <i class="bi {{ request()->routeIs('mahasiswa.riwayat.*') ? 'bi-clock-history' : 'bi-clock' }}"></i>
                <span>Riwayat</span>
            </a>

            {{-- Center CTA --}}
            <div class="bottom-nav-cta">
                <a href="{{ route('mahasiswa.pengajuan.create') }}" class="bottom-nav-cta-btn" id="fabCreate">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <span>Ajukan</span>
            </div>

            <a href="{{ route('profile') }}"
               class="bottom-nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="bi {{ request()->routeIs('profile') ? 'bi-person-fill' : 'bi-person' }}"></i>
                <span>Profil</span>
            </a>

           <form method="POST" action="{{ route('logout') }}" id="logoutFormMobile" class="bottom-nav-item p-0 border-0 bg-transparent" style="cursor:pointer;">
    @csrf
    <button type="submit" class="bottom-nav-item border-0 bg-transparent w-100 h-100" id="logoutBtnMobile" style="flex:1;">
        <i class="bi bi-box-arrow-right"></i>
        <span>Keluar</span>
    </button>
</form>
        </nav>
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // FAB press animation
        const fab = document.getElementById('fabCreate');
        if (fab) {
            fab.addEventListener('touchstart', () => fab.style.transform = 'scale(0.9)', {passive:true});
            fab.addEventListener('touchend', () => fab.style.transform = '', {passive:true});
        }

        // Haptic feedback simulation (Android vibrate API)
        document.querySelectorAll('.bottom-nav-item').forEach(el => {
            el.addEventListener('click', () => {
                if (navigator.vibrate) navigator.vibrate(8);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function confirmLogout(formId) {
        const form = document.getElementById(formId);

        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Yakin Ingin Keluar?',
                text: "Kamu akan keluar dari sistem",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }

    // ====== semua logout di layout kamu ======
    confirmLogout('logoutFormDropdown'); // navbar dropdown
    confirmLogout('logoutForm');         // sidebar
    confirmLogout('logoutFormMobile');   // bottom nav (mobile)

});
</script>
    @stack('scripts')
</body>
</html>