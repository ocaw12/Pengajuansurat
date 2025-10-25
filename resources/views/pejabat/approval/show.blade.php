@extends('layouts.app')

@section('title', 'Tinjau Pengajuan')
@section('page-title', 'Tinjau Detail Pengajuan')

@section('content')
<div class="row">
    <!-- Kolom Kiri: Detail Pengajuan -->
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Pengajuan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Nama Mahasiswa</div>
                    <div class="col-sm-8 fw-bold">{{ $approval->pengajuanSurat->mahasiswa->nama_lengkap }} ({{ $approval->pengajuanSurat->mahasiswa->nim }})</div>
                </div>
                 <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Program Studi</div>
                    <div class="col-sm-8">{{ $approval->pengajuanSurat->mahasiswa->programStudi->nama_prodi }}</div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Jenis Surat</div>
                    <div class="col-sm-8 fw-bold">{{ $approval->pengajuanSurat->jenisSurat->nama_surat }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Keperluan</div>
                    <div class="col-sm-8">{{ $approval->pengajuanSurat->keperluan }}</div>
                </div>
                <!-- Tampilkan Data Dinamis (jika ada) -->
                @if($approval->pengajuanSurat->data_pendukung)
                    <hr>
                    <h6 class="text-muted">Data Pendukung:</h6>
                    @foreach($approval->pengajuanSurat->data_pendukung as $key => $value)
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">{{ Str::title(str_replace('_', ' ', $key)) }}</div>
                        <div class="col-sm-8">{{ $value }}</div>
                    </div>
                    @endforeach
                @endif
            </div>
            <!-- (Opsional) Tampilkan preview PDF jika diperlukan -->
        </div>
    </div>

    <!-- Kolom Kanan: Form Aksi -->
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Tindakan Persetujuan</h5>
            </div>
            <div class="card-body">
                <p>Tinjau data di samping. Persetujuan Anda akan membubuhkan Tanda Tangan Digital Anda pada dokumen.</p>
                <form action="{{ route('pejabat.approval.submit', $approval) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="catatan_pejabat" class="form-label">Catatan (Wajib diisi jika menolak)</label>
                        <textarea class="form-control" name="catatan_pejabat" id="catatan_pejabat" rows="4" placeholder="Jika ditolak, berikan alasan jelas..."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="action" value="setuju" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i> Setujui Surat
                        </button>
                        <button type="submit" name="action" value="tolak" class="btn btn-outline-danger">
                            <i class="bi bi-x-circle me-2"></i> Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
