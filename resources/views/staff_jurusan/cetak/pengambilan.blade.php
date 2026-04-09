@extends('layouts.app')

@section('title', 'Antrian Pengambilan')

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

    /* Icon Box untuk Pengambilan */
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

    /* Soft Green Button untuk "Sudah Diambil" */
    .btn-taken {
        background-color: #ecfdf5;
        color: #059669;
        border: 1px solid #d1fae5;
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: 0.2s;
    }
    .btn-taken:hover {
        background-color: #059669;
        color: #fff;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
    }

    .badge-surat-soft {
        background-color: #fff9ed;
        color: #b45309;
        border: 1px solid #fef3c7;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Antrian Pengambilan Surat</h5>
            <p class="text-muted small mb-0">Daftar surat yang sudah siap dan menunggu kehadiran mahasiswa.</p>
        </div>
        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-bold">
            <i class="bi bi-hand-index-thumb me-1"></i> {{ $pengajuans->count() }} Menunggu Diambil
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Data Surat & Mahasiswa</th>
                        <th>Jenis Surat</th>
                        <th class="text-center">Tgl. Siap Ambil</th>
                        <th class="text-center pe-4" style="width: 220px;">Konfirmasi Ambil</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="bi bi-mailbox fs-5"></i>
                                </div>
                                <div>
                                    <div class="badge-nomor mb-1">{{ $pengajuan->nomor_surat }}</div>
                                    <div class="fw-bold text-dark fs-6">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-surat-soft">
                                {{ $pengajuan->jenisSurat->nama_surat }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="text-dark small fw-medium">{{ $pengajuan->updated_at->format('d M Y') }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $pengajuan->updated_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="text-center pe-4">
                            <form action="{{ route('staff_jurusan.cetak.diambil', $pengajuan) }}" method="POST" 
                                  onsubmit="return confirm('Konfirmasi bahwa mahasiswa SUDAH mengambil surat ini?');">
                                @csrf
                                <button type="submit" class="btn btn-taken d-inline-flex align-items-center">
                                    <i class="bi bi-check2-circle me-2 fs-6"></i> Sudah Diambil
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4 text-muted">
                                <i class="bi bi-check2-all display-4 opacity-25"></i>
                                <p class="mt-3">Kosong. Tidak ada surat yang menunggu pengambilan.</p>
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