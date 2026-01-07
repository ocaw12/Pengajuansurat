@extends('layouts.app')

@section('title', 'Daftar Jabatan')
@section('page-title', 'Daftar Jabatan')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title"><i class="bi bi-briefcase me-2"></i> Daftar Jabatan</h5>
        <a href="{{ route('admin_akademik.master-jabatan.create') }}" class="btn btn-warning btn-sm text-dark">
            <i class="bi bi-plus-circle me-1"></i> Tambah Jabatan
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col" style="width: 75%;">Nama Jabatan</th>
                        <th scope="col" style="width: 20%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jabatans as $jabatan)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $jabatan->nama_jabatan }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin_akademik.master-jabatan.edit', $jabatan->id) }}" class="btn btn-warning btn-sm me-2" title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $jabatan->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-center">
            {{ $jabatans->links() }}
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
@foreach($jabatans as $jabatan)
<div class="modal fade" id="deleteModal{{ $jabatan->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus jabatan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin_akademik.master-jabatan.destroy', $jabatan->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
