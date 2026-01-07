@extends('layouts.app')

@section('title', 'Daftar Staff')
@section('page-title', 'Daftar Staff')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title"><i class="bi bi-person-workspace me-2"></i> Daftar Staff</h5>
        <a href="{{ route('admin_akademik.admin-staff.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i> Tambah Staff
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col" style="width: 15%;">NIP Staff</th>
                        <th scope="col" style="width: 20%;">Nama Lengkap</th>
                        <th scope="col" style="width: 20%;">Program Studi</th>
                        <th scope="col" style="width: 15%;">No. Telepon</th>
                        <th scope="col" style="width: 20%;">Email</th>
                        <th scope="col" style="width: 10%;">Status Akun</th>
                        <th scope="col" style="width: 20%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($adminStaffs as $index => $staff)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $staff->nip_staff }}</td>
                            <td>{{ $staff->nama_lengkap }}</td>
                            <td>{{ $staff->programStudi->nama_prodi }}</td>
                            <td>{{ $staff->no_telepon ?? '-' }}</td>
                            <td>{{ $staff->user->email }}</td>
                            <td>
                                @if($staff->user->is_active)
                                    <span class="badge bg-success">Aktif</span> <!-- Status Aktif -->
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span> <!-- Status Tidak Aktif -->
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin_akademik.admin-staff.edit', $staff->id) }}" class="btn btn-warning btn-sm me-2" title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $staff->id }}" title="Hapus">
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
@foreach($adminStaffs as $staff)
<div class="modal fade" id="deleteModal{{ $staff->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data staff ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin_akademik.admin-staff.destroy', $staff->id) }}" method="POST">
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
