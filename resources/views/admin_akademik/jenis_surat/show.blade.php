@extends('layouts.app')

@section('title', 'Detail Jenis Surat')
@section('page-title', 'Detail Jenis Surat')

@push('styles')
<style>
    /* Styling Timeline untuk Alur Approval */
    .timeline-steps {
        position: relative;
        padding-left: 20px;
        border-left: 2px dashed #dee2e6;
        margin-left: 10px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: "";
        position: absolute;
        left: -27px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: var(--bs-warning);
        border: 2px solid white;
    }
    
    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .template-box {
        background-color: #f8f9fa;
        font-family: 'Courier New', Courier, monospace;
        border-left: 4px solid #0d6efd;
        line-height: 1.6;
    }

    .field-card {
        border-left: 4px solid #17a2b8;
        transition: transform 0.2s;
    }
    .field-card:hover { transform: translateX(5px); }
</style>
@endpush

@section('content')

{{-- Header & Actions --}}
<div class="row mb-4 align-items-center">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin_akademik.jenis-surat.index') }}">Jenis Surat</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">{{ $jenisSurat->nama_surat }}</h4>
    </div>
    <div class="col-auto d-flex gap-2">
        <a href="{{ route('admin_akademik.jenis-surat.edit', $jenisSurat->id) }}" class="btn btn-warning rounded-pill px-3 shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
        <button type="button" class="btn btn-danger rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="bi bi-trash me-1"></i> Hapus
        </button>
        <a href="{{ route('admin_akademik.jenis-surat.index') }}" class="btn btn-light border rounded-pill px-3">
            Kembali
        </a>
    </div>
</div>

<div class="row">
    {{-- Kolom Kiri: Informasi Utama & Template --}}
    <div class="col-lg-8">
        {{-- Card: Informasi Dasar --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-4"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Umum</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="info-label">Kode Surat</div>
                        <div class="fw-bold fs-5 text-dark">{{ $jenisSurat->kode_surat }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Kategori</div>
                        <div class="fw-bold fs-5 text-dark">{{ $jenisSurat->kategori }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Format Nomor</div>
                        <div class="text-primary font-monospace small bg-primary-subtle p-1 rounded">{{ $jenisSurat->format_penomoran }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Isi Template --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-code text-primary me-2"></i>Template Konten</h6>
            </div>
            <div class="card-body pt-0">
                <div class="p-4 rounded template-box shadow-inner">
                    {!! nl2br(e($jenisSurat->isi_template)) !!}
                </div>
                <div class="mt-2 text-muted small italic">
                    <i class="bi bi-info-circle"></i> Teks di dalam <code>[brackets]</code> akan diganti secara otomatis oleh sistem.
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Alur & Fields --}}
    <div class="col-lg-4">
        {{-- Card: Alur Approval --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-diagram-3 text-warning me-2"></i>Alur Persetujuan</h6>
            </div>
            <div class="card-body">
                @if($alurApprovals->count() > 0)
                    <div class="timeline-steps">
                        @foreach($alurApprovals as $step)
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $step->masterJabatan->nama_jabatan }}</div>
                                        <div class="text-muted" style="font-size: 0.8rem;">Scope: {{ $step->scope }}</div>
                                    </div>
                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle">Lvl {{ $step->urutan }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-slash-circle text-muted d-block fs-2 mb-2"></i>
                        <p class="text-muted small">Belum ada alur approval.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Card: Field Tambahan --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-plus-square text-info me-2"></i>Input Form</h6>
            </div>
            <div class="card-body pt-0">
                @php
                    $schema = is_string($jenisSurat->form_schema) ? json_decode($jenisSurat->form_schema, true) : $jenisSurat->form_schema;
                @endphp

                @if(!empty($schema) && is_array($schema))
                    <div class="d-flex flex-column gap-2">
                        @foreach($schema as $field)
                            <div class="p-3 border rounded-3 bg-light field-card shadow-xs">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-dark">{{ $field['label'] ?? 'Tanpa Label' }}</span>
                                    @if(!empty($field['required'])) <span class="badge bg-danger-subtle text-danger" style="font-size: 0.6rem;">WAJIB</span> @endif
                                </div>
                                <code class="small text-info">[{{ $field['name'] ?? '-' }}]</code>
                                <div class="mt-1">
                                    <span class="badge bg-white border text-muted" style="font-size: 0.7rem;">{{ $field['type'] ?? 'text' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small text-center py-2">Tidak ada field tambahan.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-body p-4 text-center">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <h5 class="mt-3 fw-bold">Hapus Jenis Surat?</h5>
                <p class="text-muted">Data ini akan dihapus permanen dari sistem.</p>
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin_akademik.jenis-surat.destroy', $jenisSurat->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 rounded-pill">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection