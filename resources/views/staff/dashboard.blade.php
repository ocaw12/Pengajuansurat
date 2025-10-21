@extends('layouts.app')

@section('content')
<div x-data="{ masterOpen: false, suratOpen: true }" class="min-h-screen flex bg-gray-50">
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
                <p class="text-xs text-gray-700 mt-1 font-medium">Sistem Informasi Pengajuan Surat Mahasiswa</p>
            </div>

            <!-- Menu -->
            <nav class="mt-6 font-medium">
                <a href="{{ route('staff.dashboard') }}" class="block py-2.5 px-6 bg-amber-200 text-amber-900 font-semibold rounded-r-full">🏠 Dashboard</a>

                <!-- Dropdown: Manajemen Surat -->
                <button @click="suratOpen = !suratOpen" 
                        class="w-full text-left py-2.5 px-6 flex justify-between items-center hover:bg-amber-200 transition rounded-r-full mt-2">
                    <span>📄 Pengelolaan Surat</span>
                    <svg :class="{'rotate-180': suratOpen}" 
                         class="w-4 h-4 transform transition-transform duration-300" 
                         fill="none" stroke="currentColor" stroke-width="2" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="suratOpen" x-collapse class="pl-10 text-sm space-y-1 mt-1 text-gray-700">
                    <a href="#" class="block py-2 hover:text-gray-900 transition">📬 Surat Masuk</a>
                    <a href="#" class="block py-2 hover:text-gray-900 transition">🕒 Surat Diproses</a>
                    <a href="#" class="block py-2 hover:text-gray-900 transition">📁 Arsip Surat</a>
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
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Staff Jurusan</h1>
                <p class="text-gray-500">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
            </div>
            <div>
                <span class="bg-amber-100 text-amber-700 px-4 py-2 rounded-lg text-sm font-medium">
                    Staf Jurusan
                </span>
            </div>
        </div>

        <!-- Statistik Surat -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">📬</div>
                <div>
                    <h3 class="text-gray-700 font-semibold">Surat Masuk</h3>
                    <p class="text-2xl font-bold text-blue-700">42</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full mr-4">🔄</div>
                <div>
                    <h3 class="text-gray-700 font-semibold">Sedang Diproses</h3>
                    <p class="text-2xl font-bold text-yellow-700">15</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                <div class="bg-green-100 text-green-600 p-3 rounded-full mr-4">✅</div>
                <div>
                    <h3 class="text-gray-700 font-semibold">Selesai Diverifikasi</h3>
                    <p class="text-2xl font-bold text-green-700">127</p>
                </div>
            </div>
        </div>

        <!-- Daftar Surat Masuk -->
        <div class="mt-10 bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">📄 Daftar Surat Masuk</h2>

            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead class="bg-amber-100">
                    <tr>
                        <th class="border p-2">No</th>
                        <th class="border p-2">Nama Mahasiswa</th>
                        <th class="border p-2">Jenis Surat</th>
                        <th class="border p-2">Tanggal Pengajuan</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-amber-50">
                        <td class="border p-2 text-center">1</td>
                        <td class="border p-2">Ahmad Rafi</td>
                        <td class="border p-2">Surat Aktif Kuliah</td>
                        <td class="border p-2 text-center">20 Okt 2025</td>
                        <td class="border p-2 text-center text-blue-600 font-medium">Menunggu Verifikasi</td>
                        <td class="border p-2 text-center">
                            <a href="#" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs">Lihat</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-amber-50">
                        <td class="border p-2 text-center">2</td>
                        <td class="border p-2">Siti Nurlaila</td>
                        <td class="border p-2">Surat Cuti Kuliah</td>
                        <td class="border p-2 text-center">18 Okt 2025</td>
                        <td class="border p-2 text-center text-yellow-600 font-medium">Diproses</td>
                        <td class="border p-2 text-center">
                            <a href="#" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-xs">Proses</a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="text-right mt-4">
                <a href="#" class="text-amber-700 font-semibold hover:text-amber-600">➡️ Lihat Semua Surat</a>
            </div>
        </div>
    </main>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
