@extends('layouts.app')

@section('title', 'Tambah Program Studi')

@push('styles')
<style>
    /* Hilangkan padding default body agar rapat ke atas */
    .container-fluid { max-width: 1300px; padding-top: 0.5rem !important; }
    
    /* Header dibuat ringkas konsisten */
    .page-header {
        background: #fff;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        border: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card { border: none; border-radius: 12px; background: #fff; margin-bottom: 0; }
    
    /* Judul seksi warna kuning f59e0b */
    .form-section-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #f59e0b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .form-section-title::after {
        content: ""; flex: 1; height: 1px; background: #f1f5f9; margin-left: 1rem;
    }

    .form-label { font-weight: 600; color: #334155; font-size: 0.8rem; margin-bottom: 0.3rem; }
    .form-control, .form-select { 
        border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 0.5rem 0.8rem; background-color: #f8fafc; font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        background-color: #fff; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1); outline: none;
    }

    .info-card {
        background: #fff9ed;
        border: 1px solid #fef3c7;
        border-radius: 10px;
        padding: 1rem;
    }
    
    /* Atur tinggi kolom agar sejajar */
    .row-main { display: flex; align-items: stretch; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <div class="page-header shadow-sm">
        <div>
            <h4 class="fw-bold text-dark mb-0">
                <i class="bi bi-journal-plus me-2 text-warning"></i>Tambah Program Studi
            </h4>
            <p class="text-muted small mb-0">Daftarkan program studi baru dan hubungkan ke fakultas terkait.</p>
        </div>
        <div class="d-none d-md-block">
            <a href="{{ route('admin_akademik.prodi.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <form action="{{ route('admin_akademik.prodi.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="row row-main g-3">
            {{-- KOLOM KIRI: Identitas Prodi --}}
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="form-section-title">Informasi Program Studi</div>
                        
                        {{-- Nama Prodi --}}
                        <div class="mb-4">
                            <label for="nama_prodi" class="form-label">Nama Program Studi</label>
                            <input type="text" class="form-control @error('nama_prodi') is-invalid @enderror" 
                                   id="nama_prodi" name="nama_prodi" value="{{ old('nama_prodi') }}" 
                                   placeholder="Contoh: Teknik Informatika" required>
                            @error('nama_prodi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Kode Prodi --}}
                        <div class="mb-0">
                            <label for="kode_prodi" class="form-label">Kode Program Studi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control text-uppercase @error('kode_prodi') is-invalid @enderror" 
                                       id="kode_prodi" name="kode_prodi" value="{{ old('kode_prodi') }}" 
                                       placeholder="TI" maxlength="10" required style="border-left: none;">
                            </div>
                            @error('kode_prodi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            <div class="form-text mt-2 small text-muted">Contoh: TI, TM, atau AKT (2-10 Karakter).</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Penempatan & Submit --}}
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="form-section-title">Penempatan Fakultas</div>
                            
                            {{-- Pilih Fakultas --}}
                            <div class="mb-4">
                                <label for="fakultas_id" class="form-label">Fakultas Naungan</label>
                                <select class="form-select @error('fakultas_id') is-invalid @enderror" id="fakultas_id" name="fakultas_id" required>
                                    <option value="">-- Pilih Fakultas --</option>
                                    @foreach ($fakultas as $f)
                                        <option value="{{ $f->id }}" {{ old('fakultas_id') == $f->id ? 'selected' : '' }}>
                                            {{ $f->nama_fakultas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fakultas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="info-card mb-3">
                                <div class="d-flex align-items-start small">
                                    <i class="bi bi-info-circle-fill text-warning me-2 mt-1"></i>
                                    <span>Pastikan Prodi terhubung ke Fakultas yang benar untuk keperluan manajemen staff dan kurikulum.</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow-sm rounded-pill text-dark text-uppercase">
                                <i class="bi bi-save2-fill me-2"></i> Simpan Program Studi
                            </button>
                            <p class="text-center text-muted small mt-3 mb-0">
                                <i class="bi bi-shield-check me-1"></i> Data akan divalidasi sistem.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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