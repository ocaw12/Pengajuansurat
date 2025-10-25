@extends('layouts.app')

@section('title', 'Riwayat Pengajuan')
@section('page-title', 'Riwayat Pengajuan Saya')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Surat</h5>
        <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Buat Pengajuan Baru
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Keperluan</th>
                        <th scope="col">Tgl. Dibuat</th>
                        <th scope="col">Status</th>
                        <th scope="col">Metode</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan_surats as $index => $pengajuan)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $pengajuan->jenisSurat->nama_surat }}</td>
                        <td>{{ Str::limit($pengajuan->keperluan, 30) }}</td>
                        <td>{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</td>
                        <td>
                            @include('partials.status_badge', ['status' => $pengajuan->status_pengajuan])
                        </td>
                        <td>
                            <span class="badge {{ $pengajuan->metode_pengambilan == 'digital' ? 'bg-info' : 'bg-secondary' }}">
                                {{ $pengajuan->metode_pengambilan }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada riwayat pengajuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

