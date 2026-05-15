@extends('layouts.app')

@section('title', 'Daftar Program Studi')

@push('styles')
<style>
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

    .icon-box {
        width: 38px; height: 38px;
        background-color: #fff9ed;
        color: #f59e0b;
        display: flex; align-items: center; justify-content: center;
        border-radius: 10px; border: 1px solid #fef3c7;
    }

    .btn-action {
        width: 34px; height: 34px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 10px; transition: 0.2s; border: none;
    }
    .btn-edit { background-color: #fff9ed; color: #d97706; }
    .btn-edit:hover { background-color: #f59e0b; color: #fff; }
    .btn-delete { background-color: #fff1f2; color: #e11d48; }
    .btn-delete:hover { background-color: #e11d48; color: #fff; }

    .badge-code { 
        padding: 0.6em 1.2em; 
        font-weight: 700; 
        font-size: 0.7rem; 
        background-color: #f1f5f9; 
        color: #475569;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
    }

    .faculty-label {
        font-size: 0.8rem;
        color: #64748b;
        display: flex;
        align-items: center;
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Daftar Program Studi</h5>
            <p class="text-muted small mb-0">Manajemen program studi dan keterkaitannya dengan fakultas.</p>
        </div>
        <a href="{{ route('admin_akademik.prodi.create') }}"
           class="btn btn-warning px-4 rounded-pill shadow-sm fw-bold text-dark">
            <i class="bi bi-plus-lg me-2"></i> Tambah Prodi
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 text-center" style="width: 70px;">No</th>
                        <th>Program Studi</th>
                        <th class="text-center">Kode Prodi</th>
                        <th>Fakultas</th>
                        <th class="text-center pe-4" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prodis as $index => $prodi)
                        <tr>
                            <td class="ps-4 text-center text-muted fw-medium">{{ $prodis->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $prodi->nama_prodi }}</div>
                                        <div class="small text-muted">Program Pendidikan Tinggi</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-code text-uppercase">
                                    {{ $prodi->kode_prodi }}
                                </span>
                            </td>
                            <td>
                                <div class="faculty-label">
                                    <i class="bi bi-bank2 me-2 text-warning"></i>
                                    {{ $prodi->fakultas->nama_fakultas }}
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin_akademik.prodi.edit', $prodi->id) }}"
                                       class="btn-action btn-edit" data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $prodi->id }}" 
                                            title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-folder2-open display-4 text-muted opacity-25"></i>
                                    <p class="text-muted mt-3">Belum ada data program studi yang ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $prodis->links() }}
</div>

{{-- MODAL HAPUS --}}
@foreach ($prodis as $prodi)
<div class="modal fade" id="deleteModal{{ $prodi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-octagon-fill" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold">Hapus Program Studi?</h5>
                <p class="text-muted small">Prodi <strong>{{ $prodi->nama_prodi }}</strong> akan dihapus permanen dari sistem.</p>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin_akademik.prodi.destroy', $prodi->id) }}" method="POST">
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