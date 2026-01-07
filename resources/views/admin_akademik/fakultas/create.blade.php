@extends('layouts.app')

@section('title', 'Tambah Fakultas')
@section('page-title', 'Tambah Fakultas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin_akademik.dashboard') }}">Admin Akademik</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin_akademik.fakultas.index') }}">Fakultas</a></li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Card Container for the Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="m-0">Tambah Fakultas</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin_akademik.fakultas.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                {{-- Nama Fakultas --}}
                <div class="form-group mb-3">
                    <label for="nama_fakultas" class="form-label fw-semibold">Nama Fakultas</label>
                    <input
                        type="text"
                        id="nama_fakultas"
                        name="nama_fakultas"
                        class="form-control @error('nama_fakultas') is-invalid @enderror"
                        placeholder="Contoh: Fakultas Sains dan Teknologi"
                        value="{{ old('nama_fakultas') }}"
                        required
                    >
                    @error('nama_fakultas')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @else
                        <div class="form-text">Gunakan nama resmi sesuai SK.</div>
                    @enderror
                </div>

                {{-- Kode Fakultas --}}
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="kode_fakultas" class="form-label fw-semibold mb-0">Kode Fakultas</label>
                        <small class="text-muted">3–6 huruf (mis.: FST, FEB)</small>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                        <input
                            type="text"
                            id="kode_fakultas"
                            name="kode_fakultas"
                            class="form-control text-uppercase @error('kode_fakultas') is-invalid @enderror"
                            placeholder="FST"
                            value="{{ old('kode_fakultas') }}"
                            minlength="2"
                            maxlength="10"
                            required
                        >
                    </div>
                    @error('kode_fakultas')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @else
                        <div class="form-text">Kode akan digunakan untuk identifikasi internal.</div>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2 pt-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save2 me-1"></i> Simpan Fakultas
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
