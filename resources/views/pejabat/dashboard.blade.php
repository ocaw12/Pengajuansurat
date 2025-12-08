@extends('layouts.app')

@section('title', 'Dashboard Pejabat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Dashboard Pejabat</h4>
            <p class="mb-0 text-muted">
                Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan status approval surat yang menjadi tanggung jawab Anda.
            </p>
        </div>
    </div>

    {{-- Ringkasan Kartu Statistik --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Menunggu Approval</h6>
                    <h2 class="fw-bold mb-0">{{ $totalMenunggu }}</h2>
                    <small class="text-muted">Surat yang masih menunggu tindakan Anda</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Disetujui</h6>
                    <h2 class="fw-bold mb-0 text-success">{{ $totalDisetujui }}</h2>
                    <small class="text-muted">Surat yang sudah Anda setujui</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Ditolak</h6>
                    <h2 class="fw-bold mb-0 text-danger">{{ $totalDitolak }}</h2>
                    <small class="text-muted">Surat yang pernah Anda tolak</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Antrian Menunggu --}}
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Antrian Menunggu Approval</h6>
                    <a href="{{ route('pejabat.approval.antrian') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($antrianTerbaru->isEmpty())
                        <p class="p-3 mb-0 text-muted">Tidak ada antrian menunggu saat ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Jenis Surat</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($antrianTerbaru as $antrian)
                                        <tr>
                                            <td>{{ $antrian->pengajuanSurat->mahasiswa->nama_lengkap ?? '-' }}</td>
                                            <td>{{ $antrian->pengajuanSurat->mahasiswa->programStudi->nama_prodi ?? '-' }}</td>
                                            <td>{{ $antrian->pengajuanSurat->jenisSurat->nama_surat ?? '-' }}</td>
                                            <td>{{ optional($antrian->pengajuanSurat->created_at)->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('pejabat.approval.show', $antrian->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    Tinjau
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Riwayat Terbaru --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Riwayat Approval Terbaru</h6>
                    <a href="{{ route('pejabat.approval.riwayat') }}" class="btn btn-sm btn-outline-secondary">
                        Lihat Riwayat
                    </a>
                </div>
                <div class="card-body">
                    @if($riwayatTerbaru->isEmpty())
                        <p class="mb-0 text-muted">Belum ada riwayat approval.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($riwayatTerbaru as $item)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $item->pengajuanSurat->jenisSurat->nama_surat ?? '-' }}</strong>
                                            <div class="small text-muted">
                                                {{ $item->pengajuanSurat->mahasiswa->nama_lengkap ?? '-' }}<br>
                                                {{ $item->tanggal_approval ? \Carbon\Carbon::parse($item->tanggal_approval)->format('d/m/Y H:i') : '-' }}
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            @if($item->status_approval == 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($item->status_approval == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($item->status_approval) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($item->catatan_pejabat)
                                        <div class="small mt-1 text-muted">
                                            <em>Catatan: {{ $item->catatan_pejabat }}</em>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
