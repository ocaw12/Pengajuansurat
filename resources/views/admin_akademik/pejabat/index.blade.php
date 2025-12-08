@extends('layouts.app')

@section('title', 'Daftar Pejabat')

@section('content')
    <h1>Daftar Pejabat</h1>
    <a href="{{ route('admin_akademik.pejabat.create') }}" class="btn btn-primary mb-3">Tambah Pejabat</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIP / NIDN</th> {{-- ganti dari ID --}}
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th>No. Telepon</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pejabat as $item)
                <tr>
                    {{-- tampilkan nip/nidn, bukan id --}}
                    <td>{{ $item->nip_atau_nidn }}</td>

                    <td>{{ $item->nama_lengkap }}</td>
                    <td>{{ $item->masterJabatan->nama_jabatan }}</td>
                    <td>{{ $item->no_telepon ?? '-' }}</td>
                    <td>{{ $item->user->email }}</td>
                    <td>
                        <a href="{{ route('admin_akademik.pejabat.edit', $item->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin_akademik.pejabat.destroy', $item->id) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pejabat ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
