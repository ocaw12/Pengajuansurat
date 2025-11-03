@extends('layouts.app')

@section('title', 'Edit Program Studi')
@section('page-title', 'Edit Program Studi')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Program Studi</h2>

    <form action="{{ route('admin_akademik.prodi.update', $prodi->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Nama Prodi -->
        <div class="mb-3">
            <label for="nama_prodi" class="form-label">Nama Program Studi</label>
            <input type="text" class="form-control @error('nama_prodi') is-invalid @enderror" id="nama_prodi" name="nama_prodi" value="{{ old('nama_prodi', $prodi->nama_prodi) }}" required>
            @error('nama_prodi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Kode Prodi -->
        <div class="mb-3">
            <label for="kode_prodi" class="form-label">Kode Program Studi</label>
            <input type="text" class="form-control @error('kode_prodi') is-invalid @enderror" id="kode_prodi" name="kode_prodi" value="{{ old('kode_prodi', $prodi->kode_prodi) }}" required>
            @error('kode_prodi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Fakultas -->
        <div class="mb-3">
            <label for="fakultas_id" class="form-label">Fakultas</label>
            <select class="form-select @error('fakultas_id') is-invalid @enderror" id="fakultas_id" name="fakultas_id" required>
                <option value="">Pilih Fakultas</option>
                @foreach ($fakultas as $fakultasItem)
                    <option value="{{ $fakultasItem->id }}" 
                        {{ old('fakultas_id', $prodi->fakultas_id) == $fakultasItem->id ? 'selected' : '' }}>
                        {{ $fakultasItem->nama_fakultas }}
                    </option>
                @endforeach
            </select>
            @error('fakultas_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-warning text-white">Perbarui Program Studi</button>
        <a href="{{ route('admin_akademik.prodi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
