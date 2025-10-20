@extends('layouts.app')

@section('content')
<div x-data="{ masterOpen: true, suratOpen: false }" class="min-h-screen flex bg-gray-50">
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
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-6 hover:bg-amber-200 transition rounded-r-full">🏠 Dashboard</a>

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
                    <a href="#" class="block py-2 hover:text-gray-900 transition">👩‍💼 Data Staf Jurusan</a>
                    <a href="#" class="block py-2 hover:text-gray-900 transition">🧾 Pejabat Berwenang</a>
                    <a href="{{ route('fakultas.index') }}" class="block py-2 text-amber-700 font-semibold bg-amber-200 rounded-r-full">🏛️ Data Fakultas</a>
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
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Tambah Fakultas</h1>

        <form action="{{ route('fakultas.store') }}" method="POST" class="space-y-4 bg-white shadow-md rounded-lg p-6 border border-gray-200">
            @csrf
            <div>
                <label class="block font-medium text-gray-700">ID Fakultas</label>
                <input type="text" name="id_fakultas" class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-amber-300 focus:outline-none" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Nama Fakultas</label>
                <input type="text" name="nama_fakultas" class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-amber-300 focus:outline-none" required>
            </div>

            <div class="flex items-center space-x-3">
                <button type="submit" class="bg-amber-400 px-4 py-2 rounded text-gray-900 font-semibold hover:bg-amber-500 transition">
                    💾 Simpan
                </button>
                <a href="{{ route('fakultas.index') }}" class="text-gray-600 hover:underline">← Kembali</a>
            </div>
        </form>
    </main>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
