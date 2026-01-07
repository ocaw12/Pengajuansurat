<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- CSRF Token untuk AJAX --}}
    <title>@yield('title', 'SISURAT UP45')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Custom CSS for Yellow Theme & Sidebar --}}
    <style>
        :root {
            --bs-primary: #ffc107; /* Warna Kuning Utama */
            --bs-primary-rgb: 255, 193, 7;
            --bs-primary-text-emphasis: #664d03;
            --bs-primary-bg-subtle: #fff3cd;
            --bs-primary-border-subtle: #ffe69c;
            --bs-link-color: #c79606; /* Warna link turunan dari kuning */
            --bs-link-color-rgb: 199, 150, 6;
            --bs-link-hover-color: #9d7705;
            --bs-link-hover-color-rgb: 157, 119, 5;
        }

        /* Mengganti warna utama Bootstrap */
        .btn-primary {
            --bs-btn-color: #000; /* Teks hitam agar kontras di tombol kuning */
            --bs-btn-bg: var(--bs-primary);
            --bs-btn-border-color: var(--bs-primary);
            --bs-btn-hover-color: #000;
            --bs-btn-hover-bg: #ffca2c;
            --bs-btn-hover-border-color: #ffc720;
            --bs-btn-focus-shadow-rgb: 66, 70, 73;
            --bs-btn-active-color: #000;
            --bs-btn-active-bg: #ffcd39;
            --bs-btn-active-border-color: #ffc720;
            --bs-btn-disabled-color: #000;
            --bs-btn-disabled-bg: var(--bs-primary);
            --bs-btn-disabled-border-color: var(--bs-primary);
        }

        .bg-primary {
             background-color: var(--bs-primary) !important;
        }
        .text-bg-primary {
            color: #000 !important; /* Teks hitam agar kontras */
            background-color: var(--bs-primary) !important;
        }
        .border-primary {
            border-color: var(--bs-primary) !important;
        }
        .text-primary {
            color: var(--bs-primary) !important;
        }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            color: #000; /* Teks hitam */
            background-color: var(--bs-primary);
        }

        /* Sidebar Styling */
        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #343a40; /* Sidebar gelap */
            color: #adb5bd;
            position: fixed; /* Fixed Sidebar */
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            padding-top: 56px; /* Space for navbar */
            transition: all 0.3s;
            overflow-y: auto; /* Allow sidebar scroll if menu long */
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px; /* Lebar ikon konsisten */
            text-align: center;
            font-size: 1.1rem; /* Slightly larger icons */
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            color: #000; /* Teks hitam di item aktif */
            background-color: var(--bs-primary);
            font-weight: 500;
        }
        .sidebar-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #495057;
            color: #fff;
            font-weight: 600;
            display: flex;
            align-items: center;
            position: fixed; /* Fix header position */
            top: 0;
            left: 0;
            width: 260px;
            height: 56px;
            background-color: #343a40;
            z-index: 101; /* Above sidebar content */
             transition: all 0.3s;
        }
        .sidebar-header img {
            height: 30px;
            margin-right: 0.75rem;
        }
        .sidebar .nav {
             padding-top: 1rem; /* Add padding below fixed header */
        }

        /* Main Content Area */
        .main-content {
            margin-left: 260px; /* Width of sidebar */
            padding-top: 76px; /* Space for navbar + sedikit padding */
            padding-bottom: 2rem;
            width: calc(100% - 260px);
            transition: all 0.3s;
        }

        /* Navbar Styling */
        .navbar {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            background-color: #fff;
            position: fixed; /* Fixed Navbar */
            top: 0;
            left: 260px; /* Width of sidebar */
            right: 0;
            z-index: 99;
            transition: all 0.3s;
        }

        /* Responsive: Hide sidebar text on smaller screens */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 70px; /* Sidebar jadi ikon saja */
            }
            .sidebar .nav-link span,
            .sidebar-header span {
                display: none;
            }
             .sidebar .nav-link i {
                 margin-right: 0; /* Hapus margin ikon */
                 font-size: 1.3rem; /* Make icons slightly larger when collapsed */
             }
             .sidebar-header {
                 width: 70px;
             }
             .sidebar-header img {
                 margin: 0 auto; /* Logo di tengah */
             }
            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
            .navbar {
                left: 70px;
            }
        }
        /* Responsive: Hide sidebar completely on very small screens */
        @media (max-width: 767.98px) {
             .sidebar {
                 width: 0;
                 overflow: hidden;
             }
              .sidebar-header {
                 width: 0;
             }
             .main-content {
                 margin-left: 0;
                 width: 100%;
             }
             .navbar {
                 left: 0;
             }
             /* Anda perlu menambahkan tombol toggle untuk sidebar offcanvas di sini jika mau */
             /* Example Button (place in navbar) */
             /*
             <button class="btn btn-outline-secondary d-md-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                 <i class="bi bi-list"></i>
             </button>
             */
             /* Then wrap the sidebar div with offcanvas classes */
             /*
             <div class="sidebar offcanvas offcanvas-start ..." tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
                 <div class="offcanvas-header"> ...close button... </div>
                 <div class="offcanvas-body p-0"> ... original sidebar content ... </div>
             </div>
             */
        }

        /* Style tambahan */
        .card-title i { vertical-align: middle; }
        .breadcrumb-item a { text-decoration: none; color: var(--bs-link-color); }
        .breadcrumb-item a:hover { color: var(--bs-link-hover-color); }
        .sidebar .nav-heading {
             font-size: 0.75rem;
             text-transform: uppercase;
             color: #6c757d;
             padding: 0.5rem 1.25rem;
             margin-top: 1rem;
        }

    </style>

    @stack('styles') {{-- Placeholder untuk CSS tambahan per halaman --}}

