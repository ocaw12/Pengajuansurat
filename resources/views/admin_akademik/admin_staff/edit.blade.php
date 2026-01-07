@extends('layouts.app')

@section('title', 'Edit Staff')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-black">
        <h5 class="m-0">Edit Staff</h5>
    </div>
    <div class="card-body">
        <!-- Notifikasi sukses -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Edit Staff -->
        <form action="{{ route('admin_akademik.admin-staff.update', $adminStaff->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="nip_staff" class="form-label">NIP Staff</label>
                <input type="text" class="form-control @error('nip_staff') is-invalid @enderror" id="nip_staff" name="nip_staff"
                       value="{{ old('nip_staff', $adminStaff->nip_staff) }}" required>
                @error('nip_staff')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap"
                       value="{{ old('nama_lengkap', $adminStaff->nama_lengkap) }}" required>
                @error('nama_lengkap')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="no_telepon" class="form-label">No. Telepon</label>
                <input type="text"
                       class="form-control @error('no_telepon') is-invalid @enderror"
                       id="no_telepon"
                       name="no_telepon"
                       value="{{ old('no_telepon', $adminStaff->no_telepon) }}"
                       placeholder="Contoh: 081234567890">
                @error('no_telepon')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="program_studi_id" class="form-label">Program Studi</label>
                <select class="form-control @error('program_studi_id') is-invalid @enderror" id="program_studi_id" name="program_studi_id" required>
                    <option value="">Pilih Program Studi</option>
                    @foreach($program_studis as $programStudi)
                        <option value="{{ $programStudi->id }}"
                            {{ old('program_studi_id', $adminStaff->program_studi_id) == $programStudi->id ? 'selected' : '' }}>
                            {{ $programStudi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
                @error('program_studi_id')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                       value="{{ old('email', $adminStaff->user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Akun Aktif --}}
            <div class="form-group form-check mt-2 mb-3">
                <input
                    type="checkbox"
                    class="form-check-input @error('is_active') is-invalid @enderror"
                    id="is_active"
                    name="is_active"
                    value="1"
                    {{ old('is_active', $adminStaff->user->is_active) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="is_active">Akun aktif</label>
                @error('is_active')
                    <div class="text-danger d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-warning mt-3">
                <i class="bi bi-pencil-square"></i> Update Staff
            </button>
        </form>
    </div>
</div>

@endsection
