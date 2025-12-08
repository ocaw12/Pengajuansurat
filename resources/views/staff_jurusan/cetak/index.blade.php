@extends('layouts.app')

@section('title', 'Antrian Perlu Dicetak')
@section('page-title', 'Antrian Perlu Dicetak')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff_jurusan.dashboard') }}">Staff Jurusan</a></li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Surat Perlu Dicetak ({{ $pengajuans->count() }})</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Daftar surat yang sudah disetujui (status: Selesai) dan siap untuk dicetak. Setelah dicetak, klik "Tandai Siap Diambil" untuk mengirim notifikasi WA ke mahasiswa.</p>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nomor Surat</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">No. HP (WA)</th>
                        <th scope="col">Tgl. Selesai</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                    <tr>
                        <td class="fw-bold">{{ $pengajuan->nomor_surat }}</td>
                        <td>{{ $pengajuan->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $pengajuan->mahasiswa->no_telepon ?? '-' }}</td>
                        <td>{{ $pengajuan->updated_at->format('d M Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('preview.surat', basename($pengajuan->file_hasil_pdf)) }}" target="_blank" class="btn btn-secondary btn-sm" title="Download untuk Cetak">
                                <i class="bi bi-printer"></i> Cetak/Preview
                            </a>
                            
                            <form action="{{ route('staff_jurusan.cetak.siapDiambil', $pengajuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin surat ini sudah dicetak dan siap diambil? Notifikasi WA akan dikirim ke mahasiswa.');">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm" title="Tandai Siap Diambil & Kirim WA">
                                    <i class="bi bi-send"></i> Tandai Siap Diambil
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada surat yang perlu dicetak.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection