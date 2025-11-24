@extends('layouts.app')

@section('title', 'Edit Staff')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Staff</h1>

    <form action="{{ route('admin_akademik.admin-staff.update', $adminStaff->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nip_staff" class="form-label">NIP Staff</label>
            <input type="text" class="form-control" id="nip_staff" name="nip_staff"
                   value="{{ old('nip_staff', $adminStaff->nip_staff) }}" required>
            @error('nip_staff')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                   value="{{ old('nama_lengkap', $adminStaff->nama_lengkap) }}" required>
            @error('nama_lengkap')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- No Telepon -->
        <div class="mb-3">
            <label for="no_telepon" class="form-label">No. Telepon</label>
            <input type="text"
                   class="form-control"
                   id="no_telepon"
                   name="no_telepon"
                   value="{{ old('no_telepon', $adminStaff->no_telepon) }}"
                   placeholder="Contoh: 081234567890">
            @error('no_telepon')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="program_studi_id" class="form-label">Program Studi</label>
            <select class="form-control" id="program_studi_id" name="program_studi_id" required>
                <option value="">Pilih Program Studi</option>
                @foreach($program_studis as $programStudi)
                    <option value="{{ $programStudi->id }}"
                        {{ old('program_studi_id', $adminStaff->program_studi_id) == $programStudi->id ? 'selected' : '' }}>
                        {{ $programStudi->nama_prodi }}
                    </option>
                @endforeach
            </select>
            @error('program_studi_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="{{ old('email', $adminStaff->user->email) }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-warning">
            <i class="bi bi-pencil-square"></i> Update Staff
        </button>

    </form>

</div>
@endsection
