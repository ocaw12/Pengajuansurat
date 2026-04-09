@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

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
    .form-control:focus, .form-select:focus {
        background-color: #fff; border-color: #f59e0b; box-shadow: none;
    }

    .status-card {
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
                <i class="bi bi-person-plus-fill me-2 text-warning"></i>Tambah Mahasiswa Baru
            </h4>
            <p class="text-muted small mb-0">Input data identitas diri dan informasi akademik mahasiswa.</p>
        </div>
        <div class="d-none d-md-block">
            <a href="{{ route('admin_akademik.mahasiswa.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <form action="{{ route('admin_akademik.mahasiswa.store') }}" method="POST">
        @csrf
        <div class="row row-main">
            {{-- KOLOM KIRI: BIODATA PRIBADI --}}
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="form-section-title">Informasi Pribadi</div>
                        
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                   name="nama_lengkap" value="{{ old('nama_lengkap') }}" 
                                   placeholder="Nama lengkap tanpa gelar (sesuai ijazah)" required>
                            @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                       name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                                @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki_laki" {{ old('jenis_kelamin') == 'Laki_laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="no_telepon" class="form-label">No. WhatsApp</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                       name="no_telepon" value="{{ old('no_telepon') }}" placeholder="0812...">
                                @error('no_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: AKADEMIK & SISTEM --}}
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="form-section-title">Data Akademik</div>
                            
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                                <input type="text" class="form-control @error('nim') is-invalid @enderror" 
                                       name="nim" value="{{ old('nim') }}" placeholder="Masukkan NIM" required>
                                @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="program_studi_id" class="form-label">Program Studi</label>
                                    <select name="program_studi_id" class="form-select @error('program_studi_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach($program_studis as $prodi)
                                            <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                                {{ $prodi->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('program_studi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="angkatan" class="form-label">Angkatan</label>
                                    <input type="number" class="form-control @error('angkatan') is-invalid @enderror" 
                                           name="angkatan" value="{{ old('angkatan', date('Y')) }}" required>
                                    @error('angkatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Email Institusi (Login)</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" placeholder="mhs@univ.ac.id" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="status-card">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark small" for="is_active">Status Akun Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow-sm rounded-pill">
                                <i class="bi bi-save-fill me-2"></i> SIMPAN DATA MAHASISWA
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection