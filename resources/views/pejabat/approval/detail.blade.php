@extends('layouts.app')

@section('title', 'Detail Surat')

@section('content')
<div class="row">

    <!-- 🔵 INFORMASI SURAT -->
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Surat</h5>
            </div>

            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Nama Mahasiswa</div>
                    <div class="col-sm-8 fw-bold">
                        {{ $pengajuan->mahasiswa->nama_lengkap }} 
                        ({{ $pengajuan->mahasiswa->nim }})
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Program Studi</div>
                    <div class="col-sm-8">
                        {{ $pengajuan->mahasiswa->programStudi->nama_prodi ?? '-' }}
                    </div>
                </div>

                <hr>

                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Jenis Surat</div>
                    <div class="col-sm-8 fw-bold">
                        {{ $pengajuan->jenisSurat->nama_surat }}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Tanggal Pengajuan</div>
                    <div class="col-sm-8">
                        {{ $pengajuan->tanggal_pengajuan->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Status</div>
                    <div class="col-sm-8">
                        @include('partials.status_badge', ['status' => $pengajuan->status_pengajuan])
                    </div>
                </div>

                <hr>

                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Keperluan</div>
                    <div class="col-sm-8">
                        {{ $pengajuan->keperluan }}
                    </div>
                </div>

                {{-- Data tambahan --}}
                @if($pengajuan->data_pendukung)
                    @foreach($pengajuan->data_pendukung as $key => $value)
                        <div class="row mb-2">
                            <div class="col-sm-4 text-muted">
                                {{ Str::title(str_replace('_', ' ', $key)) }}
                            </div>
                            <div class="col-sm-8">
                                {{ $value }}
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>

        <!-- 🔥 HASIL SURAT -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Hasil Surat</h5>
            </div>

            <div class="card-body">

                {{-- ✅ SUDAH SELESAI --}}
                @if(in_array($pengajuan->status_pengajuan, ['selesai','siap_diambil','sudah_diambil']))
                    
                    <div class="alert alert-success">
                        <h5>Surat Sudah Selesai</h5>
                        <p>Silakan akses surat di bawah ini:</p>

                        <a href="{{ route('preview.surat', basename($pengajuan->file_hasil_pdf)) }}" 
                           target="_blank" 
                           class="btn btn-secondary">
                            <i class="bi bi-eye"></i> Preview
                        </a>

                        <a href="{{ route('download.surat', basename($pengajuan->file_hasil_pdf)) }}" 
                           class="btn btn-primary">
                            <i class="bi bi-download"></i> Download
                        </a>
                    </div>

                {{-- ⏳ MASIH PROSES --}}
                @elseif($pengajuan->status_pengajuan == 'divalidasi_admin')
                    
                    <div class="alert alert-warning">
                        Surat sedang menunggu approval pejabat.
                    </div>

                {{-- ❌ DITOLAK --}}
                @elseif($pengajuan->status_pengajuan == 'perlu_revisi')
                    
                    <div class="alert alert-danger">
                        <strong>Ditolak / Perlu Revisi</strong><br>
                        {{ $pengajuan->catatan_revisi }}
                    </div>

                @else
                    <p class="text-muted">Surat sedang dalam proses.</p>
                @endif

            </div>
        </div>
    </div>

    <!-- 🟢 TRACKING -->
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Tracking Approval</h5>
            </div>

            <div class="card-body">

                <ul class="list-group list-group-flush">

                    <!-- STEP 1 -->
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Pengajuan Dibuat</strong><br>
                            <small>{{ $pengajuan->tanggal_pengajuan->format('d M Y, H:i') }}</small>
                        </div>
                        <i class="bi bi-check-circle-fill text-success"></i>
                    </li>

                    <!-- STEP 2 -->
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Validasi Staff</strong><br>
                            @if($pengajuan->admin_validator_id)
                                <small class="text-success">Sudah divalidasi</small>
                            @else
                                <small class="text-warning">Menunggu</small>
                            @endif
                        </div>

                        @if($pengajuan->admin_validator_id)
                            <i class="bi bi-check-circle-fill text-success"></i>
                        @else
                            <i class="bi bi-hourglass-split text-warning"></i>
                        @endif
                    </li>

                    <!-- APPROVAL PEJABAT -->
                    @foreach($pengajuan->approvalPejabats->sortBy('urutan_approval') as $approval)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $approval->pejabat->masterJabatan->nama_jabatan }}</strong><br>
                                <small>{{ $approval->pejabat->nama_lengkap }}</small>

                                {{-- 🔥 Highlight kalau ini pejabat yang login --}}
                                @if(auth()->user()->pejabat && $approval->pejabat_id == auth()->user()->pejabat->id)
                                    <br><span class="badge bg-primary">Anda</span>
                                @endif

                                @if($approval->status_approval == 'pending')
                                    <br><small class="text-warning">Menunggu approval</small>
                                @endif
                            </div>

                            @if($approval->status_approval == 'disetujui')
                                <i class="bi bi-check-circle-fill text-success"></i>
                            @elseif($approval->status_approval == 'ditolak')
                                <i class="bi bi-x-circle-fill text-danger"></i>
                            @else
                                <i class="bi bi-hourglass-split text-warning"></i>
                            @endif
                        </li>
                    @endforeach

                </ul>

            </div>
        </div>
    </div>

</div>
@endsection