@extends('layouts.app')

@section('title', 'Antrian Perlu Dicetak')

@push('styles')
<style>
    /* Styling Card & Table */
    .card { border: none; border-radius: 15px; overflow: hidden; }
    .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; padding: 1.25rem 1.5rem; }
    
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border-top: none;
        padding: 1rem;
    }
    
    .table tbody tr { transition: all 0.2s; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background-color: #f8fafc; }

    /* Icon Box untuk Dokumen */
    .icon-box {
        width: 42px; height: 42px;
        background-color: #f8fafc;
        color: #64748b;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px; border: 1px solid #e2e8f0;
    }

    /* Badge Nomor Surat */
    .badge-nomor {
        background-color: #f1f5f9;
        color: #1e293b;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        border: 1px solid #e2e8f0;
    }

    /* Button Actions Style */
    .btn-print {
        background-color: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: 0.2s;
    }
    .btn-print:hover { background-color: #f1f5f9; color: #1e293b; }

    .btn-ready {
        background-color: #f59e0b;
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: 0.2s;
    }
    .btn-ready:hover {
        background-color: #d97706;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Antrian Perlu Dicetak</h5>
            <p class="text-muted small mb-0">Surat yang telah disetujui dan siap untuk proses cetak fisik.</p>
        </div>
        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-bold">
            <i class="bi bi-printer-fill me-1"></i> {{ $pengajuans->count() }} Surat
        </span>
    </div>

    <div class="card-body p-0">
        <div class="p-3 bg-light-subtle border-bottom">
            <div class="d-flex align-items-center text-muted small">
                <i class="bi bi-info-circle-fill me-2 text-warning"></i>
                <span>Setelah dicetak, klik <strong>"Siap Diambil"</strong> untuk mengirim notifikasi WhatsApp otomatis ke mahasiswa.</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Data Surat & Mahasiswa</th>
                        <th>Kontak (WA)</th>
                        <th class="text-center">Tgl. Selesai</th>
                        <th class="text-center pe-4" style="width: 320px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="bi bi-file-earmark-pdf fs-5"></i>
                                </div>
                                <div>
                                    <div class="badge-nomor mb-1">{{ $pengajuan->nomor_surat }}</div>
                                    <div class="fw-bold text-dark fs-6">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-whatsapp me-2 text-success"></i>
                                <span>{{ $pengajuan->mahasiswa->no_telepon ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="text-dark small fw-medium">{{ $pengajuan->updated_at->format('d M Y') }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $pengajuan->updated_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('preview.surat', basename($pengajuan->file_hasil_pdf)) }}" 
                                   target="_blank" class="btn btn-print d-inline-flex align-items-center">
                                    <i class="bi bi-printer me-2"></i> Cetak
                                </a>
                                
                                <form action="{{ route('staff_jurusan.cetak.siapDiambil', $pengajuan) }}" method="POST" 
                                      onsubmit="return confirm('Tandai surat siap diambil? Notifikasi WA akan dikirim.');">
                                    @csrf
                                    <button type="submit" class="btn btn-ready shadow-sm d-inline-flex align-items-center">
                                        <i class="bi bi-send-check me-2"></i> Siap Diambil
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4 text-muted">
                                <i class="bi bi-printer text-muted opacity-25" style="font-size: 3rem;"></i>
                                <p class="mt-3">Tidak ada antrian cetak saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection