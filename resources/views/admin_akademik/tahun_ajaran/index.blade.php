@extends('layouts.app')

@section('title', 'Tahun Ajaran & Semester')
@section('page-title', 'Tahun Ajaran & Semester')

@push('styles')
<style>
    /* ─── Layout ─── */
    .ta-page { max-width: 1100px; }

    /* ─── Header Strip ─── */
    .ta-header {
        background: #fff;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    /* ─── Panel Form Tambah ─── */
    .ta-form-panel {
        background: #fffdf5;
        border: 1px solid #fef3c7;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .ta-form-panel .form-control {
        border-radius: 9px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        font-size: 0.9rem;
        padding: 0.55rem 0.9rem;
    }
    .ta-form-panel .form-control:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
    }

    /* ─── Card Tahun Ajaran ─── */
    .ta-card {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 16px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .ta-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
    .ta-card.is-active { border-left: 4px solid #f59e0b; }

    /* ─── Header baris tahun ajaran ─── */
    .ta-card-header {
        display: flex;
        align-items: center;
        padding: 1rem 1.25rem;
        gap: 0.75rem;
        background: #fafafa;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }
    .ta-year-badge {
        font-size: 1rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.3px;
    }
    .ta-status-chip {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .chip-active   { background: #ecfdf5; color: #059669; }
    .chip-inactive { background: #f1f5f9; color: #64748b; }

    /* ─── Aksi Header ─── */
    .ta-actions { margin-left: auto; display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-sm-icon {
        width: 30px; height: 30px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; border: none; transition: 0.15s; cursor: pointer;
        font-size: 0.8rem;
    }
    .btn-edit-ta  { background: #fef9c3; color: #a16207; }
    .btn-edit-ta:hover { background: #fde047; }
    .btn-del-ta   { background: #fee2e2; color: #b91c1c; }
    .btn-del-ta:hover { background: #fecaca; }
    .btn-aktif-ta { background: #d1fae5; color: #065f46; font-size: 0.7rem; width: auto; padding: 0 10px; height: 30px; border-radius: 8px; border: none; font-weight: 700; }
    .btn-aktif-ta:hover { background: #a7f3d0; }

    /* ─── Body semester ─── */
    .ta-card-body { padding: 0.85rem 1.25rem; }
    .semester-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

    @media (max-width: 576px) {
        .semester-grid { grid-template-columns: 1fr; }
    }

    /* ─── Semester tile ─── */
    .sem-tile {
        border: 1.5px solid #e8edf2;
        border-radius: 12px;
        padding: 0.9rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: border-color 0.15s;
    }
    .sem-tile.sem-active {
        border-color: #f59e0b;
        background: #fffdf5;
    }
    .sem-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .sem-icon.ganjil { background: #eff6ff; color: #2563eb; }
    .sem-icon.genap  { background: #fdf4ff; color: #9333ea; }
    .sem-icon.ganjil.active { background: #fffbeb; color: #d97706; }
    .sem-icon.genap.active  { background: #fffbeb; color: #d97706; }

    .sem-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #1e293b;
    }
    .sem-sublabel {
        font-size: 0.68rem;
        color: #94a3b8;
        margin-top: 1px;
    }
    .sem-right { margin-left: auto; display: flex; gap: 5px; align-items: center; }

    .btn-aktif-sem {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 6px;
        border: none;
        background: #d1fae5;
        color: #065f46;
        cursor: pointer;
        transition: 0.15s;
    }
    .btn-aktif-sem:hover { background: #a7f3d0; }
    .btn-aktif-sem:disabled {
        background: #ecfdf5;
        color: #059669;
        cursor: default;
        opacity: 0.8;
    }

    /* ─── Modal edit inline ─── */
    .modal-content { border: none; border-radius: 16px; }

    /* ─── Empty state ─── */
    .ta-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
    }
    .ta-empty i { font-size: 3rem; opacity: 0.3; display: block; margin-bottom: 0.75rem; }

    /* ─── Pagination ─── */
    .ta-pagination { display: flex; justify-content: center; margin-top: 1rem; }
</style>
@endpush

@section('content')
<div class="ta-page">

    {{-- ════════════════════════════
         HEADER
    ════════════════════════════ --}}
    <div class="ta-header shadow-sm">
        <div>
            <h4 class="fw-bold text-dark mb-0">
                <i class="bi bi-calendar-range me-2 text-warning"></i>Tahun Ajaran & Semester
            </h4>
            <p class="text-muted small mb-0">Kelola periode akademik dan aktifkan semester yang sedang berjalan.</p>
        </div>
    </div>

    

    {{-- ════════════════════════════
         FORM TAMBAH TAHUN AJARAN
    ════════════════════════════ --}}
    <div class="ta-form-panel shadow-sm">
        <h6 class="fw-bold text-dark mb-3">
            <i class="bi bi-plus-circle-fill text-warning me-2"></i>Tambah Tahun Ajaran Baru
        </h6>
        <form action="{{ route('admin_akademik.tahun-ajaran.store') }}" method="POST">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-dark mb-1">Tahun Ajaran</label>
                    <input type="text"
                           name="tahun"
                           class="form-control @error('tahun') is-invalid @enderror"
                           placeholder="Contoh: 2025/2026"
                           value="{{ old('tahun') }}"
                           required>
                    @error('tahun')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text small text-muted">Format: YYYY/YYYY. Semester GANJIL & GENAP akan dibuat otomatis.</div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-warning fw-bold rounded-pill px-4 shadow-sm" type="submit">
                        <i class="bi bi-save-fill me-2"></i>Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ════════════════════════════
         DAFTAR TAHUN AJARAN
    ════════════════════════════ --}}
    @forelse($tahunAjarans as $ta)
    <div class="ta-card {{ $ta->is_aktif ? 'is-active' : '' }}">

        {{-- Header baris --}}
        <div class="ta-card-header">
            <i class="bi bi-calendar3 text-warning fs-5"></i>
            <span class="ta-year-badge">{{ $ta->tahun }}</span>

            @if($ta->is_aktif)
                <span class="ta-status-chip chip-active"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem;vertical-align:middle;"></i>Aktif</span>
            @else
                <span class="ta-status-chip chip-inactive">Tidak Aktif</span>
            @endif

            {{-- Semester aktif info --}}
            @php
                $semAktif = $ta->semesters->firstWhere('is_aktif', true);
            @endphp
            @if($semAktif)
                <span class="ta-status-chip" style="background:#fffbeb;color:#d97706;">
                    <i class="bi bi-play-circle-fill me-1"></i>{{ $semAktif->semester }} Berjalan
                </span>
            @endif

            {{-- Aksi --}}
            <div class="ta-actions">
                @if(!$ta->is_aktif)
                <form action="{{ route('admin_akademik.tahun-ajaran.aktifkan', $ta->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-aktif-ta" title="Aktifkan Tahun Ajaran">
                        <i class="bi bi-toggle-off me-1"></i>Aktifkan
                    </button>
                </form>
                @endif

                <button class="btn-sm-icon btn-edit-ta"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal{{ $ta->id }}"
                        title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </button>

                @if(!$ta->is_aktif)
<form action="{{ route('admin_akademik.tahun-ajaran.destroy', $ta->id) }}" 
      method="POST" 
      class="d-inline" 
      id="delete-form-{{ $ta->id }}">
    @csrf
    @method('DELETE')
    <button type="button" 
            class="btn-sm-icon btn-del-ta" 
            onclick="handleDelete('{{ $ta->id }}', '{{ $ta->tahun }}')">
        <i class="bi bi-trash3-fill"></i>
    </button>
</form>
@endif
            </div>
        </div>

        {{-- Body: Grid Semester --}}
        <div class="ta-card-body">
            @if($ta->semesters->count() > 0)
            <div class="semester-grid">
                @foreach($ta->semesters->sortBy('semester') as $sem)
                @php
                    $isGanjil = $sem->semester === 'GANJIL';
                    $iconClass = $isGanjil ? 'ganjil' : 'genap';
                    $icon      = $isGanjil ? 'bi-sun-fill' : 'bi-moon-stars-fill';
                    if($sem->is_aktif) $iconClass .= ' active';
                @endphp
                <div class="sem-tile {{ $sem->is_aktif ? 'sem-active' : '' }}">
                    <div class="sem-icon {{ $iconClass }}">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div>
                        <div class="sem-label">{{ $sem->semester }}</div>
                        <div class="sem-sublabel">{{ $ta->tahun }}</div>
                    </div>
                    <div class="sem-right">
                        @if($sem->is_aktif)
                            <span class="ta-status-chip chip-active" style="font-size:0.6rem;">
                                <i class="bi bi-play-fill me-1"></i>Berjalan
                            </span>
                        @else
                            <form action="{{ route('admin_akademik.semester.aktifkan', $sem->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="btn-aktif-sem"
                                        onclick="return confirm('Aktifkan semester {{ $sem->semester }} — {{ $ta->tahun }}?')">
                                    Aktifkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-muted small mb-0"><i class="bi bi-exclamation-circle me-1"></i>Belum ada data semester.</p>
            @endif
        </div>
    </div>

    {{-- Modal Edit Tahun Ajaran --}}
    <div class="modal fade" id="editModal{{ $ta->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Tahun Ajaran</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <form action="{{ route('admin_akademik.tahun-ajaran.update', $ta->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Tahun Ajaran</label>
                            <input type="text" name="tahun"
                                   class="form-control"
                                   value="{{ $ta->tahun }}"
                                   style="border-radius:9px;border:1.5px solid #e2e8f0;"
                                   required>
                            <div class="form-text small text-muted">Format: 2025/2026</div>
                        </div>
                        <button type="submit" class="btn btn-warning fw-bold rounded-pill w-100 shadow-sm">
                            <i class="bi bi-save-fill me-2"></i>Perbarui
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @empty
    <div class="ta-empty">
        <i class="bi bi-calendar-x"></i>
        <p class="fw-semibold">Belum ada data tahun ajaran.</p>
        <p class="small">Gunakan form di atas untuk menambahkan.</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($tahunAjarans->hasPages())
    <div class="ta-pagination">
        {{ $tahunAjarans->links() }}
    </div>
    @endif

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function handleDelete(id, tahun) {
        Swal.fire({
            title: 'Hapus Data?',
            html: `Tahun ajaran <strong>${tahun}</strong> akan dihapus permanen dari sistem.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48', // Warna merah sesuai style btn-delete kamu
            cancelButtonColor: '#64748b', // Warna abu-abu muted
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'btn btn-danger px-4 rounded-pill fw-bold ms-2',
                cancelButton: 'btn btn-light px-4 rounded-pill fw-bold'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika user menekan "Ya"
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection