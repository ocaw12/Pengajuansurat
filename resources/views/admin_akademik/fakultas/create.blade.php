@extends('layouts.app')

@section('title', 'Tambah Fakultas')

@push('styles')
<style>
    /* Hilangkan padding default body agar rapat ke atas */
    .container-fluid { max-width: 1300px; padding-top: 0.5rem !important; }
    
    /* Header dibuat ringkas konsisten dengan Staff/Pejabat */
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
                <i class="bi bi-building-add me-2 text-warning"></i>Tambah Fakultas Baru
            </h4>
            <p class="text-muted small mb-0">Input identitas fakultas baru untuk struktur organisasi universitas.</p>
        </div>
        <div class="d-none d-md-block">
            <a href="{{ route('admin_akademik.fakultas.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <form action="{{ route('admin_akademik.fakultas.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="row row-main g-3">
            {{-- KOLOM KIRI: Input Utama --}}
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="form-section-title">Informasi Fakultas</div>
                        
                        {{-- Nama Fakultas --}}
                        <div class="mb-4">
                            <label for="nama_fakultas" class="form-label">Nama Lengkap Fakultas</label>
                            <input type="text" class="form-control @error('nama_fakultas') is-invalid @enderror" 
                                   id="nama_fakultas" name="nama_fakultas" value="{{ old('nama_fakultas') }}" 
                                   placeholder="Contoh: Fakultas Sains dan Teknologi" required>
                            @error('nama_fakultas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Kode Fakultas --}}
                        <div class="mb-0">
                            <label for="kode_fakultas" class="form-label">Kode Singkatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control text-uppercase @error('kode_fakultas') is-invalid @enderror" 
                                       id="kode_fakultas" name="kode_fakultas" value="{{ old('kode_fakultas') }}" 
                                       placeholder="FST" maxlength="10" required style="border-left: none;">
                            </div>
                            @error('kode_fakultas') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            <div class="form-text mt-2 small text-muted">Gunakan 3-6 huruf (misal: FST, FEB).</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Panduan & Submit --}}
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="form-section-title">Verifikasi Data</div>
                            
                            <div class="info-card mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle-fill text-warning me-2 mt-1"></i>
                                    <div class="small text-dark">
                                        Pastikan <strong>Nama Fakultas</strong> sesuai dengan SK Rektor atau dokumen resmi lainnya untuk menghindari kesalahan administrasi.
                                    </div>
                                </div>
                            </div>

                            <p class="text-muted small">
                                <i class="bi bi-check-circle me-1 text-success"></i> Kode fakultas akan menjadi prefiks untuk manajemen data selanjutnya.
                            </p>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow-sm rounded-pill text-dark">
                                <i class="bi bi-save2-fill me-2"></i> SIMPAN DATA FAKULTAS
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection