@extends('layouts.app')

@section('title', 'Riwayat Approval')

@section('content')
    <h1 class="mb-3">Riwayat Approval</h1>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tanggal Approval</th>
                <th>Mahasiswa</th>
                <th>Program Studi</th>
                <th>Jenis Surat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayats as $item)
                <tr>
                    <td>{{ $item->tanggal_approval?->format('d-m-Y H:i') ?? '-' }}</td>
                    <td>{{ $item->pengajuanSurat->mahasiswa->nama_lengkap ?? '-' }}</td>
                    <td>{{ $item->pengajuanSurat->mahasiswa->programStudi->nama_prodi ?? '-' }}</td>
                    <td>{{ $item->pengajuanSurat->jenisSurat->nama_surat ?? '-' }}</td>
                    <td class="text-capitalize">
                        @if($item->status_approval === 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($item->status_approval === 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            {{ $item->status_approval }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada riwayat approval.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
