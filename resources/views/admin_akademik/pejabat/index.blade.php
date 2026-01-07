@extends('layouts.app')

@section('title', 'Daftar Pejabat')
@section('page-title', 'Daftar Pejabat')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title"><i class="bi bi-person-badge me-2"></i> Daftar Pejabat</h5>
        <a href="{{ route('admin_akademik.pejabat.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i> Tambah Pejabat
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col" style="width: 20%;">NIP / NIDN</th>
                        <th scope="col" style="width: 25%;">Nama Lengkap</th>
                        <th scope="col" style="width: 20%;">Jabatan</th>
                        <th scope="col" style="width: 15%;">No. Telepon</th>
                        <th scope="col" style="width: 20%;">Email</th>
                        <th scope="col" style="width: 15%;">Status Akun</th>
                        <th scope="col" style="width: 20%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pejabat as $index => $item)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $item->nip_atau_nidn }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->masterJabatan->nama_jabatan }}</td>
                            <td>{{ $item->no_telepon ?? '-' }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>
                                @if($item->user->is_active)
                                    <span class="badge bg-success">Aktif</span> <!-- Status Aktif -->
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span> <!-- Status Tidak Aktif -->
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin_akademik.pejabat.edit', $item->id) }}" class="btn btn-warning btn-sm me-2" title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    {{-- Tombol Delete --}}
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
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
@foreach($pejabat as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pejabat ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin_akademik.pejabat.destroy', $item->id) }}" method="POST">
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
