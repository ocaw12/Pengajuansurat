@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Daftar Mahasiswa</h1>

    

    <!-- Tombol Tambah Mahasiswa -->
    <a href="{{ route('admin_akademik.mahasiswa.create') }}" class="btn btn-warning mb-3">
        <i class="bi bi-person-plus"></i> Tambah Mahasiswa
    </a>

    <!-- Tabel Daftar Mahasiswa -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Program Studi</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswas as $mahasiswa)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mahasiswa->nim }}</td>
                            <td>{{ $mahasiswa->nama_lengkap }}</td>
                            <td>{{ $mahasiswa->programStudi->nama_prodi }}</td>
                            <td>{{ $mahasiswa->user->email }}</td>
                            <td>
    <a href="{{ route('admin_akademik.mahasiswa.show', $mahasiswa->id) }}" class="btn btn-info btn-sm">
        <i class="bi bi-eye"></i> Detail
    </a>
    <a href="{{ route('admin_akademik.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-warning btn-sm">
        <i class="bi bi-pencil-square"></i> Edit
    </a>
    <form action="{{ route('admin_akademik.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="d-inline"
          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
        @csrf @method('DELETE')
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
@endsection
