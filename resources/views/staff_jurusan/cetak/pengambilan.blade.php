@extends('layouts.app')

@section('title', 'Antrian Pengambilan')
@section('page-title', 'Antrian Pengambilan Surat')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff_jurusan.dashboard') }}">Staff Jurusan</a></li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Surat Menunggu Diambil ({{ $pengajuans->count() }})</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Daftar surat yang sudah dicetak dan sedang menunggu diambil oleh mahasiswa (status: Siap Diambil).</p>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nomor Surat</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Tgl. Siap Ambil</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                    <tr>
                        <td class="fw-bold">{{ $pengajuan->nomor_surat }}</td>
                        <td>{{ $pengajuan->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $pengajuan->jenisSurat->nama_surat }}</td>
                        <td>{{ $pengajuan->updated_at->format('d M Y H:i') }}</td>
                        <td class="text-center">
                            <form action="{{ route('staff_jurusan.cetak.diambil', $pengajuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi bahwa mahasiswa SUDAH mengambil surat ini?');">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Tandai Sudah Diambil">
                                    <i class="bi bi-check-all"></i> Tandai Sudah Diambil
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada surat yang menunggu pengambilan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection