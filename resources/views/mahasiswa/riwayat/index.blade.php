@extends('layouts.app')

@section('title', 'Riwayat Pengajuan')
@section('page-title', 'Riwayat')

@push('styles')
<style>
    /* Desktop (unchanged) */
    .card { border: none; border-radius: 15px; overflow: hidden; }
    .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; }
    .table thead th {
        background-color: #f8fafc; text-transform: uppercase;
        font-size: 0.7rem; letter-spacing: 0.05em;
        color: #64748b; border-top: none; padding: 1rem;
    }
    .table tbody tr { transition: all 0.2s; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background-color: #f8fafc; }
    .search-wrapper { position: relative; width: 300px; }
    .search-wrapper input { border-radius: 10px; padding-left: 2.5rem; border-color: #e2e8f0; font-size: 0.85rem; height: 40px; }
    .search-wrapper i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .icon-history { width: 42px; height: 42px; background-color: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; border-radius: 12px; border: 1px solid #dbeafe; }
    .status-badge { padding: 0.45rem 0.8rem; border-radius: 8px; font-weight: 700; font-size: 0.7rem; text-transform: uppercase; display: inline-block; border: 1px solid transparent; }
    .btn-detail { background-color: #f59e0b; color: #fff; border: none; padding: 0.45rem 1.25rem; border-radius: 10px; font-weight: 600; transition: 0.2s; font-size: 0.8rem; }
    .btn-detail:hover { background-color: #d97706; color: #fff; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); transform: translateY(-1px); }

    /* ══ MOBILE RIWAYAT ══ */
    @media (max-width: 767.98px) {
        .desktop-riwayat { display: none !important; }
        .mobile-riwayat  { display: block !important; }

        /* Sticky top search bar */
        .m-search-sticky {
            position: sticky;
            top: 58px; /* navbar height */
            z-index: 100;
            background: #fff;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .m-search-input {
            width: 100%;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            font-size: 0.875rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none;
            transition: border-color 0.15s;
        }
        .m-search-input:focus { border-color: #fbbf24; background: #fff; }
        .m-search-wrap { position: relative; }
        .m-search-wrap i { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }

        /* Count bar */
        .m-count-bar {
            padding: 0.5rem 1.25rem;
            background: #f8fafc;
            font-size: 0.75rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* List items */
        .m-riwayat-item {
            background: #fff;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f8fafc;
            display: flex;
            align-items: center;
            gap: 0.9rem;
            text-decoration: none;
            color: inherit;
            transition: background 0.12s;
            -webkit-tap-highlight-color: transparent;
        }
        .m-riwayat-item:active { background: #fef9f0; }

        .m-riwayat-icon {
            width: 44px; height: 44px;
            border-radius: 13px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .m-riwayat-icon.blue  { background: #eff6ff; }
        .m-riwayat-icon.green { background: #ecfdf5; }
        .m-riwayat-icon.red   { background: #fef2f2; }
        .m-riwayat-icon.amber { background: #fffbeb; }

        .m-riwayat-title { font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
        .m-riwayat-sub   { font-size: 0.72rem; color: #94a3b8; }

        .m-riwayat-right { margin-left: auto; display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; }
        .m-status-chip {
            font-size: 0.65rem; font-weight: 700;
            padding: 3px 9px; border-radius: 20px;
        }
        .m-riwayat-date { font-size: 0.65rem; color: #cbd5e1; }

        /* Empty state */
        .m-empty-state {
            padding: 4rem 2rem;
            text-align: center;
            background: #fff;
        }
        .m-empty-state i { font-size: 3rem; color: #e2e8f0; display: block; margin-bottom: 1rem; }
        .m-empty-state p { color: #94a3b8; font-size: 0.9rem; margin: 0; }

        /* No results row */
        .m-no-results {
            padding: 2rem;
            text-align: center;
            color: #94a3b8;
            font-size: 0.85rem;
            background: #fff;
            display: none;
        }
    }

    @media (min-width: 768px) {
        .mobile-riwayat { display: none !important; }
    }
</style>
@endpush

@section('content')

{{-- ══ DESKTOP ══ --}}
<div class="card shadow-sm desktop-riwayat">
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
                <div class="icon-history d-none d-md-flex"><i class="bi bi-clock-history fs-5"></i></div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="suratTable">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:50px;">#</th>
                        <th>Informasi Surat</th>
                        <th>Deskripsi Keperluan</th>
                        <th>Tanggal & Metode</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4" style="width:150px;">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan_surats as $index => $pengajuan)
                    <tr class="surat-row">
                        <td class="ps-4 text-muted small fw-bold">{{ $index + 1 }}</td>
                        <td class="search-name">
                            <div class="fw-bold text-dark small">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</div>
                            <div class="text-muted" style="font-size:0.65rem;">ID: #SRT-{{ $pengajuan->id }}</div>
                        </td>
                        <td class="search-info">
                            <div class="text-secondary small">{{ Str::limit($pengajuan->keperluan, 45) }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</div>
                            <span class="badge-method px-2 py-0 rounded small fw-bold text-uppercase" style="font-size:0.65rem;">{{ $pengajuan->metode_pengambilan }}</span>
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
                                <i class="bi bi-search me-2" style="font-size:0.75rem;"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-folder-x display-4 text-light"></i>
                            <p class="text-muted mt-3 small">Belum ada riwayat pengajuan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- ══ MOBILE ══ --}}
<div class="mobile-riwayat">

    {{-- Sticky search --}}
    <div class="m-search-sticky">
        <div class="m-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="m-search-input" id="mSearchInput" placeholder="Cari surat atau keperluan…">
        </div>
    </div>

    {{-- Count bar --}}
    <div class="m-count-bar" id="mCountBar">
        {{ count($pengajuan_surats) }} pengajuan
    </div>

    {{-- List --}}
    <div id="mRiwayatList">
        @forelse($pengajuan_surats as $pengajuan)
        @php
            $s = $pengajuan->status_pengajuan;
            $iconClass = match(true) {
                $s === 'selesai' || $s === 'sudah_diambil' => 'green',
                $s === 'ditolak' || $s === 'perlu_revisi'  => 'red',
                $s === 'siap_diambil'                       => 'blue',
                default                                     => 'amber',
            };
            $iconColor = match(true) {
                $s === 'selesai' || $s === 'sudah_diambil' => '#059669',
                $s === 'ditolak' || $s === 'perlu_revisi'  => '#dc2626',
                $s === 'siap_diambil'                       => '#2563eb',
                default                                     => '#d97706',
            };
            $chipBg = match(true) {
                $s === 'selesai' || $s === 'sudah_diambil' => '#ecfdf5',
                $s === 'ditolak' || $s === 'perlu_revisi'  => '#fef2f2',
                $s === 'siap_diambil'                       => '#eff6ff',
                default                                     => '#fffbeb',
            };
            $chipLabel = match(true) {
                $s === 'selesai'        => 'Selesai',
                $s === 'sudah_diambil'  => 'Diambil',
                $s === 'ditolak'        => 'Ditolak',
                $s === 'perlu_revisi'   => 'Revisi',
                $s === 'siap_diambil'   => 'Siap Ambil',
                $s === 'pending'        => 'Pending',
                default                => Str::title(str_replace('_',' ',$s)),
            };
        @endphp
        <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}"
           class="m-riwayat-item"
           data-name="{{ strtolower($pengajuan->jenisSurat->nama_surat ?? '') }}"
           data-info="{{ strtolower($pengajuan->keperluan) }}">

            <div class="m-riwayat-icon {{ $iconClass }}">
                <i class="bi bi-file-earmark-text" style="color:{{ $iconColor }};font-size:1.1rem;"></i>
            </div>

            <div style="flex:1;min-width:0;">
                <div class="m-riwayat-title text-truncate">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</div>
                <div class="m-riwayat-sub text-truncate">{{ Str::limit($pengajuan->keperluan, 40) }}</div>
            </div>

            <div class="m-riwayat-right">
                <span class="m-status-chip" style="background:{{ $chipBg }};color:{{ $iconColor }};">{{ $chipLabel }}</span>
                <span class="m-riwayat-date">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</span>
            </div>
        </a>
        @empty
        <div class="m-empty-state">
            <i class="bi bi-folder-x"></i>
            <p>Belum ada riwayat pengajuan surat.</p>
            <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-warning btn-sm fw-bold mt-3 px-4" style="border-radius:10px;">
                <i class="bi bi-plus-lg me-1"></i> Buat Pengajuan
            </a>
        </div>
        @endforelse
    </div>

    {{-- No results --}}
    <div class="m-no-results" id="mNoResults">
        <i class="bi bi-search me-2"></i>Tidak ada hasil untuk pencarianmu.
    </div>

    <div style="height:1rem;"></div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Desktop search ──
    const desktopSearch = document.getElementById('searchInput');
    if (desktopSearch) {
        const rows = document.querySelectorAll('.surat-row');
        desktopSearch.addEventListener('keyup', function() {
            const q = this.value.toLowerCase();
            rows.forEach(row => {
                const name = row.querySelector('.search-name').textContent.toLowerCase();
                const info = row.querySelector('.search-info').textContent.toLowerCase();
                row.style.display = (name.includes(q) || info.includes(q)) ? '' : 'none';
            });
        });

        // Soft UI badges (desktop)
        document.querySelectorAll('.desktop-riwayat .badge').forEach(badge => {
            const styles = {
                'bg-success': { bg:'#ecfdf5', text:'#059669', border:'#d1fae5' },
                'bg-danger':  { bg:'#fef2f2', text:'#dc2626', border:'#fee2e2' },
                'bg-warning': { bg:'#fffbeb', text:'#d97706', border:'#fef3c7' },
                'bg-secondary':{ bg:'#f8fafc', text:'#64748b', border:'#e2e8f0' }
            };
            for (const [cls, color] of Object.entries(styles)) {
                if (badge.classList.contains(cls)) {
                    badge.style.cssText = `background:${color.bg};color:${color.text};border:1px solid ${color.border};padding:.45rem .8rem;border-radius:8px;font-weight:700;font-size:.7rem;text-transform:uppercase;`;
                }
            }
        });
    }

    // ── Mobile search ──
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
            noResults.style.display = (visible === 0 && q) ? 'block' : 'none';
            countBar.textContent = q
                ? `${visible} dari ${total} pengajuan`
                : `${total} pengajuan`;
        });
    }
});
</script>
@endpush