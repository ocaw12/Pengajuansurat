@extends('layouts.app')

@section('title', 'Daftar Pejabat')
@section('page-title', 'Daftar Pejabat')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-person-badge me-2"></i> Daftar Pejabat
        </h5>
        <a href="{{ route('admin_akademik.pejabat.create') }}"
           class="btn btn-warning btn-sm text-dark fw-semibold">
            <i class="bi bi-person-plus me-1"></i> Tambah Pejabat
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th style="width:4%">#</th>
                        <th style="width:14%">NIP / NIDN</th>
                        <th style="width:20%">Nama Lengkap</th>
                        <th style="width:18%">Jabatan</th>
                        <th style="width:14%">No. Telepon</th>
                        <th style="width:18%">Email</th>
                        <th style="width:8%">Status</th>
                        <th style="width:10%" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pejabat as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>{{ $item->nip_atau_nidn }}</td>

                            <td class="fw-semibold">
                                {{ $item->nama_lengkap }}
                            </td>

                            {{-- JABATAN + WILAYAH (DIGABUNG BIAR CAKEP) --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $item->masterJabatan->nama_jabatan }}
                                </div>
                                <small class="text-muted">
                                    @if ($item->program_studi_id)
                                        {{ $item->programStudi->nama_prodi }}
                                    @elseif ($item->fakultas_id)
                                        {{ $item->fakultas->nama_fakultas }}
                                    @else
                                        -
                                    @endif
                                </small>
                            </td>

                            <td>{{ $item->no_telepon ?? '-' }}</td>

                            <td>{{ $item->user->email }}</td>

                            <td>
                                @if ($item->user->is_active)
                                    <span class="badge rounded-pill bg-success-subtle text-success">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-danger-subtle text-danger">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI ICON ONLY --}}
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin_akademik.pejabat.edit', $item->id) }}"
                                       class="btn btn-outline-warning btn-sm"
                                       title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $item->id }}"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Data pejabat belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
@foreach ($pejabat as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pejabat
                <strong>{{ $item->nama_lengkap }}</strong>?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Batal
                </button>
                <form action="{{ route('admin_akademik.pejabat.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
