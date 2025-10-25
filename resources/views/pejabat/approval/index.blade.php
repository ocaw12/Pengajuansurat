@extends('layouts.app')

@section('title', 'Antrian Approval')
@section('page-title', 'Antrian Persetujuan Surat')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Menunggu Persetujuan Anda ({{ $antrians->count() }})</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Mahasiswa</th>
                        <th scope="col">Prodi</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Tgl. Diajukan</th>
                        <th scope="col">Urutan Approval</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antrians as $approval)
                    <tr>
                        <td>{{ $approval->pengajuanSurat->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $approval->pengajuanSurat->mahasiswa->programStudi->nama_prodi }}</td>
                        <td>{{ $approval->pengajuanSurat->jenisSurat->nama_surat }}</td>
                        <td>{{ $approval->pengajuanSurat->tanggal_pengajuan->format('d M Y') }}</td>
                        <td><span class="badge bg-warning">Level {{ $approval->urutan_approval }}</span></td>
                        <td>
                            <a href="{{ route('pejabat.approval.show', $approval) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-search me-1"></i> Tinjau
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada surat yang menunggu persetujuan Anda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
