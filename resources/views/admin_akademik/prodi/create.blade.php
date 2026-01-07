@extends('layouts.app')

@section('title', 'Tambah Prodi')
@section('page-title', 'Tambah Prodi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin_akademik.dashboard') }}">Admin Akademik</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin_akademik.prodi.index') }}">Program Studi</a></li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Card Container for the Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="m-0">Tambah Program Studi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin_akademik.prodi.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                {{-- Nama Prodi --}}
                <div class="form-group mb-3">
                    <label for="nama_prodi" class="form-label fw-semibold">Nama Program Studi</label>
                    <input
                        type="text"
                        id="nama_prodi"
                        name="nama_prodi"
                        class="form-control @error('nama_prodi') is-invalid @enderror"
                        placeholder="Contoh: Teknik Informatika"
                        value="{{ old('nama_prodi') }}"
                        required
                    >
                    @error('nama_prodi')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @else
                        <div class="form-text">Gunakan nama resmi sesuai SK.</div>
                    @enderror
                </div>

                {{-- Kode Prodi --}}
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="kode_prodi" class="form-label fw-semibold mb-0">Kode Program Studi</label>
                        <small class="text-muted">3–6 huruf (mis.: TI, TM, AI)</small>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                        <input
                            type="text"
                            id="kode_prodi"
                            name="kode_prodi"
                            class="form-control text-uppercase @error('kode_prodi') is-invalid @enderror"
                            placeholder="TI"
                            value="{{ old('kode_prodi') }}"
                            minlength="2"
                            maxlength="10"
                            required
                        >
                    </div>
                    @error('kode_prodi')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @else
                        <div class="form-text">Kode akan digunakan untuk identifikasi internal.</div>
                    @enderror
                </div>

                {{-- Fakultas --}}
                <div class="form-group mb-4">
                    <label for="fakultas_id" class="form-label fw-semibold">Fakultas</label>
                    <select class="form-control @error('fakultas_id') is-invalid @enderror" id="fakultas_id" name="fakultas_id" required>
                        <option value="">Pilih Fakultas</option>
                        @foreach ($fakultas as $f)
                            <option value="{{ $f->id }}" {{ old('fakultas_id') == $f->id ? 'selected' : '' }}>
                                {{ $f->nama_fakultas }}
                            </option>
                        @endforeach
                    </select>
                    @error('fakultas_id')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2 pt-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save2 me-1"></i> Simpan Program Studi
                    </button>
                </div>
            </form>
        </div>

        <div class="card-footer bg-transparent small text-muted">
            Pastikan data sesuai dokumen resmi (SK/Keputusan Rektor).
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(() => {
  'use strict';
  document.querySelectorAll('.needs-validation').forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
@endpush
