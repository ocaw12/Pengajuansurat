@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')
@section('page-title', 'Daftar Mahasiswa')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title"><i class="bi bi-person-vcard me-2"></i> Daftar Mahasiswa</h5>
        <a href="{{ route('admin_akademik.mahasiswa.create') }}" class="btn btn-warning btn-sm">
            <i class="bi bi-person-plus me-1"></i> Tambah Mahasiswa
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col" style="width: 20%;">NIM</th>
                        <th scope="col" style="width: 25%;">Nama Lengkap</th>
                        <th scope="col" style="width: 20%;">Program Studi</th>
                        <th scope="col" style="width: 20%;">Email</th>
                        <th scope="col" style="width: 20%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswas as $mahasiswa)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $mahasiswa->nim }}</td>
                            <td>{{ $mahasiswa->nama_lengkap }}</td>
                            <td>{{ $mahasiswa->programStudi->nama_prodi }}</td>
                            <td>{{ $mahasiswa->user->email }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin_akademik.mahasiswa.show', $mahasiswa->id) }}" class="btn btn-info btn-sm me-2" title="Detail">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('admin_akademik.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-warning btn-sm me-2" title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    {{-- Tombol Delete --}}
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $mahasiswa->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
@foreach($mahasiswas as $mahasiswa)
<div class="modal fade" id="deleteModal{{ $mahasiswa->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data mahasiswa ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin_akademik.mahasiswa.destroy', $mahasiswa->id) }}" method="POST">
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
