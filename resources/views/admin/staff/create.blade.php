@extends('layouts.app')

@section('content')
<div x-data="{ masterOpen: true, suratOpen: false }" class="min-h-screen flex bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Konten Utama -->
    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Tambah Staf Jurusan</h1>

        <form action="{{ route('staff.store') }}" method="POST" class="space-y-4 bg-white shadow-md rounded-lg p-6 border border-gray-200">
            @csrf

            <div>
                <label class="block font-medium text-gray-700">ID Staff</label>
                <input type="text" name="id_staff" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Nama Staff</label>
                <input type="text" name="nama_staff" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Program Studi</label>
                <select name="id_prodi" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                    <option value="">-- Pilih Prodi --</option>
                    @foreach ($prodi as $p)
                        <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
            </div>

            <div class="flex items-center space-x-3 mt-6">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Simpan</button>
                <a href="{{ route('staff.index') }}" class="text-gray-600 hover:underline">Kembali</a>
            </div>
        </form>
    </main>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
