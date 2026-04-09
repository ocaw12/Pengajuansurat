@extends('layouts.app')

@section('title', 'Daftar Jabatan')

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

    /* Icon Box untuk Jabatan */
    .icon-box {
        width: 38px; height: 38px;
        background-color: #fff9ed;
        color: #f59e0b;
        display: flex; align-items: center; justify-content: center;
        border-radius: 10px; border: 1px solid #fef3c7;
    }

    /* Action Buttons */
    .btn-action {
        width: 34px; height: 34px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 10px; transition: 0.2s; border: none;
    }
    .btn-edit { background-color: #fff9ed; color: #d97706; }
    .btn-edit:hover { background-color: #f59e0b; color: #fff; }
    .btn-delete { background-color: #fff1f2; color: #e11d48; }
    .btn-delete:hover { background-color: #e11d48; color: #fff; }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Daftar Jabatan</h5>
            <p class="text-muted small mb-0">Master data jabatan untuk struktur organisasi akademik.</p>
        </div>
        <a href="{{ route('admin_akademik.master-jabatan.create') }}"
           class="btn btn-warning px-4 rounded-pill shadow-sm fw-bold text-dark">
            <i class="bi bi-plus-lg me-2"></i> Tambah Jabatan
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 text-center" style="width: 70px;">No</th>
                        <th>Nama Jabatan</th>
                        <th class="text-center">Status Penggunaan</th>
                        <th class="text-center pe-4" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jabatans as $index => $jabatan)
                        <tr>
                            <td class="ps-4 text-center text-muted fw-medium">
                                {{ ($jabatans->currentPage() - 1) * $jabatans->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-briefcase-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $jabatan->nama_jabatan }}</div>
                                        <div class="small text-muted">Struktur Organisasi</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-light text-dark border px-3 fw-normal">
                                    Aktif
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin_akademik.master-jabatan.edit', $jabatan->id) }}"
                                       class="btn-action btn-edit" data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $jabatan->id }}" 
                                            title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-briefcase text-muted opacity-25" style="font-size: 4rem;"></i>
                                    <p class="text-muted mt-3">Belum ada data jabatan yang ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $jabatans->links() }}
</div>

{{-- MODAL HAPUS --}}
@foreach ($jabatans as $jabatan)
<div class="modal fade" id="deleteModal{{ $jabatan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-trash3 text-danger" style="font-size: 3.5rem; opacity: 0.2; position: absolute; left: 50%; transform: translateX(-50%) translateY(-10px);"></i>
                    <i class="bi bi-exclamation-circle-fill" style="font-size: 3rem; position: relative;"></i>
                </div>
                <h5 class="fw-bold mt-2">Hapus Jabatan?</h5>
                <p class="text-muted small">Jabatan <strong>{{ $jabatan->nama_jabatan }}</strong> akan dihapus dari sistem.</p>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin_akademik.master-jabatan.destroy', $jabatan->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection