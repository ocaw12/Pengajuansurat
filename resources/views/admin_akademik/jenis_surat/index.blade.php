@extends('layouts.app')

@section('title', 'Manajemen Jenis Surat')
@section('page-title', 'Manajemen Jenis Surat')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title"><i class="bi bi-files me-2"></i>Daftar Jenis Surat</h5>
        <a href="{{ route('admin_akademik.jenis-surat.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Jenis Surat Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col" style="width: 35%;">Nama Surat</th>
                        <th scope="col" style="width: 15%;">Kode Surat</th>
                        <th scope="col" style="width: 15%;">Kategori</th>
                        <th scope="col" style="width: 15%;" class="text-center">Total Approval</th>
                        <th scope="col" style="width: 15%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisSurats as $index => $jenis)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $jenis->nama_surat }}</td>
                        <td><span class="badge bg-secondary">{{ $jenis->kode_surat }}</span></td>
                        <td>{{ $jenis->kategori }}</td>
                        <td class="text-center">
                            {{-- Hitung jumlah alur approval untuk surat ini --}}
                            {{ $jenis->alurApprovals()->count() }} Level
                        </td>
                        <td class="text-center">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin_akademik.jenis-surat.edit', $jenis->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            {{-- Tombol Delete --}}
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $jenis->id }}" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>

                            {{-- Tombol Detail --}}
                            <a href="{{ route('admin_akademik.jenis-surat.show', $jenis->id) }}" class="btn btn-info btn-sm" title="Lihat Detail & Alur Approval">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada jenis surat yang ditambahkan.
                            <a href="{{ route('admin_akademik.jenis-surat.create') }}">Tambah baru</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
@foreach($jenisSurats as $jenis)
<div class="modal fade" id="deleteModal{{ $jenis->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus jenis surat ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin_akademik.jenis-surat.destroy', $jenis->id) }}" method="POST">
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
