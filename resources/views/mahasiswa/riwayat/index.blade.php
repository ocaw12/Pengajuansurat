@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Surat')
@section('page-title', 'Riwayat')

@push('styles')
<style>
    /* ══ GLOBAL & DESKTOP STYLES (MATCHED) ══ */
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

    /* Icon Box (Matched to template) */
    .icon-box {
        width: 38px; height: 38px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 10px; border: 1px solid transparent;
    }
    .icon-box.amber { background-color: #fff9ed; color: #f59e0b; border-color: #fef3c7; }
    .icon-box.blue  { background-color: #eff6ff; color: #2563eb; border-color: #dbeafe; }
    .icon-box.green { background-color: #ecfdf5; color: #059669; border-color: #d1fae5; }
    .icon-box.red   { background-color: #fef2f2; color: #dc2626; border-color: #fee2e2; }

    /* Action Buttons (Matched to template) */
    .btn-action {
        width: 34px; height: 34px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 10px; transition: 0.2s; border: none;
        text-decoration: none;
    }
    .btn-view   { background-color: #eff6ff; color: #2563eb; }
    .btn-view:hover   { background-color: #2563eb; color: #fff; }
    .btn-delete { background-color: #fff1f2; color: #e11d48; }
    .btn-delete:hover { background-color: #e11d48; color: #fff; }

    /* Search Input Wrapper */
    .search-wrapper { position: relative; width: 280px; }
    .search-wrapper input { border-radius: 10px; padding-left: 2.3rem; border-color: #e2e8f0; font-size: 0.85rem; height: 38px; }
    .search-wrapper input:focus { border-color: #f59e0b; box-shadow: none; }
    .search-wrapper i { position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; }

    /* Badges & Labels (Matched to template) */
    .badge-code { 
        padding: 0.6em 1.2em; 
        font-weight: 700; 
        font-size: 0.7rem; 
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        display: inline-block;
        text-transform: uppercase;
    }
    .badge-code.pending { background-color: #fff9ed; color: #d97706; }
    .badge-code.success { background-color: #ecfdf5; color: #059669; }
    .badge-code.danger  { background-color: #fff1f2; color: #e11d48; }
    .badge-code.secondary { background-color: #f1f5f9; color: #475569; }

    .sub-label {
        font-size: 0.8rem;
        color: #64748b;
        display: flex;
        align-items: center;
    }

    /* ══ MOBILE RIWAYAT (CLEAN & MATCHED) ══ */
    @media (max-width: 767.98px) {
        .desktop-riwayat { display: none !important; }
        .mobile-riwayat  { display: block !important; }

        .m-search-sticky {
            position: sticky; top: 58px; z-index: 100; background: #fff;
            padding: 0.75rem 1.25rem; border-bottom: 1px solid #f0f0f0;
        }
        .m-search-input {
            width: 100%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 0.55rem 1rem 0.55rem 2.3rem; font-size: 0.85rem; outline: none;
        }
        .m-search-input:focus { border-color: #f59e0b; background: #fff; }
        .m-search-wrap { position: relative; }
        .m-search-wrap i { position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; }

        .m-count-bar {
            padding: 0.5rem 1.25rem; background: #f8fafc; font-size: 0.72rem;
            font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid #f1f5f9;
        }

        .m-riwayat-item {
            background: #fff; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: 0.85rem; text-decoration: none; color: inherit;
        }
        .m-riwayat-item:active { background: #fff9ed; }

        .m-riwayat-title { font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 1px; }
        .m-riwayat-sub   { font-size: 0.75rem; color: #64748b; }

        .m-riwayat-right { margin-left: auto; display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
        .m-status-chip { font-size: 0.65rem; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; }
        .m-riwayat-date { font-size: 0.65rem; color: #94a3b8; }

        .m-empty-state, .m-no-results { padding: 3rem 2rem; text-align: center; color: #64748b; font-size: 0.85rem; background: #fff; }
        .m-empty-state i, .m-no-results i { font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 0.75rem; }
    }

    @media (min-width: 768px) {
        .mobile-riwayat { display: none !important; }
    }
</style>
@endpush

@section('content')

{{-- ══ DESKTOP VIEW ══ --}}
<div class="card shadow-sm desktop-riwayat">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Riwayat Pengajuan Surat</h5>
            <p class="text-muted small mb-0">Pantau status pengajuan dokumen dan surat mahasiswa.</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari surat atau keperluan...">
            </div>
            <a href="{{ route('mahasiswa.pengajuan.create') }}"
               class="btn btn-warning px-4 rounded-pill shadow-sm fw-bold text-dark btn-sm d-flex align-items-center" style="height: 38px;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Pengajuan
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="suratTable">
                <thead>
                    <tr>
                        <th class="ps-4 text-center" style="width: 70px;">No</th>
                        <th>Informasi Surat</th>
                        <th>Deskripsi Keperluan</th>
                        <th>Metode Ambil</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuan_surats as $index => $pengajuan)
                        @php
                            $s = $pengajuan->status_pengajuan;
                            $theme = match(true) {
                                $s === 'selesai' || $s === 'sudah_diambil' => 'green',
                                $s === 'ditolak' || $s === 'perlu_revisi'  => 'red',
                                $s === 'menunggu' || $s === 'pending'       => 'amber',
                                default                                    => 'blue',
                            };
                        @endphp
                        <tr class="surat-row">
                            <td class="ps-4 text-center text-muted fw-medium">{{ $index + 1 }}</td>
                            <td class="search-name">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $theme }} me-3">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</div>
                                        <div class="small text-muted">ID: #SRT-{{ $pengajuan->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="search-info">
                                <div class="text-dark small fw-medium">{{ Str::limit($pengajuan->keperluan, 45) }}</div>
                                <div class="small text-muted">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div class="sub-label text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                    <i class="bi bi-box-seam me-2 text-warning"></i>
                                    {{ str_replace('_', ' ', $pengajuan->metode_pengambilan) }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($s === 'selesai' || $s === 'sudah_diambil')
                                    <span class="badge-code success">Selesai</span>
                                @elseif($s === 'ditolak' || $s === 'perlu_revisi')
                                    <span class="badge-code danger">Ditolak</span>
                                @elseif($s === 'menunggu')
                                    <span class="badge-code amber">Menunggu</span>
                                @else
                                    <span class="badge-code secondary">{{ str_replace('_', ' ', $s) }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}"
                                       class="btn-action btn-view" data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if($s === 'menunggu')
                                    <button type="button" class="btn-action btn-delete" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $pengajuan->id }}" 
                                            title="Batalkan">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-folder2-open display-4 text-muted opacity-25"></i>
                                    <p class="text-muted mt-3">Belum ada riwayat pengajuan surat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- ══ MOBILE VIEW ══ --}}
<div class="mobile-riwayat">
    <div class="m-search-sticky">
        <div class="m-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="m-search-input" id="mSearchInput" placeholder="Cari nama surat atau keperluan…">
        </div>
    </div>

    <div class="m-count-bar" id="mCountBar">
        {{ count($pengajuan_surats) }} total pengajuan
    </div>

    <div id="mRiwayatList">
        @forelse($pengajuan_surats as $pengajuan)
        @php
            $s = $pengajuan->status_pengajuan;
            $theme = match(true) {
                $s === 'selesai' || $s === 'sudah_diambil' => 'green',
                $s === 'ditolak' || $s === 'perlu_revisi'  => 'red',
                $s === 'menunggu' || $s === 'pending'       => 'amber',
                default                                    => 'blue',
            };
            $chipStyles = match(true) {
                $s === 'selesai' || $s === 'sudah_diambil' => 'background-color: #ecfdf5; color: #059669;',
                $s === 'ditolak' || $s === 'perlu_revisi'  => 'background-color: #fff1f2; color: #e11d48;',
                $s === 'menunggu'                          => 'background-color: #fff9ed; color: #d97706;',
                default                                    => 'background-color: #f1f5f9; color: #475569;',
            };
            $chipLabel = match(true) {
                $s === 'selesai'       => 'Selesai',
                $s === 'sudah_diambil' => 'Diambil',
                $s === 'ditolak'       => 'Ditolak',
                $s === 'perlu_revisi'  => 'Revisi',
                $s === 'menunggu'      => 'Menunggu',
                default                => Str::title(str_replace('_',' ',$s)),
            };
        @endphp
        <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}"
           class="m-riwayat-item"
           data-name="{{ strtolower($pengajuan->jenisSurat->nama_surat ?? '') }}"
           data-info="{{ strtolower($pengajuan->keperluan) }}">

            <div class="icon-box {{ $theme }} flex-shrink-0" style="width: 40px; height: 40px;">
                <i class="bi bi-file-earmark-text-fill" style="font-size: 1.1rem;"></i>
            </div>

            <div style="flex:1; min-width:0;">
                <div class="m-riwayat-title text-truncate">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</div>
                <div class="m-riwayat-sub text-truncate">{{ $pengajuan->keperluan }}</div>
            </div>

            <div class="m-riwayat-right">
                <span class="m-status-chip" style="{{ $chipStyles }}">{{ $chipLabel }}</span>
                <span class="m-riwayat-date">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</span>
            </div>
        </a>
        @empty
        <div class="m-empty-state">
            <i class="bi bi-folder2-open"></i>
            <p>Belum ada riwayat pengajuan surat.</p>
            <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-warning btn-sm fw-bold px-4 rounded-pill mt-3 text-dark">
                Tambah Pengajuan
            </a>
        </div>
        @endforelse
    </div>

    <div class="m-no-results" id="mNoResults">
        <i class="bi bi-search"></i>
        <p>Tidak ada hasil pencarian yang cocok.</p>
    </div>
</div>

{{-- MODAL PEMBATALAN (MATCHED TO DELETE MODAL) --}}
@foreach ($pengajuan_surats as $pengajuan)
@if($pengajuan->status_pengajuan === 'menunggu')
<div class="modal fade" id="deleteModal{{ $pengajuan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-octagon-fill" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold">Batalkan Pengajuan?</h5>
                <p class="text-muted small">Pengajuan untuk <strong>{{ $pengajuan->jenisSurat->nama_surat ?? 'Surat' }}</strong> akan dibatalkan permanen.</p>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('mahasiswa.pengajuan.destroy', $pengajuan->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger rounded-pill px-4">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Desktop Realtime Filter ──
    const desktopSearch = document.getElementById('searchInput');
    if (desktopSearch) {
        const rows = document.querySelectorAll('.surat-row');
        desktopSearch.addEventListener('keyup', function() {
            const q = this.value.toLowerCase().trim();
            rows.forEach(row => {
                const name = row.querySelector('.search-name').textContent.toLowerCase();
                const info = row.querySelector('.search-info').textContent.toLowerCase();
                row.style.display = (name.includes(q) || info.includes(q)) ? '' : 'none';
            });
        });
    }

    // ── Mobile Realtime Filter ──
    const mSearch = document.getElementById('mSearchInput');
    if (mSearch) {
        const items     = document.querySelectorAll('.m-riwayat-item');
        const noResults = document.getElementById('mNoResults');
        const countBar  = document.getElementById('mCountBar');
        const total     = items.length;

        mSearch.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            let visible = 0;
            items.forEach(item => {
                const match = item.dataset.name.includes(q) || item.dataset.info.includes(q);
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if(noResults) noResults.style.display = (visible === 0 && q) ? 'block' : 'none';
            if(countBar) countBar.textContent = q ? `${visible} dari ${total} ditemukan` : `${total} total pengajuan`;
        });
    }
});
</script>
@endpush