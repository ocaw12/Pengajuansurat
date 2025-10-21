@extends('layouts.app')

@section('content')
<div x-data="{ masterOpen: false, suratOpen: false }" class="min-h-screen flex bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-amber-100 text-gray-800 flex flex-col justify-between shadow-lg border-r border-amber-200">
        <div>
            <!-- Header dengan logo -->
            <div class="p-6 text-center border-b border-amber-200 bg-amber-50">
                <div class="flex justify-center mb-3">
                    <img src="{{ asset('images/logoup45.png') }}" 
                         alt="Logo Universitas 45" 
                         class="w-16 h-16 rounded-full shadow-md border border-amber-300 bg-white">
                </div>
                <h1 class="text-lg font-extrabold leading-tight text-gray-900 tracking-wide">SILASMA</h1>
                <p class="text-xs text-gray-700 mt-1 font-medium">Sistem Informasi Layanan Pengajuan Surat Mahasiswa</p>
            </div>

            <!-- Menu -->
            <nav class="mt-6 font-medium">
                <a href="#" class="block py-2.5 px-6 hover:bg-amber-200 transition rounded-r-full">🏠 Dashboard</a>

                <!-- Dropdown: Data Master -->
                <button @click="masterOpen = !masterOpen" 
                        class="w-full text-left py-2.5 px-6 flex justify-between items-center hover:bg-amber-200 transition rounded-r-full">
                    <span>📚 Data Master</span>
                    <svg :class="{'rotate-180': masterOpen}" 
                         class="w-4 h-4 transform transition-transform duration-300" 
                         fill="none" stroke="currentColor" stroke-width="2" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="masterOpen" x-collapse class="pl-10 text-sm space-y-1 mt-1 text-gray-700">
                    <a href="#" class="block py-2 hover:text-gray-900 transition">🎓 Data Mahasiswa</a>
                    <a href="{{ route('staff.index') }}" class="block py-2 hover:text-gray-900 transition">👩‍💼 Data Staf Jurusan</a>
                    <a href="#" class="block py-2 hover:text-gray-900 transition">🧾 Pejabat Berwenang</a>
                    <a href="{{ route('fakultas.index') }}" class="block py-2 hover:text-gray-900 transition">🏛️ Data Fakultas</a>
                    <a href="{{ route('prodi.index') }}" class="block py-2 hover:text-gray-900 transition">📗 Data Prodi</a>
                </div>

                <!-- Dropdown: Manajemen Surat -->
                <button @click="suratOpen = !suratOpen" 
                        class="w-full text-left py-2.5 px-6 flex justify-between items-center hover:bg-amber-200 transition rounded-r-full mt-2">
                    <span>📄 Manajemen Surat</span>
                    <svg :class="{'rotate-180': suratOpen}" 
                         class="w-4 h-4 transform transition-transform duration-300" 
                         fill="none" stroke="currentColor" stroke-width="2" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="suratOpen" x-collapse class="pl-10 text-sm space-y-1 mt-1 text-gray-700">
                    <a href="#" class="block py-2 hover:text-gray-900 transition">📝 Jenis Surat</a>
                    <a href="#" class="block py-2 hover:text-gray-900 transition">📁 Kategori Surat</a>
                    <a href="#" class="block py-2 hover:text-gray-900 transition">📬 Data Pengajuan Surat</a>
                    <a href="#" class="block py-2 hover:text-gray-900 transition">📂 Arsip Surat</a>
                </div>
            </nav>
        </div>

        <!-- Profil & Logout -->
        <div class="p-4 border-t border-amber-200 bg-amber-50">
            <div class="flex items-center space-x-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=FCD34D&color=000" 
                     alt="Avatar" class="w-10 h-10 rounded-full ring-2 ring-amber-300">
                <div>
                    <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-700">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" 
                        class="w-full text-left bg-amber-300 hover:bg-amber-200 text-gray-900 font-semibold px-4 py-2 rounded-lg transition">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 p-8">
        <!-- Header Dashboard -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
                <p class="text-gray-500">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
            </div>
            <div>
                <span class="bg-amber-100 text-amber-700 px-4 py-2 rounded-lg text-sm font-medium">
                    Admin Akademik
                </span>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                <div class="bg-amber-100 text-amber-600 p-3 rounded-full mr-4">🎓</div>
                <div>
                    <h3 class="text-gray-700 font-semibold">Total Mahasiswa</h3>
                    <p class="text-2xl font-bold text-amber-700">1.250</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                <div class="bg-green-100 text-green-600 p-3 rounded-full mr-4">📄</div>
                <div>
                    <h3 class="text-gray-700 font-semibold">Surat Diproses</h3>
                    <p class="text-2xl font-bold text-green-700">89</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full mr-4">📬</div>
                <div>
                    <h3 class="text-gray-700 font-semibold">Surat Selesai</h3>
                    <p class="text-2xl font-bold text-yellow-700">215</p>
                </div>
            </div>
        </div>

        <!-- Seksi Arsip -->
        <div class="mt-10 bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">📂 Rekap Arsip Surat</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                    <p class="text-gray-700 font-medium">Total Arsip</p>
                    <p class="text-3xl font-bold text-amber-700 mt-1">1.203</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <p class="text-gray-700 font-medium">Arsip Bulan Ini</p>
                    <p class="text-3xl font-bold text-green-700 mt-1">87</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <p class="text-gray-700 font-medium">Prodi Aktif</p>
                    <p class="text-3xl font-bold text-blue-700 mt-1">12</p>
                </div>
            </div>

            <div class="text-right mt-4">
                <a href="#" class="text-amber-700 font-semibold hover:text-amber-600">➡️ Lihat Semua Arsip</a>
            </div>
        </div>

        <!-- Seksi Informasi -->
        <div class="mt-10 bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2 text-gray-800">Informasi Sistem</h2>
            <p class="text-gray-600 leading-relaxed">
                Dashboard ini berfungsi sebagai pusat kendali bagi Admin Akademik untuk mengelola seluruh data terkait
                mahasiswa, staf, pejabat berwenang, hingga surat dan kategorinya. 
                Admin dapat memantau status pengajuan dan arsip surat, 
                serta memastikan proses layanan berjalan dengan efisien dan transparan.
            </p>
            <div class="mt-4">
                <p class="text-sm text-gray-500">📅 Terakhir login: {{ now()->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </main>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
