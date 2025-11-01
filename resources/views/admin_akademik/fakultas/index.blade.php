@extends('layouts.app')

@section('title', 'Daftar Fakultas')
@section('page-title', 'Daftar Fakultas')

@section('content')

<div class="mb-3 text-end">
    <a href="{{ route('admin_akademik.fakultas.create') }}" class="btn btn-primary">
        + Tambah Fakultas
    </a>
</div>

@if ($fakultas->isEmpty())
    <div class="alert alert-info text-center">
        Tidak ada data fakultas.
    </div>
@else
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-striped table-bordered mb-0">
            <thead class="table-warning">
                <tr>
                    <th>ID</th>
                    <th>Nama Fakultas</th>
                    <th>Kode Fakultas</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fakultas as $fakultasItem)
                <tr>
                    <td>{{ $fakultasItem->id }}</td>
                    <td>{{ $fakultasItem->nama_fakultas }}</td>
                    <td>{{ $fakultasItem->kode_fakultas }}</td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('admin_akademik.fakultas.edit', $fakultasItem->id) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>
                        <form action="{{ route('admin_akademik.fakultas.destroy', $fakultasItem->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus fakultas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ✅ Tambah pagination di sini --}}
<div class="mt-3 d-flex justify-content-center">
    {{ $fakultas->links() }}
</div>
@endif

@endsection
