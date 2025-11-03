<!-- resources/views/admin_akademik/prodi/create.blade.php -->
@extends('layouts.app')

@section('title', 'Tambah Prodi')
@section('page-title', 'Tambah Prodi')

@section('content')
    <form action="{{ route('admin_akademik.prodi.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nama_prodi" class="form-label">Nama Prodi</label>
            <input type="text" class="form-control @error('nama_prodi') is-invalid @enderror" id="nama_prodi" name="nama_prodi" value="{{ old('nama_prodi') }}" required>
            @error('nama_prodi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="kode_prodi" class="form-label">Kode Prodi</label>
            <input type="text" class="form-control @error('kode_prodi') is-invalid @enderror" id="kode_prodi" name="kode_prodi" value="{{ old('kode_prodi') }}" required>
            @error('kode_prodi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="fakultas_id" class="form-label">Fakultas</label>
            <select class="form-control @error('fakultas_id') is-invalid @enderror" id="fakultas_id" name="fakultas_id" required>
                <option value="">Pilih Fakultas</option>
                @foreach ($fakultas as $f)
                    <option value="{{ $f->id }}" {{ old('fakultas_id') == $f->id ? 'selected' : '' }}>{{ $f->nama_fakultas }}</option>
                @endforeach
            </select>
            @error('fakultas_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Prodi</button>
    </form>
@endsection
