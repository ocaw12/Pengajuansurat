@extends('layouts.app')

@section('title', 'Edit Program Studi')
@section('page-title', 'Edit Program Studi')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-black">
        <h5 class="m-0">Edit Program Studi</h5>
    </div>
    <div class="card-body">
        <!-- Form Edit Program Studi -->
        <form action="{{ route('admin_akademik.prodi.update', $prodi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Prodi -->
            <div class="form-group mb-3">
                <label for="nama_prodi" class="form-label">Nama Program Studi</label>
                <input
                    type="text"
                    class="form-control @error('nama_prodi') is-invalid @enderror"
                    id="nama_prodi"
                    name="nama_prodi"
                    value="{{ old('nama_prodi', $prodi->nama_prodi) }}"
                    required
                >
                @error('nama_prodi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kode Prodi -->
            <div class="form-group mb-3">
                <label for="kode_prodi" class="form-label">Kode Program Studi</label>
                <input
                    type="text"
                    class="form-control @error('kode_prodi') is-invalid @enderror"
                    id="kode_prodi"
                    name="kode_prodi"
                    value="{{ old('kode_prodi', $prodi->kode_prodi) }}"
                    required
                >
                @error('kode_prodi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Fakultas -->
            <div class="form-group mb-3">
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
            <div class="d-flex justify-content-end gap-2 pt-2">
                <a href="{{ route('admin_akademik.prodi.index') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i> Perbarui Program Studi
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
