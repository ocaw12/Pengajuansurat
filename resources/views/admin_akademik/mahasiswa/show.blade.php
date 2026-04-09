@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@push('styles')
<style>
    /* Container & Header Setup */
    .container-fluid { max-width: 1200px; padding-top: 0.5rem !important; }
    
    .page-header {
        background: #fff;
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Profile Sidebar Styling */
    .profile-card {
        background: #fff;
        border-radius: 16px;
        border: none;
        overflow: hidden;
    }
    .profile-avatar-big {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);
    }

    /* Info Grid Styling */
    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .info-value {
        font-size: 0.95rem;
        color: #1e293b;
        font-weight: 500;
        margin-bottom: 1.25rem;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #f59e0b;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title::after {
        content: ""; flex: 1; height: 1px; background: #f1f5f9;
    }

    /* Badge Custom */
    .status-badge {
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <div class="page-header shadow-sm">
        <div>
            <h4 class="fw-bold text-dark mb-0">
                <i class="bi bi-person-badge me-2 text-warning"></i>Detail Profil Mahasiswa
            </h4>
            <p class="text-muted small mb-0">Informasi lengkap data diri dan status akademik mahasiswa.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin_akademik.mahasiswa.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('admin_akademik.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-white">
                <i class="bi bi-pencil-square me-1"></i> Edit Data
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card profile-card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="profile-avatar-big">
                        {{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $mahasiswa->nama_lengkap }}</h5>
                    <p class="text-muted small mb-3">{{ $mahasiswa->nim }}</p>
                    
                    @if($mahasiswa->user && $mahasiswa->user->is_active)
                        <span class="badge bg-success-subtle text-success status-badge">
                            <i class="bi bi-check-circle-fill me-1"></i> AKUN AKTIF
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger status-badge">
                            <i class="bi bi-x-circle-fill me-1"></i> TIDAK AKTIF
                        </span>
                    @endif

                    <hr class="my-4" style="opacity: 0.1;">

                    <div class="text-start">
                        <div class="info-label">Email Institusi</div>
                        <div class="info-value text-break">{{ $mahasiswa->user->email ?? '-' }}</div>

                        <div class="info-label">Program Studi</div>
                        <div class="info-value">{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</div>
                        
                        <div class="info-label">Angkatan</div>
                        <div class="info-value">{{ $mahasiswa->angkatan }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body p-4">
                    
                    <div class="section-title">Informasi Pribadi</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">Tempat, Tanggal Lahir</div>
                            <div class="info-value">
                                {{ $mahasiswa->tempat_lahir }}, 
                                {{ \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Jenis Kelamin</div>
                            <div class="info-value">
                                {{ $mahasiswa->jenis_kelamin == 'LAKI_LAKI' ? 'Laki-laki' : 'Perempuan' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">No. WhatsApp / Telepon</div>
                            <div class="info-value">
                                <a href="https://wa.me/{{ $mahasiswa->no_telepon }}" target="_blank" class="text-decoration-none text-dark">
                                    <i class="bi bi-whatsapp text-success me-1"></i> {{ $mahasiswa->no_telepon ?? '-' }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="info-label">Alamat Lengkap</div>
                            <div class="info-value text-muted" style="line-height: 1.6;">
                                {{ $mahasiswa->alamat ?? 'Alamat belum diisi' }}
                            </div>
                        </div>
                    </div>

                    <div class="section-title mt-2">Log Sistem</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">Terdaftar Pada</div>
                            <div class="info-value small text-muted">
                                <i class="bi bi-calendar-check me-1"></i> {{ $mahasiswa->created_at?->translatedFormat('d F Y, H:i') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Terakhir Diperbarui</div>
                            <div class="info-value small text-muted">
                                <i class="bi bi-clock-history me-1"></i> {{ $mahasiswa->updated_at?->translatedFormat('d F Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                        <form action="{{ route('admin_akademik.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none btn-sm fw-bold">
                                <i class="bi bi-trash me-1"></i> Hapus Mahasiswa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection