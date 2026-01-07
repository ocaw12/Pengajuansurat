@extends('layouts.app')

@section('title', 'Edit Fakultas')
@section('page-title', 'Edit Fakultas')

{{-- Breadcrumb tambahan (opsional) --}}
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin_akademik.dashboard') }}">Admin Akademik</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin_akademik.fakultas.index') }}">Fakultas</a></li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-black">
        <h5 class="m-0">Edit Fakultas</h5>
    </div>
    <div class="card-body">
        <form
            action="{{ route('admin_akademik.fakultas.update', ['fakultas' => $fakultas->id]) }}"
            method="POST"
            class="needs-validation"
            novalidate
        >
            @csrf
            @method('PUT')

            {{-- Nama Fakultas --}}
            <div class="form-group mb-3">
                <label for="nama_fakultas" class="form-label fw-semibold">Nama Fakultas</label>
                <input
                    type="text"
                    id="nama_fakultas"
                    name="nama_fakultas"
                    class="form-control @error('nama_fakultas') is-invalid @enderror"
                    value="{{ old('nama_fakultas', $fakultas->nama_fakultas) }}"
                    placeholder="Contoh: Fakultas Sains dan Teknologi"
                    required
                >
                @error('nama_fakultas')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @else
                    <div class="form-text">Gunakan penulisan resmi sesuai SK.</div>
                @enderror
            </div>

            {{-- Kode Fakultas --}}
            <div class="form-group mb-3">
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
                        value="{{ old('kode_fakultas', $fakultas->kode_fakultas) }}"
                        placeholder="FST"
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
                    <div class="form-text">Kode akan ditampilkan di daftar & filter pencarian.</div>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2 pt-2">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i> Perbarui Fakultas
                </button>
            </div>
        </form>
    </div>

    {{-- Optional footer info --}}
    <div class="card-footer bg-transparent small text-muted">
        Pastikan data sesuai dokumen resmi (SK/Keputusan Rektor).
    </div>
</div>

{{-- Bootstrap client-side validation hint (opsional, ringan) --}}
@push('scripts')
<script>
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault(); event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
@endpush

@endsection
