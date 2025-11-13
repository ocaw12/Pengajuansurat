@extends('layouts.app')

@section('title', 'Antrian Validasi')
@section('page-title', 'Antrian Validasi Surat')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Menunggu Validasi ({{ $pengajuans->count() }})</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">NIM</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Tgl. Diajukan</th>
                        <th scope="col">Metode</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                    <tr>
                        <td>{{ $pengajuan->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $pengajuan->mahasiswa->nim }}</td>
                        <td>{{ $pengajuan->jenisSurat->nama_surat }}</td>
                        <td>{{ $pengajuan->tanggal_pengajuan->format('d M Y H:i') }}</td>
                        <td>
                            <span class="badge {{ $pengajuan->metode_pengambilan == 'digital' ? 'bg-info' : 'bg-secondary' }}">
                                {{ $pengajuan->metode_pengambilan }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('staff_jurusan.validasi.show', $pengajuan) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-search me-1"></i> Periksa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada pengajuan yang menunggu validasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
