@extends('layouts.app')

@section('title', 'Daftar Pejabat')
@section('page-title', 'Daftar Pejabat')

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
    }
    
    .table tbody tr { transition: all 0.2s; }
    .table tbody tr:hover { background-color: #f1f5f9; }

    /* Avatar & Info Styling */
    .avatar-circle {
        width: 40px; height: 40px;
        background-color: #e2e8f0;
        color: #475569;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-weight: bold; font-size: 0.9rem;
    }

    /* Action Buttons */
    .btn-action {
        width: 32px; height: 32px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; transition: 0.2s; border: none;
    }
    .btn-edit { background-color: #fef9c3; color: #a16207; }
    .btn-edit:hover { background-color: #fde047; }
    .btn-delete { background-color: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background-color: #fecaca; }

    /* Custom Badge */
    .badge-status { padding: 0.5em 1em; font-weight: 600; font-size: 0.75rem; }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Daftar Pejabat Aktif</h5>
            <p class="text-muted small mb-0">Kelola data pejabat tingkat Prodi, Fakultas, dan Universitas.</p>
        </div>
        <a href="{{ route('admin_akademik.pejabat.create') }}"
           class="btn btn-warning px-4 rounded-pill shadow-sm fw-bold">
            <i class="bi bi-person-plus-fill me-2"></i> Tambah Pejabat
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Pejabat</th>
                        <th>Identitas</th>
                        <th>Kontak</th>
                        <th>Penempatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pejabat as $index => $item)
                        <tr>
                            <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        {{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->nama_lengkap }}</div>
                                        <div class="small text-muted">{{ $item->masterJabatan->nama_jabatan }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $item->nip_atau_nidn }}</div>
                                <div class="small text-muted">NIP/NIDN</div>
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-envelope me-1"></i> {{ $item->user->email }}</div>
                                <div class="small"><i class="bi bi-telephone me-1"></i> {{ $item->no_telepon ?? '-' }}</div>
                            </td>
                            <td>
                                @if ($item->program_studi_id)
                                    <span class="badge bg-info-subtle text-info rounded-pill badge-status">
                                        {{ $item->programStudi->nama_prodi }}
                                    </span>
                                @elseif ($item->fakultas_id)
                                    <span class="badge bg-primary-subtle text-primary rounded-pill badge-status">
                                        {{ $item->fakultas->nama_fakultas }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill badge-status">Universitas</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->user->is_active)
                                    <span class="text-success small fw-bold">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="text-danger small fw-bold">
                                        <i class="bi bi-x-circle-fill me-1"></i> Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin_akademik.pejabat.edit', $item->id) }}"
                                       class="btn-action btn-edit" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $item->id }}" 
                                            title="Hapus Data">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                                <p class="text-muted">Belum ada data pejabat yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL HAPUS (Dibuat lebih modern) --}}
@foreach ($pejabat as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-octagon-fill" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold">Hapus Data?</h5>
                <p class="text-muted small">Anda akan menghapus data <strong>{{ $item->nama_lengkap }}</strong>. Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin_akademik.pejabat.destroy', $item->id) }}" method="POST">
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
@endsection