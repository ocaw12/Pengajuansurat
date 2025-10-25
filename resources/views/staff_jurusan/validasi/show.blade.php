@extends('layouts.app')

@section('title', 'Periksa Pengajuan')
@section('page-title', 'Periksa Detail Pengajuan')

@section('content')
<div class="row">
    <!-- Kolom Kiri: Detail Pengajuan -->
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Pengajuan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Nama Mahasiswa</div>
                    <div class="col-sm-8 fw-bold">{{ $pengajuan->mahasiswa->nama_lengkap }} ({{ $pengajuan->mahasiswa->nim }})</div>
                </div>
                 <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Program Studi</div>
                    <div class="col-sm-8">{{ $pengajuan->mahasiswa->programStudi->nama_prodi }}</div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Jenis Surat</div>
                    <div class="col-sm-8 fw-bold">{{ $pengajuan->jenisSurat->nama_surat }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Keperluan</div>
                    <div class="col-sm-8">{{ $pengajuan->keperluan }}</div>
                </div>
                <!-- Tampilkan Data Dinamis (jika ada) -->
                @if($pengajuan->data_pendukung)
                    <hr>
                    <h6 class="text-muted">Data Pendukung:</h6>
                    @foreach($pengajuan->data_pendukung as $key => $value)
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">{{ Str::title(str_replace('_', ' ', $key)) }}</div>
                        <div class="col-sm-8">{{ $value }}</div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Form Aksi -->
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Tindakan Validasi</h5>
            </div>
            <div class="card-body">
                <p>Periksa kelengkapan data. Jika sudah sesuai, teruskan ke pejabat. Jika tidak, tolak atau minta revisi.</p>
                <form action="{{ route('staff.validasi.submit', $pengajuan) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="catatan_revisi" class="form-label">Catatan (Wajib diisi jika menolak)</label>
                        <textarea class="form-control" name="catatan_revisi" id="catatan_revisi" rows="4" placeholder="Jika data tidak lengkap, jelaskan apa yang harus diperbaiki oleh mahasiswa..."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="action" value="validasi" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i> Validasi & Teruskan ke Pejabat
                        </button>
                        <button type="submit" name="action" value="tolak" class="btn btn-outline-danger">
                            <i class="bi bi-x-circle me-2"></i> Tolak / Minta Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
