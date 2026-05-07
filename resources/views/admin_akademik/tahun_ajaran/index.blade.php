@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Kelola Tahun Ajaran')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Daftar Tahun Ajaran</h5>
                <small class="text-muted">Kelola data tahun ajaran akademik.</small>
            </div>

            <a href="{{ route('admin_akademik.tahun-ajaran.create') }}"
               class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Tahun Ajaran
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tahunAjarans as $item)
                            <tr>
                                <td class="ps-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $item->tahun }}
                                    </div>
                                </td>

                                <td>
                                    @if($item->is_aktif)
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">

                                        @if(!$item->is_aktif)
                                        <form action="{{ route('admin_akademik.tahun-ajaran.aktifkan', $item->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-success btn-sm rounded-pill">
                                                Aktifkan
                                            </button>
                                        </form>
                                        @endif

                                        <a href="{{ route('admin_akademik.tahun-ajaran.edit', $item->id) }}"
                                           class="btn btn-warning btn-sm rounded-pill">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin_akademik.tahun-ajaran.destroy', $item->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus tahun ajaran ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm rounded-pill">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    Belum ada data tahun ajaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($tahunAjarans->hasPages())
        <div class="card-footer bg-white">
            {{ $tahunAjarans->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
