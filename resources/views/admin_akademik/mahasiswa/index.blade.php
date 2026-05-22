@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')
@section('page-title', 'Daftar Mahasiswa')

@push('styles')
<style>
    .container-fluid { max-width: 1400px; padding-top: 0.5rem !important; }

    .card { border: none; border-radius: 15px; overflow: hidden; }
    .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; padding: 1.25rem 1.5rem; }
    
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border-top: none;
    }
    
    .table tbody tr { transition: all 0.2s; }
    .table tbody tr:hover { background-color: #f1f5f9; }

    .avatar-circle {
        width: 40px; height: 40px;
        background-color: #d1fae5; 
        color: #059669;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-weight: bold; font-size: 0.9rem;
        flex-shrink: 0;
    }

    .btn-action {
        width: 32px; height: 32px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; transition: 0.2s; border: none; text-decoration: none;
    }
    .btn-show { background-color: #e0f2fe; color: #0369a1; }
    .btn-show:hover { background-color: #bae6fd; }
    .btn-edit { background-color: #fef9c3; color: #a16207; }
    .btn-edit:hover { background-color: #fde047; }
    .btn-delete { background-color: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background-color: #fecaca; }

    .badge-prodi { padding: 0.5em 1em; font-weight: 600; font-size: 0.75rem; background-color: #f1f5f9; color: #475569; border-radius: 50px; }
    
    .import-container { display: flex; align-items: center; gap: 8px; border-right: 1px solid #e2e8f0; padding-right: 15px; margin-right: 10px; }

    /* ── Search & Sort Bar ── */
    .filter-bar {
        padding: 0.875rem 1.5rem;
        background: #fafbfc;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-wrapper {
        position: relative;
        flex: 1;
        min-width: 220px;
        max-width: 380px;
    }
    .search-wrapper .bi-search {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
    }
    .search-wrapper input {
        padding-left: 34px;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
        height: 36px;
        width: 100%;
        background: #fff;
        color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-wrapper input:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5,150,105,0.12);
    }

    /* Sort buttons */
    .sort-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .sort-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }
    .btn-sort {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        background: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-sort:hover {
        border-color: #059669;
        color: #059669;
        background: #f0fdf4;
    }
    .btn-sort.active {
        background: #d1fae5;
        border-color: #059669;
        color: #047857;
    }
    .btn-sort .sort-icon { font-size: 0.7rem; }

    /* Reset link */
    .btn-reset {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.78rem; color: #94a3b8;
        text-decoration: none; padding: 5px 8px;
        border-radius: 50px; transition: color 0.15s;
    }
    .btn-reset:hover { color: #ef4444; }

    /* Sort indicator on column header */
    .th-sortable { cursor: pointer; user-select: none; white-space: nowrap; }
    .th-sortable .sort-caret { margin-left: 4px; font-size: 0.65rem; color: #cbd5e1; }
    .th-sortable.active .sort-caret { color: #059669; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        {{-- ── Card Header ── --}}
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="mb-0 fw-bold text-dark">Daftar Mahasiswa</h5>
                <p class="text-muted small mb-0">Kelola data akademik dan akun mahasiswa.</p>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin_akademik.mahasiswa.template') }}"
                   class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="bi bi-download"></i> Template Excel
                </a>
                <form action="{{ route('admin_akademik.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="import-container d-none d-md-flex">
                    @csrf
                    <input type="file" name="file" class="form-control form-control-sm" style="width: 180px;" accept=".xlsx,.csv" required>
                    <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3">
                        <i class="bi bi-upload"></i> Import
                    </button>
                </form>
                <a href="{{ route('admin_akademik.mahasiswa.create') }}"
                   class="btn btn-warning px-4 rounded-pill shadow-sm fw-bold">
                    <i class="bi bi-person-plus-fill me-2"></i> Tambah Mahasiswa
                </a>
            </div>
        </div>

        {{-- ── Filter Bar (Search + Sort) ── --}}
        <div class="filter-bar">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('admin_akademik.mahasiswa.index') }}" class="d-flex align-items-center gap-2 flex-wrap flex-grow-1" id="filterForm">
                {{-- Pertahankan nilai sort saat search berubah --}}
                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'nama') }}">
                <input type="hidden" name="sort_dir" value="{{ request('sort_dir', 'asc') }}">

                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        value="{{ request('search') }}"
                        placeholder="Cari nama atau NIM…"
                        autocomplete="off"
                    >
                </div>

                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3" style="height:36px;">
                    <i class="bi bi-search me-1"></i> Cari
                </button>

                @if(request()->hasAny(['search', 'sort_by', 'sort_dir']))
                    <a href="{{ route('admin_akademik.mahasiswa.index') }}" class="btn-reset">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                @endif
            </form>

            {{-- Sort Buttons --}}
            <div class="sort-group">
                <span class="sort-label"><i class="bi bi-arrow-down-up me-1"></i>Urutkan:</span>

                @php
                    $currentSort  = request('sort_by', 'nama');
                    $currentDir   = request('sort_dir', 'asc');
                    $currentSearch = request('search');

                    // Helper: buat URL sort dengan mempertahankan search
                    $sortUrl = fn($by, $dir) => route('admin_akademik.mahasiswa.index', array_filter([
                        'search'   => $currentSearch,
                        'sort_by'  => $by,
                        'sort_dir' => $dir,
                    ]));
                @endphp

                {{-- Nama A→Z --}}
                <a href="{{ $sortUrl('nama', 'asc') }}"
                   class="btn-sort {{ $currentSort === 'nama' && $currentDir === 'asc' ? 'active' : '' }}">
                    <i class="bi bi-person sort-icon"></i> Nama
                    <i class="bi bi-sort-alpha-down sort-icon"></i>
                </a>

                {{-- Nama Z→A --}}
                <a href="{{ $sortUrl('nama', 'desc') }}"
                   class="btn-sort {{ $currentSort === 'nama' && $currentDir === 'desc' ? 'active' : '' }}">
                    <i class="bi bi-person sort-icon"></i> Nama
                    <i class="bi bi-sort-alpha-up sort-icon"></i>
                </a>

                {{-- NIM 0→9 --}}
                <a href="{{ $sortUrl('nim', 'asc') }}"
                   class="btn-sort {{ $currentSort === 'nim' && $currentDir === 'asc' ? 'active' : '' }}">
                    <i class="bi bi-hash sort-icon"></i> NIM
                    <i class="bi bi-sort-numeric-down sort-icon"></i>
                </a>

                {{-- NIM 9→0 --}}
                <a href="{{ $sortUrl('nim', 'desc') }}"
                   class="btn-sort {{ $currentSort === 'nim' && $currentDir === 'desc' ? 'active' : '' }}">
                    <i class="bi bi-hash sort-icon"></i> NIM
                    <i class="bi bi-sort-numeric-up sort-icon"></i>
                </a>
            </div>
        </div>

        {{-- ── Tabel ── --}}
        <div class="card-body p-0">
            {{-- Info hasil pencarian --}}
            @if(request('search'))
            <div class="px-4 py-2 border-bottom bg-white">
                <small class="text-muted">
                    Menampilkan hasil untuk <strong>"{{ request('search') }}"</strong>
                    — ditemukan <strong>{{ $mahasiswas->total() }}</strong> mahasiswa.
                </small>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>
                                {{-- Klik header Mahasiswa untuk toggle sort nama --}}
                                <a href="{{ $sortUrl('nama', $currentSort === 'nama' && $currentDir === 'asc' ? 'desc' : 'asc') }}"
                                   class="th-sortable d-inline-flex align-items-center text-decoration-none text-uppercase {{ $currentSort === 'nama' ? 'active text-success' : 'text-secondary' }}"
                                   style="font-size:0.75rem; letter-spacing:0.05em; font-weight:600;">
                                    Mahasiswa
                                    <i class="bi {{ $currentSort === 'nama' ? ($currentDir === 'asc' ? 'bi-caret-up-fill' : 'bi-caret-down-fill') : 'bi-caret-down' }} sort-caret"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ $sortUrl('nim', $currentSort === 'nim' && $currentDir === 'asc' ? 'desc' : 'asc') }}"
                                   class="th-sortable d-inline-flex align-items-center text-decoration-none text-uppercase {{ $currentSort === 'nim' ? 'active text-success' : 'text-secondary' }}"
                                   style="font-size:0.75rem; letter-spacing:0.05em; font-weight:600;">
                                    NIM
                                    <i class="bi {{ $currentSort === 'nim' ? ($currentDir === 'asc' ? 'bi-caret-up-fill' : 'bi-caret-down-fill') : 'bi-caret-down' }} sort-caret"></i>
                                </a>
                            </th>
                            <th>Program Studi</th>
                            <th>Kontak</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswas as $index => $mhs)
                            <tr>
                                <td class="ps-4 text-muted">{{ $mahasiswas->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            {{ strtoupper(substr($mhs->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                {{-- Highlight teks pencarian pada nama --}}
                                                @if(request('search'))
                                                    {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="p-0 bg-warning bg-opacity-50 rounded">$1</mark>', e($mhs->nama_lengkap)) !!}
                                                @else
                                                    {{ $mhs->nama_lengkap }}
                                                @endif
                                            </div>
                                            <div class="small text-muted">{{ $mhs->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        {{-- Highlight teks pencarian pada NIM --}}
                                        @if(request('search'))
                                            {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="p-0 bg-warning bg-opacity-50 rounded">$1</mark>', e($mhs->nim)) !!}
                                        @else
                                            {{ $mhs->nim }}
                                        @endif
                                    </div>
                                    <div class="small text-muted">Nomor Induk</div>
                                </td>
                                <td>
                                    <span class="badge-prodi">
                                        {{ $mhs->programStudi->nama_prodi }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-muted"><i class="bi bi-whatsapp me-1"></i> {{ $mhs->no_telepon ?? '-' }}</div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin_akademik.mahasiswa.show', $mhs->id) }}"
                                           class="btn-action btn-show" title="Detail Mahasiswa">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('admin_akademik.mahasiswa.edit', $mhs->id) }}"
                                           class="btn-action btn-edit" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-delete" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $mhs->id }}" 
                                                title="Hapus Data">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-search fs-1 d-block mb-2 opacity-25"></i>
                                    @if(request('search'))
                                        Tidak ada mahasiswa dengan kata kunci <strong>"{{ request('search') }}"</strong>.
                                    @else
                                        Belum ada data mahasiswa yang terdaftar.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($mahasiswas->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            <div class="d-flex justify-content-center">
                {{-- Teruskan parameter search & sort ke pagination --}}
                {{ $mahasiswas->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODAL HAPUS --}}
@foreach ($mahasiswas as $mhs)
<div class="modal fade" id="deleteModal{{ $mhs->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-octagon-fill" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold">Hapus Mahasiswa?</h5>
                <p class="text-muted small">Menghapus <strong>{{ $mhs->nama_lengkap }}</strong> akan menghilangkan akses akunnya.</p>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin_akademik.mahasiswa.destroy', $mhs->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger rounded-pill px-4">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    // Auto-submit saat input pencarian di-clear dengan tombol × browser
    document.getElementById('searchInput').addEventListener('search', function () {
        document.getElementById('filterForm').submit();
    });
</script>
@endpush
@endsection