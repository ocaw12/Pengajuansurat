@extends('layouts.app')

@section('title', 'Edit Pejabat')

@push('styles')
<style>
    .container-fluid { max-width: 1300px; padding-top: 0.5rem !important; }
    
    /* Header ringkas konsisten dengan halaman tambah */
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

    .card { border: none; border-radius: 12px; background: #fff; }
    
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
        background-color: #fff; border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1); 
    }

    .status-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1rem;
    }
    
    .row-main { display: flex; align-items: stretch; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <div class="page-header shadow-sm">
        <div>
            <h4 class="fw-bold text-dark mb-0">
                <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Pejabat
            </h4>
            <p class="text-muted small mb-0">Memperbarui informasi identitas atau jabatan pejabat: <strong>{{ $pejabat->nama_lengkap }}</strong></p>
        </div>
        <div class="d-none d-md-block">
            <a href="{{ route('admin_akademik.pejabat.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin_akademik.pejabat.update', $pejabat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row row-main">
            {{-- KOLOM KIRI: DATA PRIBADI --}}
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="form-section-title">Informasi Pribadi</div>
                        
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap & Gelar</label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                   name="nama_lengkap" value="{{ old('nama_lengkap', $pejabat->nama_lengkap) }}" required>
                            @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nip_atau_nidn" class="form-label">NIP / NIDN</label>
                                <input type="text" class="form-control @error('nip_atau_nidn') is-invalid @enderror" 
                                       name="nip_atau_nidn" value="{{ old('nip_atau_nidn', $pejabat->nip_atau_nidn) }}" required>
                                @error('nip_atau_nidn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="no_telepon" class="form-label">No. WhatsApp</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                       name="no_telepon" value="{{ old('no_telepon', $pejabat->no_telepon) }}">
                                @error('no_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email Institusi</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email', $pejabat->user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-section-title">Keamanan</div>
                        <div class="mb-0">
                            <label for="password" class="form-label">Ganti Password (Opsional)</label>
                            <input type="password" class="form-control" name="password" placeholder="Isi hanya jika ingin mengganti password">
                            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: PENEMPATAN --}}
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="form-section-title">Penempatan & Akses</div>
                            
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan Struktural</label>
                                <select name="jabatan" class="form-select @error('jabatan') is-invalid @enderror" id="jabatan" required>
                                    @foreach ($masterJabatan as $j)
                                        <option value="{{ $j->id }}" {{ old('jabatan', $pejabat->master_jabatan_id) == $j->id ? 'selected' : '' }}>
                                            {{ $j->nama_jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3" id="fakultas" style="display: none;">
                                <label for="fakultas_id" class="form-label">Fakultas</label>
                                <select name="fakultas_id" class="form-select">
                                    <option value="">Pilih Fakultas</option>
                                    @foreach ($fakultas as $f)
                                        <option value="{{ $f->id }}" {{ old('fakultas_id', $pejabat->fakultas_id) == $f->id ? 'selected' : '' }}>
                                            {{ $f->nama_fakultas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3" id="program_studi" style="display: none;">
                                <label for="program_studi_id" class="form-label">Program Studi</label>
                                <select name="program_studi_id" class="form-select">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach ($programStudi as $prodi)
                                        <option value="{{ $prodi->id }}" {{ old('program_studi_id', $pejabat->program_studi_id) == $prodi->id ? 'selected' : '' }}>
                                            {{ $prodi->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="status-card">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $pejabat->user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark small" for="is_active">Status Akun Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow-sm rounded-pill text-dark">
                                <i class="bi bi-save2-fill me-2"></i> PERBARUI DATA
                            </button>
                            <p class="text-center mt-2 mb-0">
                                <a href="{{ route('admin_akademik.pejabat.index') }}" class="text-muted small text-decoration-none">Batalkan Perubahan</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleFields(val) {
        const fldFakultas = document.getElementById('fakultas');
        const fldProdi = document.getElementById('program_studi');
        if (val == 2) { // Dekan
            fldFakultas.style.display = 'block'; fldProdi.style.display = 'none';
        } else if (val == 1) { // Kaprodi
            fldFakultas.style.display = 'none'; fldProdi.style.display = 'block';
        } else {
            fldFakultas.style.display = 'none'; fldProdi.style.display = 'none';
        }
    }
    document.querySelector('#jabatan').addEventListener('change', function () { toggleFields(this.value); });
    // Inisialisasi saat load
    window.addEventListener('load', function() { toggleFields(document.querySelector('#jabatan').value); });
</script>
@endsection