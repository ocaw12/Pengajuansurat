@extends('layouts.app')

@section('title', 'Riwayat Surat')
@section('page-title', 'Riwayat Surat')

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between">
        <h5 class="mb-0">Riwayat Surat</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($riwayat as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                {{ $item->mahasiswa->nama_lengkap ?? '-' }} <br>
                                <small class="text-muted">
                                    {{ $item->mahasiswa->nim ?? '' }}
                                </small>
                            </td>

                            <td>{{ $item->jenisSurat->nama_surat }}</td>

                            <td>
                                {{ $item->tanggal_pengajuan->format('d M Y') }} <br>
                                <small class="text-muted">
                                    {{ $item->tanggal_pengajuan->format('H:i') }}
                                </small>
                            </td>

                            <td>
                                @include('partials.status_badge', [
                                    'status' => $item->status_pengajuan
                                ])
                            </td>

                            <td>
                                <a href="{{ route('staff_jurusan.validasi.detailRiwayat', $item->id) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada riwayat surat
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

@endsection