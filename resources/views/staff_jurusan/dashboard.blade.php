@extends('layouts.app') {{-- atau layouts.main / layouts.staff_jurusan, sesuaikan --}}

@section('title', 'Dashboard Staff Jurusan')

@section('breadcrumb')
    {{-- Supaya breadcrumb jadi: Home / Dashboard --}}
    {{-- Kalau mau custom breadcrumb lain, isi di sini --}}
@endsection

@section('page-title', 'Dashboard Staff Jurusan')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Dashboard Staff Jurusan</h4>
            <p class="mb-0 text-muted">
                Halo, {{ Auth::user()->name ?? '' }}. Ini ringkasan pengajuan surat di prodi Anda.
            </p>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Antrian Validasi</h6>
                    <h2 class="fw-bold mb-0">{{ $totalPendingValidasi }}</h2>
                    <small class="text-muted">Surat yang masih menunggu validasi staff jurusan.</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Perlu Dicetak</h6>
                    <h2 class="fw-bold mb-0">{{ $totalPerluDicetak }}</h2>
                    <small class="text-muted">Surat yang sudah siap dicetak.</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Siap Diambil</h6>
                    <h2 class="fw-bold mb-0">{{ $totalSiapDiambil }}</h2>
                    <small class="text-muted">Surat cetak yang sudah siap diambil mahasiswa.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Antrian Validasi --}}
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Antrian Validasi</h6>
                    <a href="{{ route('staff_jurusan.validasi.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($antrianValidasi->isEmpty())
                        <p class="p-3 mb-0 text-muted">Belum ada pengajuan yang menunggu validasi.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Jenis Surat</th>
                                        <th>Tgl Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($antrianValidasi as $pengajuan)
                                        <tr>
                                            <td>{{ $pengajuan->mahasiswa->nama_lengkap ?? '-' }}</td>
                                            <td>{{ $pengajuan->mahasiswa->programStudi->nama_prodi ?? '-' }}</td>
                                            <td>{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</td>
                                            <td>{{ optional($pengajuan->tanggal_pengajuan)->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('staff_jurusan.validasi.show', $pengajuan->id) }}"
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

      {{-- Antrian Perlu Dicetak --}}
<div class="col-lg-5 mb-4">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Antrian Perlu Dicetak</h6>
            <a href="{{ route('staff_jurusan.cetak.index') }}" class="btn btn-sm btn-outline-secondary">
                Lihat Semua
            </a>
        </div>
        <div class="card-body">
            @if($antrianPerluDicetak->isEmpty())
                <p class="mb-0 text-muted">Belum ada surat yang perlu dicetak.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($antrianPerluDicetak as $item)
                        <li class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    {{-- Jenis surat --}}
                                    <strong>{{ optional($item->jenisSurat)->nama_surat ?? '-' }}</strong>

                                    <div class="small text-muted">
                                        {{-- Nama mahasiswa --}}
                                        {{ optional($item->mahasiswa)->nama_lengkap ?? '-' }}<br>

                                        {{-- Program studi (kalau mau ditampilkan) --}}
                                        {{ optional(optional($item->mahasiswa)->programStudi)->nama_prodi ?? '-' }}<br>

                                        {{-- Tanggal update --}}
                                        {{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </div>

                                <div class="text-end">
                                    <form action="{{ route('staff_jurusan.cetak.siapDiambil', $item->id) }}"
                                          method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Tandai Siap Diambil
                                        </button>
                                    </form>
                                </div>
                            </div>
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
