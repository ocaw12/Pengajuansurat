@extends('layouts.app')

@section('title', 'Tambah Pejabat')

@section('content')
    <h1>Tambah Pejabat</h1>

    <form action="{{ route('admin_akademik.pejabat.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                name="email"
                value="{{ old('email') }}"
                required
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="nip_atau_nidn">NIP/NIDN</label>
            <input
                type="text"
                class="form-control @error('nip_atau_nidn') is-invalid @enderror"
                name="nip_atau_nidn"
                value="{{ old('nip_atau_nidn') }}"
                required
            >
            @error('nip_atau_nidn')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input
                type="text"
                class="form-control @error('nama_lengkap') is-invalid @enderror"
                name="nama_lengkap"
                value="{{ old('nama_lengkap') }}"
                required
            >
            @error('nama_lengkap')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- No Telepon --}}
        <div class="form-group">
            <label for="no_telepon">No. Telepon</label>
            <input
                type="text"
                class="form-control @error('no_telepon') is-invalid @enderror"
                name="no_telepon"
                value="{{ old('no_telepon') }}"
                placeholder="Contoh: 081234567890"
            >
            @error('no_telepon')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="jabatan">Jabatan</label>
            <select name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" required>
                <option value="">Pilih Jabatan</option>
                @foreach ($masterJabatan as $jabatan)
                    <option
                        value="{{ $jabatan->id }}"
                        {{ old('jabatan') == $jabatan->id ? 'selected' : '' }}
                    >
                        {{ $jabatan->nama_jabatan }}
                    </option>
                @endforeach
            </select>
            @error('jabatan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group" id="fakultas" style="display: none;">
            <label for="fakultas_id">Fakultas</label>
            <select name="fakultas_id" class="form-control @error('fakultas_id') is-invalid @enderror">
                <option value="">Pilih Fakultas</option>
                @foreach ($fakultas as $fak)
                    <option
                        value="{{ $fak->id }}"
                        {{ old('fakultas_id') == $fak->id ? 'selected' : '' }}
                    >
                        {{ $fak->nama_fakultas }}
                    </option>
                @endforeach
            </select>
            @error('fakultas_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group" id="program_studi" style="display: none;">
            <label for="program_studi_id">Program Studi</label>
            <select name="program_studi_id" class="form-control @error('program_studi_id') is-invalid @enderror">
                <option value="">Pilih Program Studi</option>
                @foreach ($programStudi as $program)
                    <option
                        value="{{ $program->id }}"
                        {{ old('program_studi_id') == $program->id ? 'selected' : '' }}
                    >
                        {{ $program->nama_prodi }}
                    </option>
                @endforeach
            </select>
            @error('program_studi_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Akun Aktif --}}
        <div class="form-group form-check mt-2">
            <input
                type="checkbox"
                class="form-check-input"
                id="is_active"
                name="is_active"
                value="1"
                {{ old('is_active', true) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="is_active">Akun aktif</label>
            @error('is_active')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>

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
    </script>
@endsection
