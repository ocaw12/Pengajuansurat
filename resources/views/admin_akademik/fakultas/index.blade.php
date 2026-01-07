@extends('layouts.app')

@section('title', 'Daftar Fakultas')
@section('page-title', 'Daftar Fakultas')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title"><i class="bi bi-building me-2"></i> Daftar Fakultas</h5>
        <a href="{{ route('admin_akademik.fakultas.create') }}" class="btn btn-warning btn-sm text-dark">
            <i class="bi bi-plus-circle me-1"></i> Tambah Fakultas
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col" style="width: 35%;">Nama Fakultas</th>
                        <th scope="col" style="width: 35%;">Kode Fakultas</th>
                        <th scope="col" style="width: 25%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fakultas as $fakultasItem)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $fakultasItem->nama_fakultas }}</td>
                            <td>{{ $fakultasItem->kode_fakultas }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin_akademik.fakultas.edit', $fakultasItem->id) }}" class="btn btn-warning btn-sm me-2" title="Edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <!-- Tombol Delete -->
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $fakultasItem->id }}" title="Hapus">
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
            {{ $fakultas->links() }}
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
@foreach ($fakultas as $fakultasItem)
<div class="modal fade" id="deleteModal{{ $fakultasItem->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus fakultas ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin_akademik.fakultas.destroy', $fakultasItem->id) }}" method="POST">
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
