@extends('layouts.app')

@section('title', 'Tambah Jabatan')
@section('page-title', 'Tambah Jabatan')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-black">
        <h5 class="m-0">Tambah Jabatan</h5>
    </div>
    <div class="card-body">
        <!-- Form Tambah Jabatan -->
        <form action="{{ route('admin_akademik.master-jabatan.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="nama_jabatan" class="form-label fw-semibold">Nama Jabatan</label>
                <input
                    type="text"
                    id="nama_jabatan"
                    name="nama_jabatan"
                    class="form-control @error('nama_jabatan') is-invalid @enderror"
                    placeholder="Contoh: Dekan, Kaprodi"
                    value="{{ old('nama_jabatan') }}"
                    required
                >
                @error('nama_jabatan')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @else
                    <div class="form-text">Masukkan nama jabatan sesuai struktur organisasi.</div>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 pt-2">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save2 me-1"></i> Simpan Jabatan
                </button>
            </div>
        </form>
    </div>
    <div class="card-footer bg-transparent small text-muted">
        Pastikan data jabatan sesuai dengan struktur organisasi resmi.
    </div>
</div>

@endsection
