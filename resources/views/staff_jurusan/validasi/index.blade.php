@extends('layouts.app')

@section('title', 'Antrian Validasi')

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

    /* Icon Box untuk Profil Mahasiswa */
    .icon-box {
        width: 42px; height: 42px;
        background-color: #f8fafc;
        color: #64748b;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px; border: 1px solid #e2e8f0;
    }

    /* Badge Metode Pengambilan */
    .badge-digital {
        background-color: #ecfdf5;
        color: #059669;
        border: 1px solid #d1fae5;
    }
    .badge-fisik {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fee2e2;
    }
    .badge-method {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
    }

    /* Action Button (Periksa) */
    .btn-examine {
        background-color: #f59e0b;
        color: #fff;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.2s;
        font-size: 0.85rem;
    }
    .btn-examine:hover {
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
            <h5 class="mb-0 fw-bold text-dark">Antrian Validasi Surat</h5>
            <p class="text-muted small mb-0">Verifikasi kelengkapan berkas sebelum masuk ke tahap persetujuan.</p>
        </div>
        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-bold">
            <i class="bi bi-clock-history me-1"></i> {{ $pengajuans->count() }} Menunggu
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Mahasiswa</th>
                        <th>Jenis Surat</th>
                        <th class="text-center">Tgl. Diajukan</th>
                        <th class="text-center">Metode</th>
                        <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="bi bi-person-circle fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark fs-6">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
                                    <div class="small text-muted">NIM: {{ $pengajuan->mahasiswa->nim }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">
                                {{ $pengajuan->jenisSurat->nama_surat }}
                            </div>
                        </td>
                        <td class="text-center text-muted small">
                            {{ $pengajuan->tanggal_pengajuan->format('d M Y') }}<br>
                            <span style="font-size: 0.75rem;">Pukul {{ $pengajuan->tanggal_pengajuan->format('H:i') }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-method {{ $pengajuan->metode_pengambilan == 'digital' ? 'badge-digital' : 'badge-fisik' }}">
                                <i class="bi {{ $pengajuan->metode_pengambilan == 'digital' ? 'bi-cloud-download' : 'bi-envelope-paper' }} me-1"></i>
                                {{ $pengajuan->metode_pengambilan }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <a href="{{ route('staff_jurusan.validasi.show', $pengajuan) }}" class="btn btn-examine shadow-sm d-inline-flex align-items-center">
                                <i class="bi bi-shield-check me-2"></i> Periksa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-clipboard-check display-4 text-muted opacity-25"></i>
                                <p class="text-muted mt-3">Semua pengajuan telah divalidasi.</p>
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