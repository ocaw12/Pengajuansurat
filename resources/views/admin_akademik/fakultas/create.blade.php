@extends('layouts.app')

@section('title', 'Tambah Fakultas')
@section('page-title', 'Tambah Fakultas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin_akademik.dashboard') }}">Admin Akademik</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin_akademik.fakultas.index') }}">Fakultas</a></li>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-10 col-xl-8">

    <div class="card shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between text-bg-primary">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-building-add"></i>
          <strong>Tambah Fakultas</strong>
        </div>
        <a href="{{ route('admin_akademik.fakultas.index') }}" class="btn btn-sm btn-light">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
      </div>

      <div class="card-body">
        <form action="{{ route('admin_akademik.fakultas.store') }}" method="POST" class="needs-validation" novalidate>
          @csrf

          {{-- Nama Fakultas --}}
          <div class="mb-3">
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
          <div class="mb-4">
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

          {{-- Tombol Aksi --}}
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
</div>

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
@endsection
