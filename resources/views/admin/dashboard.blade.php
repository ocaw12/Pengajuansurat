@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-2">Dashboard Admin</h1>
    <p class="text-gray-600 mb-4">Selamat datang, {{ auth()->user()->name }}</p>

    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-red-600">Logout</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    <div class="mt-6">
        <h2 class="font-semibold mb-2">Menu Utama</h2>
        <ul class="list-disc list-inside text-gray-700">
            <li>Kelola Data Mahasiswa</li>
            <li>Kelola Staf Jurusan</li>
            <li>Kelola Pejabat Berwenang</li>
            <li>Kelola Prodi & Fakultas</li>
            <li>Kelola Jenis Surat</li>
        </ul>
    </div>
</div>
@endsection
