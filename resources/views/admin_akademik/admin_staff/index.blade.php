@extends('layouts.app')

@section('title', 'Daftar Staff')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Daftar Staff</h1>

    <div class="mb-3">
        <a href="{{ route('admin_akademik.admin-staff.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Tambah Staff
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP Staff</th>
                        <th>Nama Lengkap</th>
                        <th>Program Studi</th>
                        <th>No. Telepon</th>   <!-- ⬅️ DITAMBAHKAN -->
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($adminStaffs as $index => $staff)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $staff->nip_staff }}</td>
                            <td>{{ $staff->nama_lengkap }}</td>
                            <td>{{ $staff->programStudi->nama_prodi }}</td>
                            <td>{{ $staff->no_telepon ?? '-' }}</td>   <!-- ⬅️ DITAMBAHKAN -->
                            <td>{{ $staff->user->email }}</td>
                            <td>
                                <a href="{{ route('admin_akademik.admin-staff.edit', $staff->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin_akademik.admin-staff.destroy', $staff->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
@endsection
