@extends('layouts.app')

@section('title', 'Riwayat Approval')

@push('styles')
<style>
    /* Styling Card & Table */
    .card { border: none; border-radius: 15px; overflow: hidden; }
    .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; }
    
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border-top: none;
        padding: 1rem;
    }
    
    .table tbody tr { transition: all 0.2s; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background-color: #f8fafc; }

    /* Icon Box Header */
    .icon-history {
        width: 42px; height: 42px;
        background-color: #fffbeb;
        color: #d97706;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px; border: 1px solid #fef3c7;
    }

    /* Base Status Badge Style (Soft UI) */
    .status-badge {
        padding: 0.45rem 0.8rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        display: inline-block;
        border: 1px solid transparent;
    }
    
    /* Action Button (Kuning/Amber) - Sesuai Permintaan */
    .btn-detail {
        background-color: #f59e0b;
        color: #fff;
        border: none;
        padding: 0.45rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.2s;
        font-size: 0.8rem;
    }
    .btn-detail:hover {
        background-color: #d97706;
        color: #fff;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Riwayat Approval Pejabat</h5>
            <p class="text-muted small mb-0">Arsip seluruh keputusan persetujuan yang telah Anda berikan.</p>
        </div>
        <div class="icon-history">
            <i class="bi bi-clock-history fs-5"></i>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Waktu Approval</th>
                        <th>Mahasiswa & Prodi</th>
                        <th>Informasi Surat</th>
                        <th class="text-center">Status Keputusan</th>
                        <th class="text-center pe-4" style="width: 150px;">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayats as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark small">{{ $item->tanggal_approval?->format('d M Y') ?? '-' }}</div>
                            <div class="text-muted" style="font-size: 0.65rem;">Pukul {{ $item->tanggal_approval?->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">{{ $item->pengajuanSurat->mahasiswa->nama_lengkap ?? '-' }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $item->pengajuanSurat->mahasiswa->programStudi->nama_prodi ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="text-secondary small d-inline-block p-1 bg-light rounded px-2" style="font-size: 0.75rem;">
                                <i class="bi bi-file-earmark-text-fill me-1 text-warning"></i> {{ $item->pengajuanSurat->jenisSurat->nama_surat ?? '-' }}
                            </div>
                        </td>
                        <td class="text-center">
                            @if($item->status_approval === 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($item->status_approval === 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">{{ $item->status_approval }}</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <a href="{{ route('pejabat.approval.detail', $item->pengajuanSurat->id) }}" class="btn btn-detail shadow-sm d-inline-flex align-items-center">
                                <i class="bi bi-search me-2" style="font-size: 0.75rem;"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-folder-x display-4 text-light"></i>
                                <p class="text-muted mt-3 small">Belum ada riwayat aktivitas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.badge').forEach(badge => {
            const styles = {
                'bg-success': { bg: '#ecfdf5', text: '#059669', border: '#d1fae5' },
                'bg-danger':  { bg: '#fef2f2', text: '#dc2626', border: '#fee2e2' },
                'bg-secondary': { bg: '#f8fafc', text: '#64748b', border: '#e2e8f0' }
            };

            for (const [bsClass, color] of Object.entries(styles)) {
                if (badge.classList.contains(bsClass)) {
                    badge.style.backgroundColor = color.bg;
                    badge.style.color = color.text;
                    badge.style.border = `1px solid ${color.border}`;
                    badge.classList.remove(bsClass);
                }
            }
            badge.classList.add('status-badge');
        });
    });
</script>
@endpush
@endsection