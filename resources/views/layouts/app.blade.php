<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SISURAT UP45')</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- Google Fonts - Plus Jakarta Sans --}}
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
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--content-bg);
            display: flex;
            min-height: 100vh;
            color: var(--text-main);
        }

        /* --- Sidebar Modern Clean --- */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
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

        .sidebar-header img {
            height: 32px;
            margin-right: 12px;
        }

        .brand-text {
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.5px;
            color: var(--text-main);
        }

        /* Menu Scrollable */
        .menu-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .nav-heading {
            color: #94a3b8;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 1.5rem 1.5rem 0.5rem;
        }

        .sidebar .nav-link {
            color: var(--text-muted);
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 1rem;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
            color: #94a3b8;
        }

        .sidebar .nav-link:hover {
            color: var(--text-main);
            background: #f1f5f9;
        }

        /* Active Menu Styling - Amber Soft Theme */
        .sidebar .nav-link.active {
            color: #92400e !important; /* Brown-Amber Dark */
            background-color: #fef3c7 !important; /* Amber Light */
            font-weight: 700;
        }

        .sidebar .nav-link.active i {
            color: #d97706; /* Amber Dark */
        }

        /* --- Navbar Putih Modern --- */
        .main-content {
            margin-left: 280px;
            flex-grow: 1;
            width: calc(100% - 280px);
            transition: all var(--transition-speed);
            display: flex;
            flex-direction: column;
        }

        .navbar {
            height: 70px;
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* User Profile Style */
        .profile-pill {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 5px 15px 5px 6px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        .profile-pill:hover { background: #f8fafc; }

        .avatar-circle {
            width: 32px;
            height: 32px;
            background: var(--bs-primary);
            color: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.75rem;
            margin-right: 10px;
        }

        /* Footer Sidebar */
        .sidebar-footer {
            padding: 1.25rem;
            border-top: 1px solid #f1f5f9;
        }

        .btn-logout-sidebar {
            width: 100%;
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #ffe4e6;
            padding: 0.6rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: 0.2s;
        }
        .btn-logout-sidebar:hover { background: #e11d48; color: #fff; }

        /* Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        /* --- Responsive --- */
        @media (max-width: 992px) {
            .sidebar { width: 80px; }
            .sidebar .nav-link span, .brand-text, .nav-heading { display: none; }
            .sidebar .nav-link { justify-content: center; margin: 0.5rem; }
            .sidebar .nav-link i { margin-right: 0; }
            .main-content { margin-left: 80px; width: calc(100% - 80px); }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; width: 100%; }
            .sidebar.show { transform: translateX(0); width: 280px; }
            .sidebar.show span, .sidebar.show .brand-text, .sidebar.show .nav-heading { display: inline; }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logoup45.png') }}" alt="Logo">
            <span class="brand-text">SISURAT <span class="text-warning">UP45</span></span>
        </div>

        <div class="menu-wrapper custom-scrollbar">
            <ul class="nav flex-column">
                @auth
                    @php $role = Auth::user()->role->nama_role; @endphp

                    {{-- Dinamis Menu Berdasarkan Role --}}
                    <!-- <li class="nav-heading">Utama</li>
                    <li class="nav-item">
                        <a href="{{ route($role == 'mahasiswa' ? 'mahasiswa.dashboard' : ($role == 'pejabat' ? 'pejabat.dashboard' : ($role == 'staff jurusan' ? 'staff_jurusan.dashboard' : 'admin_akademik.dashboard'))) }}" 
                           class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
                        </a>
                    </li> -->

                    {{-- == MENU MAHASISWA == --}}

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

                <a href="{{ route('mahasiswa.pengajuan.create') }}" class="nav-link {{ request()->routeIs('mahasiswa.pengajuan.*') ? 'active' : '' }}">

                    <i class="bi bi-file-earmark-plus"></i><span>Buat Pengajuan Baru</span>

                </a>

            </li>



        {{-- == MENU STAFF JURUSAN == --}}

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



        {{-- == MENU PEJABAT == --}}

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



        {{-- == MENU ADMIN AKADEMIK == --}}

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

    {{-- Main Content --}}
    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <button class="btn btn-light border d-lg-none me-3" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>

                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Portal</a></li>
                        <li class="breadcrumb-item active text-dark fw-bold">@yield('page-title', 'Dashboard')</li>
                    </ol>
                </nav>

                <div class="ms-auto dropdown">
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
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 mx-2 w-auto text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container-fluid p-4">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="border-radius: 12px; background: #ecfdf5; color: #065f46;">
                    <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                    <div>{{ session('success') }}</div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>