</head>
<body>

    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column flex-shrink-0 p-0 text-white bg-dark">
        {{-- Header Sidebar (Fixed) --}}
        <a href="{{ route('dashboard') }}" class="sidebar-header text-white text-decoration-none">
            {{-- Ganti 'logo.png' dengan path logo kampus Anda --}}
            <img src="{{ asset('images/logoup45.png') }}" alt="Logo UP45">
            <span class="fs-5">SISURAT UP45</span>
        </a>

        {{-- Menu Items (Scrollable) --}}
        <ul class="nav nav-pills flex-column mb-auto mt-0 px-2"> {{-- Removed mt-3 --}}

            @auth
                {{-- Menu Dinamis Berdasarkan Role --}}
                @php $role = Auth::user()->role->nama_role; @endphp

                @if($role === 'mahasiswa')
                    {{-- == MENU MAHASISWA == --}}
                    <li class="nav-heading"><span>Utama</span></li>

    <li class="nav-item">
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>
    </li>

  <li class="nav-item">
        <a href="{{ route('mahasiswa.riwayat.index') }}"
           class="nav-link {{ request()->routeIs('mahasiswa.riwayat.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i><span>Riwayat Pengajuan</span>
        </a>
    </li>
                                        </li>

                    <li class="nav-item">
                        <a href="{{ route('mahasiswa.pengajuan.create') }}" class="nav-link {{ request()->routeIs('mahasiswa.pengajuan.*') ? 'active' : '' }}"> {{-- Match create and show --}}
                            <i class="bi bi-file-earmark-plus"></i><span>Buat Pengajuan Baru</span>
                        </a>
                    </li>
                    

                @elseif($role === 'staff jurusan')
                    {{-- == MENU STAFF JURUSAN == --}}
                    </li>
                    <li class="nav-heading"><span>Utama</span></li>
                    <li class="nav-item">
                    <a href="{{ route('staff_jurusan.dashboard') }}" class="nav-link {{ request()->routeIs('staff_jurusan.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i><span>Dashboard</span>
                    </a>
                </li>
                 </li>
                    <li class="nav-heading"><span>Utama</span></li>
                    <li class="nav-item">
                    <a href="{{ route('staff_jurusan.validasi.index') }}" class="nav-link {{ request()->routeIs('staff_jurusan.validasi.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i><span>Antrian Validasi</span>
                    </a>
                </li>
                 </li>
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
    {{-- == MENU PEJABAT == --}}
    <li class="nav-heading"><span>Utama</span></li>

    <li class="nav-item">
        <a href="{{ route('pejabat.dashboard') }}"
           class="nav-link {{ request()->routeIs('pejabat.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>
    </li>


    {{-- Antrian Approval --}}
    <li class="nav-item">
        <a href="{{ route('pejabat.approval.antrian') }}"
           class="nav-link {{ request()->routeIs('pejabat.approval.antrian') ? 'active' : '' }}">
            <i class="bi bi-pen"></i><span>Antrian Approval</span>
        </a>
    </li>

    {{-- Riwayat Approval --}}
    <li class="nav-item">
        <a href="{{ route('pejabat.approval.riwayat') }}" 
           class="nav-link {{ request()->routeIs('pejabat.approval.riwayat') ? 'active' : '' }}">
            <i class="bi bi-list-check"></i>
            <span>Riwayat Approval</span>
        </a>
    </li>

    

                  @elseif($role === 'admin akademik')
            {{-- == MENU ADMIN AKADEMIK == --}}
            <li class="nav-heading"><span>Utama</span></li>
            <li class="nav-item">
                <a href="{{ route('admin_akademik.dashboard') }}" class="nav-link {{ request()->routeIs('admin_akademik.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="nav-heading"><span>Manajemen Surat</span></li>
            <li class="nav-item">
                <a href="{{ route('admin_akademik.jenis-surat.index') }}" class="nav-link {{ request()->routeIs('admin_akademik.jenis-surat.*') ? 'active' : '' }}">
                    <i class="bi bi-files"></i><span>Jenis Surat</span>
                </a>
            </li>
            <li class="nav-heading"><span>Manajemen Pengguna</span></li>
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
            <li class="nav-heading"><span>Data Master</span></li>
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

        {{-- Spacer agar menu bawah tidak tertutup jika sidebar scrollable --}}
         <div style="height: 50px;"></div>

    </div>

    {{-- Main Content Area --}}
    <div class="main-content">
        {{-- Navbar --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                {{-- Tombol Toggle Sidebar (untuk mobile nanti) --}}
                {{-- <button class="btn btn-primary d-md-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                    <i class="bi bi-list"></i>
                </button> --}}

                {{-- Breadcrumb atau Judul Halaman --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        {{-- Yield untuk breadcrumb tambahan --}}
                        @yield('breadcrumb')
                        <li class="breadcrumb-item active" aria-current="page">@yield('page-title', 'Dashboard')</li>
                    </ol>
                </nav>

                {{-- Navbar Kanan (User Dropdown) --}}
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->email ?? 'Guest' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                {{-- Form Logout --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- Konten Utama Halaman --}}
        <main class="container-fluid px-4 py-4">
            {{-- Judul Halaman Utama (jika berbeda dari breadcrumb) --}}
             {{-- <h1 class="h3 mb-4 text-gray-800">@yield('page-title', 'Dashboard')</h1> --}}

             {{-- Flash Message (jika ada) --}}
             @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
             @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content') {{-- Konten dinamis per halaman --}}
        </main>

         {{-- Footer (opsional) --}}
         <footer class="mt-auto px-4 py-3 border-top bg-light">
             <div class="text-center text-muted small">
                 &copy; {{ date('Y') }} Universitas Proklamasi 45 Yogyakarta. All rights reserved.
             </div>
         </footer>

    </div>

    {{-- Bootstrap Bundle JS (termasuk Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    @stack('scripts') {{-- Placeholder untuk JavaScript tambahan per halaman --}}
</body>
</html>

