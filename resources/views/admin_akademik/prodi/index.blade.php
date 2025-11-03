@extends('layouts.app')

@section('title', 'Daftar Program Studi')
@section('page-title', 'Daftar Program Studi')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin_akademik.prodi.create') }}" class="btn btn-primary mb-3">Tambah Program Studi</a>
            <div class="card">
                <div class="card-header">
                    <h4 class="m-0">Daftar Program Studi</h4>
                </div>
                <div class="card-body">
                    <!-- Tabel Daftar Program Studi -->
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Program Studi</th>
                                <th>Kode Prodi</th>
                                <th>Fakultas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prodis as $prodi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $prodi->nama_prodi }}</td>
                                <td>{{ $prodi->kode_prodi }}</td>
                                <td>{{ $prodi->fakultas->nama_fakultas }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin_akademik.prodi.edit', $prodi->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <!-- Tombol Delete -->
                                    <form action="{{ route('admin_akademik.prodi.destroy', $prodi->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
