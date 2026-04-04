@extends('layouts.app')

@section('title', 'Riwayat Approval')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Riwayat Approval</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Tanggal Approval</th>
                            <th>Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Jenis Surat</th>
                            <th>Status</th>
                            <th width="120">Aksi</th> {{-- 🔥 TAMBAHAN --}}
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($riwayats as $item)
                            <tr>
                                <td>
                                    {{ $item->tanggal_approval?->format('d-m-Y H:i') ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->pengajuanSurat->mahasiswa->nama_lengkap ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->pengajuanSurat->mahasiswa->programStudi->nama_prodi ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->pengajuanSurat->jenisSurat->nama_surat ?? '-' }}
                                </td>

                                <td>
                                    @if($item->status_approval === 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($item->status_approval === 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $item->status_approval }}</span>
                                    @endif
                                </td>

                                {{-- 🔥 BUTTON DETAIL --}}
                                <td>
                                    <a href="{{ route('pejabat.approval.detail', $item->pengajuanSurat->id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada riwayat approval.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
@endsection