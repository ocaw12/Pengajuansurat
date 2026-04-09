@extends('layouts.app')

@section('title', 'Riwayat Pengajuan')

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

    /* Search Input Style */
    .search-wrapper {
        position: relative;
        width: 300px;
    }
    .search-wrapper input {
        border-radius: 10px;
        padding-left: 2.5rem;
        border-color: #e2e8f0;
        font-size: 0.85rem;
        height: 40px;
    }
    .search-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .icon-history {
        width: 42px; height: 42px;
        background-color: #eff6ff;
        color: #2563eb;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px; border: 1px solid #dbeafe;
    }

    .status-badge {
        padding: 0.45rem 0.8rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        display: inline-block;
        border: 1px solid transparent;
    }
    
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
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h5 class="mb-0 fw-bold text-dark">Riwayat Pengajuan Surat</h5>
                <p class="text-muted small mb-0">Total: {{ count($pengajuan_surats) }} dokumen</p>
            </div>
            <div class="col-md-8 d-flex justify-content-md-end gap-3 mt-3 mt-md-0">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama surat atau keperluan...">
                </div>
                
                <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary btn-sm rounded-3 px-3 d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i> Buat Baru
                </a>
                <div class="icon-history d-none d-md-flex">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="suratTable">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">#</th>
                        <th>Informasi Surat</th>
                        <th>Deskripsi Keperluan</th>
                        <th>Tanggal & Metode</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4" style="width: 150px;">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan_surats as $index => $pengajuan)
                    <tr class="surat-row">
                        <td class="ps-4 text-muted small fw-bold">
                            {{ $index + 1 }}
                        </td>
                        <td class="search-name">
                            <div class="fw-bold text-dark small">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</div>
                            <div class="text-muted" style="font-size: 0.65rem;">ID Pengajuan: #SRT-{{ $pengajuan->id }}</div>
                        </td>
                        <td class="search-info">
                            <div class="text-secondary small">
                                {{ Str::limit($pengajuan->keperluan, 45) }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</div>
                            <div class="d-inline-block mt-1">
                                <span class="badge-method px-2 py-0 rounded small fw-bold text-uppercase" style="font-size: 0.65rem;">
                                    {{ $pengajuan->metode_pengambilan }}
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($pengajuan->status_pengajuan === 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($pengajuan->status_pengajuan === 'ditolak' || $pengajuan->status_pengajuan === 'perlu_revisi')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif($pengajuan->status_pengajuan === 'menunggu')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @else
                                <span class="badge bg-secondary">{{ $pengajuan->status_pengajuan }}</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}" class="btn btn-detail shadow-sm d-inline-flex align-items-center">
                                <i class="bi bi-search me-2" style="font-size: 0.75rem;"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-folder-x display-4 text-light"></i>
                                <p class="text-muted mt-3 small">Belum ada riwayat pengajuan.</p>
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
        // 1. Logika Pencarian (Filter Table)
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('.surat-row');

        searchInput.addEventListener('keyup', function() {
            const q = searchInput.value.toLowerCase();
            
            rows.forEach(row => {
                const namaSurat = row.querySelector('.search-name').textContent.toLowerCase();
                const infoSurat = row.querySelector('.search-info').textContent.toLowerCase();
                
                if (namaSurat.includes(q) || infoSurat.includes(q)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        // 2. Logika Soft UI Badges
        document.querySelectorAll('.badge').forEach(badge => {
            const styles = {
                'bg-success': { bg: '#ecfdf5', text: '#059669', border: '#d1fae5' },
                'bg-danger':  { bg: '#fef2f2', text: '#dc2626', border: '#fee2e2' },
                'bg-warning': { bg: '#fffbeb', text: '#d97706', border: '#fef3c7' },
                'bg-secondary': { bg: '#f8fafc', text: '#64748b', border: '#e2e8f0' }
            };

            for (const [bsClass, color] of Object.entries(styles)) {
                if (badge.classList.contains(bsClass)) {
                    badge.style.backgroundColor = color.bg;
                    badge.style.color = color.text;
                    badge.style.border = `1px solid ${color.border}`;
                    badge.classList.remove(bsClass);
                    badge.classList.remove('text-dark');
                }
            }
            badge.classList.add('status-badge');
        });
    });
</script>
@endpush
@endsection