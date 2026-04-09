@extends('layouts.app')

@section('title', 'Riwayat Surat')

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

    /* Icon Box untuk Riwayat (Arsip) */
    .icon-box {
        width: 42px; height: 42px;
        background-color: #f8fafc;
        color: #64748b;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px; border: 1px solid #e2e8f0;
    }

    /* Status Badge Style (Soft UI) */
    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        display: inline-block;
    }
    
    /* Action Button (Detail) */
    .btn-detail {
        background-color: #f59e0b;
        color: #fff;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.2s;
        font-size: 0.85rem;
    }
    .btn-detail:hover {
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
            <h5 class="mb-0 fw-bold text-dark">Riwayat Pengajuan Surat</h5>
            <p class="text-muted small mb-0">Daftar arsip seluruh pengajuan surat yang telah diproses.</p>
        </div>
        <div class="icon-box">
            <i class="bi bi-archive-fill fs-5"></i>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Mahasiswa</th>
                        <th>Jenis Surat</th>
                        <th class="text-center">Tgl. Selesai</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="bi bi-person-circle fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark fs-6">{{ $item->mahasiswa->nama_lengkap ?? '-' }}</div>
                                    <div class="small text-muted">NIM: {{ $item->mahasiswa->nim ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">
                                {{ $item->jenisSurat->nama_surat }}
                            </div>
                        </td>
                        <td class="text-center text-muted small">
                            {{ $item->tanggal_pengajuan->format('d M Y') }}<br>
                            <span style="font-size: 0.75rem;">Pukul {{ $item->tanggal_pengajuan->format('H:i') }}</span>
                        </td>
                        <td class="text-center">
                            {{-- Pastikan partials ini menghasilkan class yang warnanya soft --}}
                            <div class="status-container">
                                @include('partials.status_badge', [
                                    'status' => $item->status_pengajuan
                                ])
                            </div>
                        </td>
                        <td class="text-center pe-4">
                            <a href="{{ route('staff_jurusan.validasi.detailRiwayat', $item->id) }}" class="btn btn-detail shadow-sm d-inline-flex align-items-center">
                                <i class="bi bi-eye-fill me-2"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-inbox display-4 text-muted opacity-25"></i>
                                <p class="text-muted mt-3">Belum ada riwayat surat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script untuk memastikan warna badge status tidak terlalu kontras --}}
@push('scripts')
<script>
    document.querySelectorAll('.badge').forEach(badge => {
        // Mengubah warna default Bootstrap menjadi Soft/Pastel Palette
        if(badge.classList.contains('bg-success')) {
            badge.style.backgroundColor = '#ecfdf5'; 
            badge.style.color = '#059669';
            badge.style.border = '1px solid #d1fae5';
            badge.classList.remove('bg-success');
        }
        if(badge.classList.contains('bg-danger')) {
            badge.style.backgroundColor = '#fef2f2';
            badge.style.color = '#dc2626';
            badge.style.border = '1px solid #fee2e2';
            badge.classList.remove('bg-danger');
        }
        if(badge.classList.contains('bg-warning')) {
            badge.style.backgroundColor = '#fff9ed';
            badge.style.color = '#d97706';
            badge.style.border = '1px solid #fef3c7';
            badge.classList.remove('bg-warning');
        }
        if(badge.classList.contains('bg-info')) {
            badge.style.backgroundColor = '#eff6ff';
            badge.style.color = '#2563eb';
            badge.style.border = '1px solid #dbeafe';
            badge.classList.remove('bg-info');
        }
        // Tambahkan class umum untuk padding dan radius
        badge.classList.add('status-badge');
    });
</script>
@endpush

@endsection