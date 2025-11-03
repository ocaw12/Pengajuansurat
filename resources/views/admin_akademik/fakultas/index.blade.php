@extends('layouts.app')

@section('title', 'Daftar Fakultas')
@section('page-title', 'Daftar Fakultas')

@section('content')
<div class="container-fluid px-4">
    <!-- Grid Layout -->
    <div class="row mb-4">
        <!-- Column untuk Title -->
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="m-0">Daftar Fakultas</h4>
                <a href="{{ route('admin_akademik.fakultas.create') }}" class="btn btn-warning text-white">
                    + Tambah Fakultas
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Tabel Daftar Fakultas -->
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Fakultas</th>
                                <th>Kode Fakultas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fakultas as $fakultasItem)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $fakultasItem->nama_fakultas }}</td>
                                <td>{{ $fakultasItem->kode_fakultas }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin_akademik.fakultas.edit', $fakultasItem->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <!-- Tombol Delete -->
                                    <form action="{{ route('admin_akademik.fakultas.destroy', $fakultasItem->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fakultas ini?');">
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

    {{-- ✅ Tambah pagination di sini --}}
    <div class="mt-3 d-flex justify-content-center">
        {{ $fakultas->links() }}
    </div>
</div>
@endsection
