@extends('layouts.app')

@section('title', 'Dashboard Pejabat')

@push('styles')
<style>
    /* Card & Stats Styling */
    .stat-card {
        border: none;
        border-radius: 16px;
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-5px); }
    
    .icon-shape {
        width: 48px; height: 48px;
        background-position: center;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }

    /* Table & List Styling */
    .card { border-radius: 16px; border: none; }
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border: none;
    }
    .table tbody td { border-bottom: 1px solid #f1f5f9; padding: 1rem; font-size: 0.85rem; }

    /* Timeline Styling untuk Riwayat */
    .history-item {
        border-left: 2px solid #e2e8f0;
        padding-left: 20px;
        position: relative;
        padding-bottom: 1.5rem;
    }
    .history-item::before {
        content: "";
        position: absolute;
        left: -7px; top: 0;
        width: 12px; height: 12px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #3b82f6;
    }

    /* Status Badge Soft UI */
    .badge-soft {
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.7rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-1">Ringkasan Approval</h3>
            <p class="text-muted">Halo, {{ Auth::user()->name }}. Anda memiliki <strong>{{ $totalMenunggu }} pengajuan</strong> yang memerlukan tindakan.</p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card shadow-sm border-start border-primary border-4">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary me-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small uppercase fw-bold">Menunggu</h6>
                        <h3 class="fw-bold mb-0">{{ $totalMenunggu }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card shadow-sm border-start border-success border-4">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-shape bg-success bg-opacity-10 text-success me-3">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small uppercase fw-bold">Disetujui</h6>
                        <h3 class="fw-bold mb-0 text-success">{{ $totalDisetujui }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card shadow-sm border-start border-danger border-4">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-shape bg-danger bg-opacity-10 text-danger me-3">
                        <i class="bi bi-x-circle fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small uppercase fw-bold">Ditolak</h6>
                        <h3 class="fw-bold mb-0 text-danger">{{ $totalDitolak }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Antrian Utama --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-list-stars me-2 text-primary"></i>Antrian Approval Terbaru</h6>
                    <a href="{{ route('pejabat.approval.antrian') }}" class="btn btn-sm btn-light fw-bold text-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Mahasiswa</th>
                                    <th>Jenis Surat</th>
                                    <th>Tgl. Masuk</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($antrianTerbaru as $antrian)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $antrian->pengajuanSurat->mahasiswa->nama_lengkap }}</div>
                                        <div class="text-muted small">{{ $antrian->pengajuanSurat->mahasiswa->programStudi->nama_prodi }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1">
                                            {{ $antrian->pengajuanSurat->jenisSurat->nama_surat }}
                                        </span>
                                    </td>
                                    <td>{{ optional($antrian->pengajuanSurat->created_at)->format('d M Y') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('pejabat.approval.show', $antrian->id) }}" class="btn btn-primary btn-sm px-3 rounded-pill">
                                            Tinjau <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Tidak ada antrian menunggu.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Log Aktivitas/Riwayat --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Riwayat Terakhir</h6>
                </div>
                <div class="card-body">
                    @forelse($riwayatTerbaru as $item)
                        <div class="history-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold small">{{ $item->pengajuanSurat->mahasiswa->nama_lengkap }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $item->pengajuanSurat->jenisSurat->nama_surat }}</div>
                                </div>
                                @if($item->status_approval == 'disetujui')
                                    <span class="badge bg-success bg-opacity-10 text-success badge-soft">Setuju</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger badge-soft">Tolak</span>
                                @endif
                            </div>
                            <div class="text-muted mt-1" style="font-size: 0.7rem;">
                                {{ $item->tanggal_approval ? \Carbon\Carbon::parse($item->tanggal_approval)->diffForHumans() : '-' }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small">Belum ada aktivitas.</p>
                    @endforelse
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('pejabat.approval.riwayat') }}" class="btn btn-sm btn-outline-secondary w-100 rounded-pill">Lihat Riwayat Lengkap</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection