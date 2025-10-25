@extends('layouts.app')

@section('title', 'Antrian Cetak')
@section('page-title', 'Antrian Cetak & Pengambilan Surat')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Surat Siap Diambil ({{ $pengajuans->count() }})</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Daftar surat yang sudah disetujui dan memilih metode "Cetak". Silakan download, cetak, dan siapkan untuk diambil mahasiswa.</p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nomor Surat</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Tgl. Selesai</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                    <tr>
                        <td class="fw-bold">{{ $pengajuan->nomor_surat }}</td>
                        <td>{{ $pengajuan->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $pengajuan->jenisSurat->nama_surat }}</td>
                        <td>{{ $pengajuan->updated_at->format('d M Y H:i') }}</td>
                        <td class="text-center">
                            <!-- Tombol Download PDF untuk dicetak -->
                            <a href="{{ $pengajuan->file_hasil_pdf }}" target="_blank" class="btn btn-secondary btn-sm" title="Download untuk Cetak">
                                <i class="bi bi-printer"></i> Cetak
                            </a>
                            
                            <!-- Tombol Konfirmasi Pengambilan -->
                            <form action="{{ route('staff.validasi.diambil', $pengajuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin mahasiswa ini sudah mengambil surat fisiknya?');">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm" title="Tandai Sudah Diambil">
                                    <i class="bi bi-check-all"></i> Tandai Diambil
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada surat yang menunggu untuk diambil.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
