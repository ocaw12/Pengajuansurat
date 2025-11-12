@extends('layouts.app')

@section('title', 'Tambah Pejabat')

@section('content')
    <h1>Tambah Pejabat</h1>

    <form action="{{ route('admin_akademik.pejabat.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="nip_atau_nidn">NIP/NIDN</label>
            <input type="text" class="form-control" name="nip_atau_nidn" value="{{ old('nip_atau_nidn') }}" required>
        </div>

        <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
        </div>

        <div class="form-group">
            <label for="jabatan">Jabatan</label>
            <select name="jabatan" class="form-control" id="jabatan" required>
                <option value="">Pilih Jabatan</option> <!-- Opsi kosong pertama kali -->
                @foreach ($masterJabatan as $jabatan)
                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" id="fakultas" style="display: none;">
            <label for="fakultas_id">Fakultas</label>
            <select name="fakultas_id" class="form-control">
                @foreach ($fakultas as $fakultas)
                    <option value="{{ $fakultas->id }}">{{ $fakultas->nama_fakultas }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" id="program_studi" style="display: none;">
            <label for="program_studi_id">Program Studi</label>
            <select name="program_studi_id" class="form-control">
                @foreach ($programStudi as $program)
                    <option value="{{ $program->id }}">{{ $program->nama_prodi }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
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
