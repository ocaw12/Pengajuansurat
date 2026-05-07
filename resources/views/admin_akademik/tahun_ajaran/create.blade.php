@extends('layouts.app')

@section('title', 'Tambah Tahun Ajaran')
@section('page-title', 'Tambah Tahun Ajaran')

@section('content')
<div class="container-fluid">

    <div class="card border-0 shadow-sm col-lg-6">

        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Form Tahun Ajaran</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin_akademik.tahun-ajaran.store') }}"
                  method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Tahun Ajaran
                    </label>

                    <input type="text"
                           name="tahun"
                           class="form-control @error('tahun') is-invalid @enderror"
                           placeholder="Contoh: 2025/2026"
                           value="{{ old('tahun') }}"
                           required>

                    @error('tahun')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary px-4 rounded-pill">
                        Simpan
                    </button>

                    <a href="{{ route('admin_akademik.tahun-ajaran.index') }}"
                       class="btn btn-outline-secondary rounded-pill px-4">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
