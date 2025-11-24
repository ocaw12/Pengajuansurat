@extends('layouts.app')

@section('title', 'Edit Pejabat')

@section('content')
    <h1>Edit Pejabat</h1>

    <form action="{{ route('admin_akademik.pejabat.update', $pejabat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                class="form-control"
                name="email"
                value="{{ old('email', $pejabat->user->email) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="nip_atau_nidn">NIP/NIDN</label>
            <input
                type="text"
                class="form-control"
                name="nip_atau_nidn"
                value="{{ old('nip_atau_nidn', $pejabat->nip_atau_nidn) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input
                type="text"
                class="form-control"
                name="nama_lengkap"
                value="{{ old('nama_lengkap', $pejabat->nama_lengkap) }}"
                required
            >
        </div>

        {{-- No Telepon --}}
        <div class="form-group">
            <label for="no_telepon">No. Telepon</label>
            <input
                type="text"
                class="form-control"
                name="no_telepon"
                value="{{ old('no_telepon', $pejabat->no_telepon) }}"
                placeholder="Contoh: 081234567890"
            >
        </div>

        <div class="form-group">
            <label for="jabatan">Jabatan</label>
            <select name="jabatan" class="form-control" id="jabatan" required>
                @foreach ($masterJabatan as $jabatan)
                    <option
                        value="{{ $jabatan->id }}"
                        {{ old('jabatan', $pejabat->master_jabatan_id) == $jabatan->id ? 'selected' : '' }}
                    >
                        {{ $jabatan->nama_jabatan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" id="fakultas" style="display: none;">
            <label for="fakultas_id">Fakultas</label>
            <select name="fakultas_id" class="form-control">
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
        </div>

        <div class="form-group" id="program_studi" style="display: none;">
            <label for="program_studi_id">Program Studi</label>
            <select name="program_studi_id" class="form-control">
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
        </div>

        <div class="form-group">
            <label for="password">Password Baru (Opsional)</label>
            <input type="password" class="form-control" name="password">
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Update</button>
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

        // Set initial state based on current jabatan
        document.querySelector('#jabatan').dispatchEvent(new Event('change'));
    </script>
@endsection
