@extends('layouts.app')

@section('title', 'Tambah Jabatan')

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
    .form-control { 
        border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 0.5rem 0.8rem; background-color: #f8fafc; font-size: 0.9rem;
    }
    .form-control:focus {
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
                <i class="bi bi-briefcase-fill me-2 text-warning"></i>Tambah Jabatan Baru
            </h4>
            <p class="text-muted small mb-0">Definisikan level jabatan baru dalam struktur organisasi universitas.</p>
        </div>
        <div class="d-none d-md-block">
            <a href="{{ route('admin_akademik.master-jabatan.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <form action="{{ route('admin_akademik.master-jabatan.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="row row-main g-3">
            {{-- KOLOM KIRI: Form Input --}}
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="form-section-title">Detail Jabatan</div>
                        
                        {{-- Nama Jabatan --}}
                        <div class="mb-0">
                            <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                            <input type="text" 
                                   id="nama_jabatan"
                                   name="nama_jabatan" 
                                   class="form-control @error('nama_jabatan') is-invalid @enderror" 
                                   placeholder="Contoh: Dekan, Kaprodi, atau Sekretaris" 
                                   value="{{ old('nama_jabatan') }}" 
                                   required>
                            @error('nama_jabatan') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @else
                                <div class="form-text mt-2 small text-muted">Gunakan nama jabatan resmi tanpa singkatan berlebih.</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Panduan & Submit --}}
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="form-section-title">Ketentuan Struktur</div>
                            
                            <div class="info-card mb-3">
                                <div class="d-flex align-items-start small text-dark">
                                    <i class="bi bi-shield-check text-warning me-2 mt-1 fs-5"></i>
                                    <div>
                                        Jabatan yang ditambahkan akan muncul sebagai pilihan pada saat <strong>Penempatan Pejabat</strong> di tingkat Fakultas maupun Program Studi.
                                    </div>
                                </div>
                            </div>

                            <p class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i> Pastikan tidak ada duplikasi nama jabatan yang memiliki fungsi serupa.
                            </p>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow-sm rounded-pill text-dark text-uppercase">
                                <i class="bi bi-save2-fill me-2"></i> Simpan Jabatan
                            </button>
                            <p class="text-center text-muted small mt-3 mb-0">
                                Sistem akan mencatat perubahan secara real-time.
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