@extends('layouts.app')

@section('title', 'Daftar Program Studi')
@section('page-title', 'Daftar Program Studi')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title"><i class="bi bi-diagram-3 me-2"></i> Daftar Program Studi</h5>
        <a href="{{ route('admin_akademik.prodi.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Program Studi
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col" style="width: 30%;">Nama Program Studi</th>
                        <th scope="col" style="width: 20%;">Kode Prodi</th>
                        <th scope="col" style="width: 30%;">Fakultas</th>
                        <th scope="col" style="width: 15%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prodis as $prodi)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $prodi->nama_prodi }}</td>
                            <td>{{ $prodi->kode_prodi }}</td>
                            <td>{{ $prodi->fakultas->nama_fakultas }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin_akademik.prodi.edit', $prodi->id) }}" class="btn btn-warning btn-sm me-2" title="Edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <!-- Tombol Delete -->
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $prodi->id }}" title="Hapus">
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
            {{ $prodis->links() }}
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
@foreach($prodis as $prodi)
<div class="modal fade" id="deleteModal{{ $prodi->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus program studi ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin_akademik.prodi.destroy', $prodi->id) }}" method="POST">
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
