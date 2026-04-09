@extends('layouts.app')

@section('title', 'Edit Fakultas')

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
                <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Fakultas
            </h4>
            <p class="text-muted small mb-0">Perbarui informasi identitas fakultas yang sudah terdaftar.</p>
        </div>
        <div class="d-none d-md-block">
            <a href="{{ route('admin_akademik.fakultas.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <form action="{{ route('admin_akademik.fakultas.update', ['fakultas' => $fakultas->id]) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        
        <div class="row row-main g-3">
            {{-- KOLOM KIRI: Form Edit --}}
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="form-section-title">Informasi Fakultas</div>
                        
                        {{-- Nama Fakultas --}}
                        <div class="mb-4">
                            <label for="nama_fakultas" class="form-label">Nama Lengkap Fakultas</label>
                            <input type="text" class="form-control @error('nama_fakultas') is-invalid @enderror" 
                                   id="nama_fakultas" name="nama_fakultas" 
                                   value="{{ old('nama_fakultas', $fakultas->nama_fakultas) }}" 
                                   placeholder="Contoh: Fakultas Sains dan Teknologi" required>
                            @error('nama_fakultas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Kode Fakultas --}}
                        <div class="mb-0">
                            <label for="kode_fakultas" class="form-label">Kode Singkatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control text-uppercase @error('kode_fakultas') is-invalid @enderror" 
                                       id="kode_fakultas" name="kode_fakultas" 
                                       value="{{ old('kode_fakultas', $fakultas->kode_fakultas) }}" 
                                       placeholder="FST" maxlength="10" required style="border-left: none;">
                            </div>
                            @error('kode_fakultas') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            <div class="form-text mt-2 small text-muted">Contoh: FST, FEB, atau FKIP (3-10 Karakter).</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Panduan & Submit --}}
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="form-section-title">Riwayat Perubahan</div>
                            
                            <div class="info-card mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle-fill text-warning me-2 mt-1"></i>
                                    <div class="small text-dark">
                                        Perubahan <strong>Nama</strong> atau <strong>Kode</strong> akan langsung berdampak pada laporan akademik dan data program studi terkait.
                                    </div>
                                </div>
                            </div>

                            <ul class="list-unstyled small text-muted px-2">
                                <li class="mb-2"><i class="bi bi-calendar3 text-warning me-2"></i> Terdaftar: {{ $fakultas->created_at->format('d M Y') }}</li>
                                <li class="mb-2"><i class="bi bi-clock-history text-warning me-2"></i> Update Terakhir: {{ $fakultas->updated_at->diffForHumans() }}</li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow-sm rounded-pill text-dark text-uppercase">
                                <i class="bi bi-save2-fill me-2"></i> Perbarui Data Fakultas
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-link w-100 text-muted text-decoration-none small mt-3">
                                Batalkan Perubahan
                            </a>
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