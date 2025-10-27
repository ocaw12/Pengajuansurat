@extends('layouts.app')

@section('content')
<div x-data="{ masterOpen: true, suratOpen: false }" class="min-h-screen flex bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Konten Utama -->
    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-4">Data Staf Jurusan</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <a href="{{ route('staff.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Tambah Staf</a>

        <table class="w-full mt-6 border-collapse border border-gray-300 text-sm">
            <thead class="bg-amber-100">
                <tr>
                    <th class="border p-2">ID Staff</th>
                    <th class="border p-2">Nama Staff</th>
                    <th class="border p-2">Prodi</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($staff as $s)
                    <tr class="hover:bg-amber-50">
                        <td class="border p-2">{{ $s->id_staff }}</td>
                        <td class="border p-2">{{ $s->nama_staff }}</td>
                        <td class="border p-2">{{ $s->prodi->nama_prodi ?? '-' }}</td>
                        <td class="border p-2">{{ $s->user->email ?? '-' }}</td>
                        <td class="border p-2 text-center space-x-1">
                            <a href="{{ route('staff.edit', $s->id_staff) }}" class="bg-yellow-400 text-gray-900 px-2 py-1 rounded hover:bg-yellow-500 text-sm">Edit</a>
                            <form action="{{ route('staff.destroy', $s->id_staff) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-3 text-gray-600">Belum ada data staf jurusan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
