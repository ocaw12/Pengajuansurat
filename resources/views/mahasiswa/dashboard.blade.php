@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard Mahasiswa')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Dashboard Mahasiswa</h4>
            <p class="mb-0 text-muted">
                Halo, {{ Auth::user()->name ?? 'Mahasiswa' }}. Ini ringkasan pengajuan suratmu.
            </p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Buat Pengajuan Baru
            </a>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="row mb-4">
        {{-- Total Pengajuan --}}
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Pengajuan</h6>
                    <h2 class="fw-bold mb-0">{{ $totalPengajuan }}</h2>
                    <small class="text-muted">Semua surat yang pernah kamu ajukan.</small>
                </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Selesai / Terbit</h6>
                    <h2 class="fw-bold mb-0 text-success">{{ $totalSelesai }}</h2>
                    <small class="text-muted">Surat yang sudah selesai diproses.</small>
                </div>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Ditolak / Perlu Revisi</h6>
                    <h2 class="fw-bold mb-0 text-danger">{{ $totalDitolak }}</h2>
                    <small class="text-muted">Pengajuan yang pernah ditolak atau diminta revisi.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Pengajuan Terbaru --}}
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Pengajuan Terbaru</h6>
                    <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua Riwayat
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($pengajuanTerbaru->isEmpty())
                        <p class="p-3 mb-0 text-muted">
                            Belum ada pengajuan surat. Yuk mulai dengan klik tombol
                            <strong>"Buat Pengajuan Baru"</strong> di kanan atas.
                        </p>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jenis Surat</th>
                                        <th>Keperluan</th>
                                        <th>Tgl. Dibuat</th>
                                        <th>Status</th>
                                        <th>Metode</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuanTerbaru as $pengajuan)
                                        <tr>
                                            <td>{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($pengajuan->keperluan, 40) }}</td>
                                            <td>{{ optional($pengajuan->tanggal_pengajuan)->format('d M Y') }}</td>
                                            <td>
                                                @include('partials.status_badge', [
                                                    'status' => $pengajuan->status_pengajuan
                                                ])
                                            </td>
                                            <td>
                                                <span class="badge {{ $pengajuan->metode_pengambilan == 'digital' ? 'bg-info' : 'bg-secondary' }}">
                                                    {{ $pengajuan->metode_pengambilan }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}"
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                    Detail
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

        {{-- Panel Info Samping --}}
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-2"><i class="bi bi-info-circle me-1"></i> Tips Pengajuan</h6>
                    <ul class="small mb-0 text-muted">
                        <li>Pastikan data di form pengajuan diisi lengkap dan benar.</li>
                        <li>Cek kembali <strong>status pengajuan</strong> di menu Riwayat Pengajuan.</li>
                        <li>Jika diminta revisi, baca catatan dengan teliti sebelum mengajukan ulang.</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-2"><i class="bi bi-bell me-1"></i> Status Singkat</h6>
                    <p class="small mb-1 text-muted">
                        Warna badge status di riwayat:
                    </p>
                    <ul class="small mb-0 text-muted">
                        <li><span class="badge bg-warning text-dark">menunggu</span> &mdash; masih dalam proses.</li>
                        <li><span class="badge bg-success">selesai</span> &mdash; surat sudah terbit.</li>
                        <li><span class="badge bg-danger">ditolak / perlu_revisi</span> &mdash; cek catatan dari admin/staff.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
