@extends('layouts.app')

@section('title', 'Periksa Pengajuan')

@push('styles')
<style>
    .card { border: none; border-radius: 12px; }
    .card-header { padding: 0.75rem 1.25rem; background: #fff; border-bottom: 1px solid #f1f5f9; }
    
    /* Layout Padat */
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .info-item { padding: 8px 12px; background: #f8fafc; border-radius: 8px; border: 1px solid #f1f5f9; }
    .info-label { color: #64748b; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
    .info-value { color: #1e293b; font-weight: 600; font-size: 0.85rem; line-height: 1.2; }
    
    /* Area Data Pendukung yang Scrollable jika panjang */
    .scroll-area { max-height: 350px; overflow-y: auto; padding-right: 5px; }
    .scroll-area::-webkit-scrollbar { width: 4px; }
    .scroll-area::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    /* Buttons */
    .btn-action { padding: 0.6rem; border-radius: 10px; font-weight: 700; font-size: 0.8rem; }
    .btn-validate { background-color: #f59e0b; color: #fff; border: none; }
    .btn-validate:hover { background-color: #d97706; color: #fff; }
    .btn-reject { background-color: #fff; color: #ef4444; border: 1px solid #fee2e2; }

    .form-control-sm { font-size: 0.85rem; border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Detail Pengajuan</h6>
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Menunggu Validasi</span>
            </div>
            <div class="card-body p-3">
                <div class="info-grid mb-3">
                    <div class="info-item">
                        <div class="info-label">Mahasiswa</div>
                        <div class="info-value">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">{{ $pengajuan->mahasiswa->nim }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Prodi</div>
                        <div class="info-value text-truncate">{{ $pengajuan->mahasiswa->programStudi->nama_prodi }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jenis Surat</div>
                        <div class="info-value text-primary">{{ $pengajuan->jenisSurat->nama_surat }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Metode</div>
                        <div class="info-value"><i class="bi bi-send-fill small"></i> {{ ucfirst($pengajuan->metode_pengambilan) }}</div>
                    </div>
                </div>

                <div class="info-item mb-3 w-100">
                    <div class="info-label">Keperluan</div>
                    <div class="info-value fw-normal text-secondary italic small">"{{ $pengajuan->keperluan }}"</div>
                </div>

                @if($pengajuan->data_pendukung)
                <h6 class="fw-bold small mb-2"><i class="bi bi-paperclip"></i> Lampiran & Data Tambahan</h6>
                <div class="scroll-area">
                    @foreach($pengajuan->data_pendukung as $key => $value)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <span class="small text-muted text-capitalize">{{ str_replace('_', ' ', $key) }}</span>
                        <span class="small fw-bold text-end">
                            @if(is_string($value) && Str::contains($value, 'dokumen_pengajuan'))
                                <a href="{{ asset('storage/'.$value) }}" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-2" style="font-size: 0.7rem;">
                                    <i class="bi bi-file-earmark-pdf"></i> Lihat
                                </a>
                            @else
                                {{ is_array($value) ? implode(', ', $value) : $value }}
                            @endif
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-check me-2"></i>Panel Validasi</h6>
            </div>
            <div class="card-body p-3 d-flex flex-column">
                <form action="{{ route('staff_jurusan.validasi.submit', $pengajuan) }}" method="POST" class="flex-grow-1">
                    @csrf
                    <div class="mb-3">
                        <label for="catatan_revisi" class="info-label fw-bold text-dark">Catatan / Alasan Revisi</label>
                        <textarea class="form-control form-control-sm" name="catatan_revisi" id="catatan_revisi" rows="6" 
                                  placeholder="Tulis alasan jika berkas ditolak atau perlu diperbaiki mahasiswa..."></textarea>
                    </div>

                    <div class="d-grid gap-2 mt-auto">
                        <button type="submit" name="action" value="validasi" class="btn btn-action btn-validate">
                            <i class="bi bi-check-circle-fill me-2"></i> Validasi & Teruskan
                        </button>
                        <button type="submit" name="action" value="tolak" class="btn btn-action btn-reject">
                            <i class="bi bi-x-circle me-2"></i> Tolak / Minta Revisi
                        </button>
                    </div>
                </form>
                
                <div class="mt-3 p-2 rounded bg-light border text-center">
                    <p class="mb-0 text-muted" style="font-size: 0.7rem;">
                        <i class="bi bi-exclamation-circle me-1"></i> Periksa kembali sebelum mengirim.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection