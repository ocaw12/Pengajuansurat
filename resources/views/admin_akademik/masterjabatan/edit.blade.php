@extends('layouts.app')

@section('title', 'Edit Jabatan')
@section('page-title', 'Edit Jabatan')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-black">
        <h5 class="m-0">Edit Jabatan</h5>
    </div>
    <div class="card-body">
        <!-- Form Edit Jabatan -->
        <form action="{{ route('admin_akademik.master-jabatan.update', $jabatan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                <input
                    type="text"
                    class="form-control @error('nama_jabatan') is-invalid @enderror"
                    id="nama_jabatan"
                    name="nama_jabatan"
                    value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}"
                    required
                >
                @error('nama_jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 pt-2">
                <a href="{{ route('admin_akademik.master-jabatan.index') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i> Perbarui Jabatan
                </button>
            </div>
        </form>
    </div>

    {{-- Optional footer info --}}
    <div class="card-footer bg-transparent small text-muted">
        Pastikan data jabatan sesuai dengan struktur organisasi yang berlaku.
    </div>
</div>

@endsection
