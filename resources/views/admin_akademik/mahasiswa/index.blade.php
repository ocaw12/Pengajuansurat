@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')
@section('page-title', 'Daftar Mahasiswa')

@push('styles')
<style>
    /* Hilangkan padding default body agar rapat ke atas sesuai request sebelumnya */
    .container-fluid { max-width: 1400px; padding-top: 0.5rem !important; }

    /* Styling Card & Table - Konsisten dengan Staff & Pejabat */
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

    /* Avatar & Info Styling - Warna Hijau/Tosca untuk Mahasiswa */
    .avatar-circle {
        width: 40px; height: 40px;
        background-color: #d1fae5; 
        color: #059669;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-weight: bold; font-size: 0.9rem;
    }

    /* Action Buttons */
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

    /* Custom Badge */
    .badge-prodi { padding: 0.5em 1em; font-weight: 600; font-size: 0.75rem; background-color: #f1f5f9; color: #475569; border-radius: 50px; }
    
    /* Import Input Styling */
    .import-container { display: flex; align-items: center; gap: 8px; border-right: 1px solid #e2e8f0; padding-right: 15px; margin-right: 10px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
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

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Program Studi</th>
                            <th>Kontak</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswas as $index => $mhs)
                            <tr>
                                <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            {{ strtoupper(substr($mhs->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $mhs->nama_lengkap }}</div>
                                            <div class="small text-muted">{{ $mhs->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ $mhs->nim }}</div>
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
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                    Belum ada data mahasiswa yang terdaftar.
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
                {{ $mahasiswas->links() }}
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
@endsection