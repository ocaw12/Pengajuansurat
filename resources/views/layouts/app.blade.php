<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pengajuan Surat') - UP45</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Kustomisasi Tema Merah Sederhana -->
    <style>
        :root {
            /* Mengubah warna primer Bootstrap menjadi merah */
            --bs-primary: #dc3545;
            --bs-primary-rgb: 220, 53, 69;

            /* Mengubah warna tombol primer */
            --bs-btn-bg: var(--bs-primary);
            --bs-btn-border-color: var(--bs-primary);
            --bs-btn-hover-bg: #bb2d3b;
            --bs-btn-hover-border-color: #b02a37;
            --bs-btn-active-bg: #b02a37;
            --bs-btn-active-border-color: #a52834;
            --bs-btn-disabled-bg: var(--bs-primary);
            --bs-btn-disabled-border-color: var(--bs-primary);
        }

        /* Mengubah link aktif agar konsisten merah */
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            background-color: var(--bs-primary);
        }

        /* Mengubah warna teks primer */
        .text-primary {
            color: var(--bs-primary) !important;
        }
        
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <!-- Navigasi Utama -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <img src="https://placehold.co/40x40/ffffff/dc3545?text=UP45" width="30" height="30" class="d-inline-block align-top rounded-circle me-2" alt="">
                SISURAT UP45
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->email }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <!-- Form Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Navigasi -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-white shadow-sm sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column nav-pills">
                        
                        @if(Auth::user()->role->nama_role == 'mahasiswa')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}" href="{{ route('mahasiswa.dashboard') }}">
                                <i class="bi bi-clock-history me-2"></i> Riwayat Saya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mahasiswa.pengajuan.create') ? 'active' : '' }}" href="{{ route('mahasiswa.pengajuan.create') }}">
                                <i class="bi bi-file-earmark-plus me-2"></i> Buat Pengajuan Baru
                            </a>
                        </li>
                        
                        @elseif(Auth::user()->role->nama_role == 'staff jurusan')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('staff.validasi.index') ? 'active' : '' }}" href="{{ route('staff.validasi.index') }}">
                                <i class="bi bi-patch-check me-2"></i> Antrian Validasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('staff.validasi.cetak') ? 'active' : '' }}" href="{{ route('staff.validasi.cetak') }}">
                                <i class="bi bi-printer me-2"></i> Antrian Cetak
                            </a>
                        </li>

                        @elseif(Auth::user()->role->nama_role == 'pejabat')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pejabat.approval.index') ? 'active' : '' }}" href="{{ route('pejabat.approval.index') }}">
                                <i class="bi bi-pen me-2"></i> Antrian Approval
                            </a>
                        </li>
                        @endif

                    </ul>
                </div>
            </nav>

            <!-- Konten Utama -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title')</h1>
                </div>
                
                <!-- Menampilkan Alert (jika ada) -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')

                <footer class="pt-4 my-md-5 pt-md-5 border-top">
                    <div class="row">
                        <div class="col-12 text-center">
                            <small class="d-block mb-3 text-muted">&copy; 2025 Universitas Proklamasi 45</small>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script khusus per halaman (jika ada) -->
    @stack('scripts')
</body>
</html>
