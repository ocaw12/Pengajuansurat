@extends('layouts.app')

@section('title', 'Staff Dashboard')

@push('styles')
<style>
    /* Body & Layout */
    body { background-color: #f4f7fa; color: #334155; }
    .main-container { padding: 1.5rem; }

    /* Compact Stats - Fokus pada Angka */
    .stat-box {
        background: #ffffff;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s;
    }
    .stat-box:hover { transform: translateY(-3px); border-color: #cbd5e1; }
    
    .stat-info h2 { font-weight: 800; margin-bottom: 0; font-size: 1.5rem; letter-spacing: -0.5px; }
    .stat-info p { font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; margin: 0; }
    
    .stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }

    /* Table & Card */
    .content-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .card-title-area {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex; justify-content: space-between; align-items: center;
    }
    .card-title-area h6 { font-weight: 700; margin: 0; font-size: 0.9rem; color: #1e293b; }

    .table-custom thead th {
        background: #f8fafc;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #94a3b8;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-custom tbody td {
        padding: 0.75rem 1.25rem;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Small Quick Actions */
    .btn-action {
        padding: 0.35rem 0.8rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="main-container">
    
    <div class="mb-4">
        <h5 class="fw-bold text-dark mb-0">Dashboard Operasional</h5>
        <p class="text-muted small">Ringkasan beban kerja prodi hari ini.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-info">
                    <p>Menunggu Validasi</p>
                    <h2 class="text-primary">{{ $totalPendingValidasi }}</h2>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-info">
                    <p>Perlu Dicetak</p>
                    <h2 class="text-warning">{{ $totalPerluDicetak }}</h2>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-printer"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-info">
                    <p>Siap Diambil</p>
                    <h2 class="text-success">{{ $totalSiapDiambil }}</h2>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-bag-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="card-title-area">
                    <h6><i class="bi bi-list-task me-2 text-primary"></i>Antrian Terbaru</h6>
                    <a href="{{ route('staff_jurusan.validasi.index') }}" class="btn btn-sm btn-light text-primary fw-bold" style="font-size: 0.7rem;">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Jenis Surat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($antrianValidasi as $p)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $p->mahasiswa->nama_lengkap }}</div>
                                    <div class="text-muted small" style="font-size: 0.7rem;">{{ $p->mahasiswa->programStudi->nama_prodi }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark fw-medium border">{{ $p->jenisSurat->nama_surat }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('staff_jurusan.validasi.show', $p->id) }}" class="btn btn-primary btn-action">
                                        Tinjau
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted small">Semua beres! Tidak ada antrian.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card">
                <div class="card-title-area">
                    <h6><i class="bi bi-lightning-fill text-warning me-1"></i>Siap Cetak</h6>
                </div>
                <div class="p-0">
                    @forelse($antrianPerluDicetak as $item)
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                            <div style="max-width: 65%;">
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;">{{ $item->jenisSurat->nama_surat }}</div>
                                <div class="text-muted small text-truncate" style="font-size: 0.7rem;">{{ $item->mahasiswa->nama_lengkap }}</div>
                            </div>
                            <form action="{{ route('staff_jurusan.cetak.siapDiambil', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-action shadow-sm">Siap</button>
                            </form>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted small">Antrian cetak kosong.</div>
                    @endforelse
                </div>
                <div class="p-3 text-center">
                    <a href="{{ route('staff_jurusan.cetak.index') }}" class="text-muted small text-decoration-none fw-bold">Buka Modul Cetak</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection