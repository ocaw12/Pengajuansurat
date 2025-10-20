@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-amber-100 to-amber-200">
    <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">
        <!-- Header dengan logo dan judul -->
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/logoup45.png') }}" alt="Logo Universitas 45" class="w-24 h-24 mb-3">
            <h1 class="text-center text-2xl font-extrabold text-amber-700 mb-2">
                Sistem Informasi Layanan<br>Pengajuan Surat Mahasiswa
            </h1>
            <p class="text-gray-500 text-sm text-center">Universitas 45</p>
        </div>

        <!-- Menampilkan error jika ada -->
        @if ($errors->any())
            <div class="mb-4 text-red-600 bg-red-100 border border-red-300 rounded-lg p-3">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label for="id" class="block text-gray-700 font-semibold">ID</label>
                <input type="text" name="id" id="id"
                    class="w-full border border-gray-300 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-amber-400 focus:outline-none"
                    placeholder="Masukkan ID anda" required autofocus>
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-semibold">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full border border-gray-300 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-amber-400 focus:outline-none"
                    placeholder="Masukkan password" required>
            </div>

            <!-- Tombol Login -->
            <button type="submit"
                class="w-full bg-amber-400 text-gray-900 font-semibold py-2 rounded-lg hover:bg-amber-500 transition duration-300 shadow-md">
                Login
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Universitas 45 – All Rights Reserved
        </div>
    </div>
</div>
@endsection
