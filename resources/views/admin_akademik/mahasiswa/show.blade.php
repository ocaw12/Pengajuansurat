@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Mahasiswa</h1>

    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">NIM</dt>
                <dd class="col-sm-9">{{ $mahasiswa->nim }}</dd>

                <dt class="col-sm-3">Nama Lengkap</dt>
                <dd class="col-sm-9">{{ $mahasiswa->nama_lengkap }}</dd>

                <dt class="col-sm-3">Tempat, Tanggal Lahir</dt>
                <dd class="col-sm-9">
                    {{ $mahasiswa->tempat_lahir }},
                    {{ \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->translatedFormat('d F Y') }}
                </dd>

                <dt class="col-sm-3">Alamat</dt>
                <dd class="col-sm-9">{{ $mahasiswa->alamat }}</dd>

                <dt class="col-sm-3">Jenis Kelamin</dt>
                <dd class="col-sm-9">{{ str_replace('_', '-', $mahasiswa->jenis_kelamin) }}</dd>

                <dt class="col-sm-3">Angkatan</dt>
                <dd class="col-sm-9">{{ $mahasiswa->angkatan }}</dd>

                <dt class="col-sm-3">Program Studi</dt>
                <dd class="col-sm-9">{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $mahasiswa->user->email ?? '-' }}</dd>

                <dt class="col-sm-3">Dibuat / Diperbarui</dt>
                <dd class="col-sm-9">
                    {{ $mahasiswa->created_at?->format('d-m-Y H:i') }} /
                    {{ $mahasiswa->updated_at?->format('d-m-Y H:i') }}
                </dd>
            </dl>
        </div>
        <div class="card-footer d-flex gap-2">
            <a href="{{ route('admin_akademik.mahasiswa.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin_akademik.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <form action="{{ route('admin_akademik.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Hapus data ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
