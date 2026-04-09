@extends('layouts.app')

@section('title', 'Antrian Approval')

@push('styles')
<style>
    /* Styling Card & Table */
    .card { border: none; border-radius: 15px; overflow: hidden; }
    .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; padding: 1.25rem 1.5rem; }
    
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border-top: none;
        padding: 1rem;
    }
    
    .table tbody tr { transition: all 0.2s; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background-color: #f8fafc; }

    /* Icon Box untuk Mahasiswa/User */
    .icon-box {
        width: 42px; height: 42px;
        background-color: #eff6ff;
        color: #3b82f6;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px; border: 1px solid #dbeafe;
    }

    /* Badge Khusus Approval */
    .badge-level {
        background-color: #fff9ed;
        color: #d97706;
        border: 1px solid #fef3c7;
        font-weight: 700;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
    }

    .badge-surat {
        background-color: #f1f5f9;
        color: #475569;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Action Button (Tinjau) */
    .btn-review {
        background-color: #f59e0b;
        color: #fff;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.2s;
        font-size: 0.85rem;
    }
    .btn-review:hover {
        background-color: #d97706;
        color: #fff;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Antrian Persetujuan Surat</h5>
            <p class="text-muted small mb-0">Tinjau dan berikan keputusan untuk pengajuan surat mahasiswa.</p>
        </div>
        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-bold">
            {{ $antrians->count() }} Perlu Diproses
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Data Mahasiswa</th>
                        <th>Informasi Surat</th>
                        <th class="text-center">Tgl. Diajukan</th>
                        <th class="text-center">Urutan</th>
                        <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antrians as $approval)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark fs-6">{{ $approval->pengajuanSurat->mahasiswa->nama_lengkap }}</div>
                                    <div class="small text-muted">{{ $approval->pengajuanSurat->mahasiswa->programStudi->nama_prodi }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="badge-surat d-inline-block">
                                <i class="bi bi-file-earmark-text-fill me-1 text-warning"></i>
                                {{ $approval->pengajuanSurat->jenisSurat->nama_surat }}
                            </div>
                        </td>
                        <td class="text-center text-muted small">
                            {{ $approval->pengajuanSurat->tanggal_pengajuan->format('d M Y') }}
                        </td>
                        <td class="text-center">
                            <span class="badge-level text-uppercase">
                                Level {{ $approval->urutan_approval }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <a href="{{ route('pejabat.approval.show', $approval) }}" class="btn btn-review shadow-sm d-inline-flex align-items-center">
                                <i class="bi bi-eye-fill me-2"></i> Tinjau
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-check2-circle display-4 text-success opacity-25"></i>
                                <p class="text-muted mt-3">Luar biasa! Tidak ada antrian yang menunggu persetujuan Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection