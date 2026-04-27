@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Overview')

@push('styles')
<style>
    /* Custom Styling untuk Tampilan Modern */
    .card { border-radius: 15px; transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-5px); }
    
    .icon-shape {
        width: 48px;
        height: 48px;
        background-position: center;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #2dce89 0%, #2dcecc 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #f5365c 0%, #f56036 100%); }

    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 700;
        border: none;
        color: #64748b;
    }

    .table tbody td { border-bottom: 1px solid #f1f5f9; padding: 1.2rem 0.75rem; }
    .table tbody tr:last-child td { border: none; }
    
    .status-badge {
        padding: 0.5em 1em;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-2">

    {{-- Welcome Header --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold mb-1 text-dark">Selamat Datang, {{ Auth::user()->name ?? 'Mahasiswa' }}! 👋</h3>
            <p class="text-muted">Pantau status pengajuan suratmu secara real-time di sini.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary btn-lg shadow-sm" style="border-radius: 12px;">
                <i class="bi bi-plus-lg me-2"></i>Buat Pengajuan
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-gradient-primary text-white shadow-sm me-3">
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Total Pengajuan</span>
                            <h3 class="fw-bold mb-0 mt-1">{{ $totalPengajuan }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-gradient-success text-white shadow-sm me-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Selesai / Terbit</span>
                            <h3 class="fw-bold mb-0 mt-1 text-success">{{ $totalSelesai }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-gradient-danger text-white shadow-sm me-3">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Perlu Revisi</span>
                            <h3 class="fw-bold mb-0 mt-1 text-danger">{{ $totalDitolak }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Table Section --}}
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                    <h5 class="fw-bold mb-0">Pengajuan Terbaru</h5>
                    <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-sm btn-light text-primary fw-bold">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body px-0 pt-0">
                    @if($pengajuanTerbaru->isEmpty())
                        <div class="text-center py-5">
                            <img src="https://illustrations.popsy.co/gray/data-report.svg" alt="empty" style="width: 150px;" class="mb-3 opacity-50">
                            <p class="text-muted">Belum ada aktivitas pengajuan.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Jenis Surat</th>
                                        <th>Tgl. Pengajuan</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuanTerbaru as $pengajuan)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-light rounded-circle p-2 me-3 text-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-file-text text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold small">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</h6>
                                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($pengajuan->keperluan, 25) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="small text-muted">
                                                {{ optional($pengajuan->tanggal_pengajuan)->format('d M Y') }}
                                            </td>
                                            <td>
                                                @include('partials.status_badge', ['status' => $pengajuan->status_pengajuan])
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}" class="btn btn-sm btn-icon btn-outline-light border text-dark shadow-sm px-3">
                                                    <i class="bi bi-search me-1"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Side Info --}}
        <div class="col-lg-4">
            <div class="card border-0 bg-dark text-white shadow mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="position-relative" style="z-index: 2;">
                        <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-warning"></i>Butuh Bantuan?</h5>
                        <p class="small opacity-75 mb-4">Jika kamu mengalami kendala atau butuh informasi lebih lanjut mengenai surat-surat akademik, silakan hubungi Staff TU.</p>
@if($linkWa)
    <a href="{{ $linkWa }}" target="_blank" 
       class="btn btn-warning btn-sm fw-bold px-4 py-2 shadow-sm" 
       style="border-radius: 8px;">
        Hubungi Admin
    </a>
@else
    <button class="btn btn-secondary btn-sm fw-bold px-4 py-2" disabled>
        Admin belum tersedia
    </button>
@endif                    </div>
                    <div class="position-absolute" style="top: -20px; right: -20px; font-size: 150px; opacity: 0.1;">
                        <i class="bi bi-question-circle"></i>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 small text-uppercase letter-spacing-1">Panduan Warna</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="dot bg-warning me-3 shadow-sm" style="width: 12px; height: 12px; border-radius: 50%;"></div>
                        <span class="small text-muted"><strong>Menunggu:</strong> Sedang divalidasi.</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="dot bg-success me-3 shadow-sm" style="width: 12px; height: 12px; border-radius: 50%;"></div>
                        <span class="small text-muted"><strong>Selesai:</strong> Surat bisa diunduh/ambil.</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dot bg-danger me-3 shadow-sm" style="width: 12px; height: 12px; border-radius: 50%;"></div>
                        <span class="small text-muted"><strong>Ditolak:</strong> Perlu perbaikan data.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection