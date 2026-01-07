@extends('layouts.app')

@section('title', 'Edit Pejabat')
@section('page-title', 'Edit Pejabat')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-black">
        <h5 class="m-0">Edit Pejabat</h5>
    </div>
    <div class="card-body">
        <!-- Notifikasi sukses -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Edit Pejabat -->
        <form action="{{ route('admin_akademik.pejabat.update', $pejabat->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email', $pejabat->user->email) }}"
                    required
                >
                @error('email')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="nip_atau_nidn" class="form-label fw-semibold">NIP/NIDN</label>
                <input
                    type="text"
                    class="form-control @error('nip_atau_nidn') is-invalid @enderror"
                    name="nip_atau_nidn"
                    value="{{ old('nip_atau_nidn', $pejabat->nip_atau_nidn) }}"
                    required
                >
                @error('nip_atau_nidn')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap</label>
                <input
                    type="text"
                    class="form-control @error('nama_lengkap') is-invalid @enderror"
                    name="nama_lengkap"
                    value="{{ old('nama_lengkap', $pejabat->nama_lengkap) }}"
                    required
                >
                @error('nama_lengkap')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="no_telepon" class="form-label fw-semibold">No. Telepon</label>
                <input
                    type="text"
                    class="form-control @error('no_telepon') is-invalid @enderror"
                    name="no_telepon"
                    value="{{ old('no_telepon', $pejabat->no_telepon) }}"
                    placeholder="Contoh: 081234567890"
                >
                @error('no_telepon')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="jabatan" class="form-label fw-semibold">Jabatan</label>
                <select name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" required>
                    @foreach ($masterJabatan as $jabatan)
                        <option
                            value="{{ $jabatan->id }}"
                            {{ old('jabatan', $pejabat->master_jabatan_id) == $jabatan->id ? 'selected' : '' }}
                        >
                            {{ $jabatan->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
                @error('jabatan')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3" id="fakultas" style="display: none;">
                <label for="fakultas_id" class="form-label fw-semibold">Fakultas</label>
                <select name="fakultas_id" class="form-control @error('fakultas_id') is-invalid @enderror">
                    <option value="">Pilih Fakultas</option>
                    @foreach ($fakultas as $f)
                        <option
                            value="{{ $f->id }}"
                            {{ old('fakultas_id', $pejabat->fakultas_id) == $f->id ? 'selected' : '' }}
                        >
                            {{ $f->nama_fakultas }}
                        </option>
                    @endforeach
                </select>
                @error('fakultas_id')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3" id="program_studi" style="display: none;">
                <label for="program_studi_id" class="form-label fw-semibold">Program Studi</label>
                <select name="program_studi_id" class="form-control @error('program_studi_id') is-invalid @enderror">
                    <option value="">Pilih Program Studi</option>
                    @foreach ($programStudi as $program)
                        <option
                            value="{{ $program->id }}"
                            {{ old('program_studi_id', $pejabat->program_studi_id) == $program->id ? 'selected' : '' }}
                        >
                            {{ $program->nama_prodi }}
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
                <label for="password" class="form-label fw-semibold">Password Baru (Opsional)</label>
                <input type="password" class="form-control" name="password">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
            </div>

            <div class="form-group form-check mt-2 mb-3">
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="is_active"
                    name="is_active"
                    value="1"
                    {{ old('is_active', $pejabat->user->is_active) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="is_active">Akun aktif</label>
                @error('is_active')
                    <div class="text-danger d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-warning mt-3">Update</button>
        </form>
    </div>
</div>

<script>
    document.querySelector('#jabatan').addEventListener('change', function () {
        var jabatan = this.value;
        if (jabatan == 2) { // Dekan
            document.getElementById('fakultas').style.display = 'block';
            document.getElementById('program_studi').style.display = 'none';
        } else if (jabatan == 1) { // Kaprodi
            document.getElementById('fakultas').style.display = 'none';
            document.getElementById('program_studi').style.display = 'block';
        } else {
            document.getElementById('fakultas').style.display = 'none';
            document.getElementById('program_studi').style.display = 'none';
        }
    });

    // Set initial state based on current jabatan
    document.querySelector('#jabatan').dispatchEvent(new Event('change'));
</script>

@endsection
