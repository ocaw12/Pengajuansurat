@extends('layouts.app')

@section('title', 'Detail Jenis Surat')
@section('page-title', 'Detail Jenis Surat')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title">
            <i class="bi bi-file-earmark-text me-2"></i>
            Detail Jenis Surat: {{ $jenisSurat->nama_surat }}
        </h5>

        <div>
            <a href="{{ route('admin_akademik.jenis-surat.edit', $jenisSurat->id) }}"
               class="btn btn-warning btn-sm me-2">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>

            <form action="{{ route('admin_akademik.jenis-surat.destroy', $jenisSurat->id) }}"
                  method="POST" class="d-inline"
                  onsubmit="return confirm('Yakin ingin menghapus jenis surat ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </form>

            <a href="{{ route('admin_akademik.jenis-surat.index') }}"
               class="btn btn-secondary btn-sm ms-2">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">

        {{-- ===================== --}}
        {{-- Bagian 1: Detail Surat --}}
        {{-- ===================== --}}
        <h5 class="fw-bold mb-3">📄 Detail Informasi</h5>

        <div class="row mb-4">
            <div class="col-md-6 mb-2">
                <p class="mb-1 fw-semibold">Nama Surat</p>
                <div class="p-2 bg-light rounded">{{ $jenisSurat->nama_surat }}</div>
            </div>
            <div class="col-md-3 mb-2">
                <p class="mb-1 fw-semibold">Kode Surat</p>
                <div class="p-2 bg-light rounded">
                    <span class="badge bg-secondary">{{ $jenisSurat->kode_surat }}</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <p class="mb-1 fw-semibold">Kategori</p>
                <div class="p-2 bg-light rounded">{{ $jenisSurat->kategori }}</div>
            </div>
        </div>

        <div class="mb-4">
            <p class="mb-1 fw-semibold">Format Penomoran</p>
            <div class="p-2 bg-light rounded">
                <code>{{ $jenisSurat->format_penomoran }}</code>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- Bagian 2: Template Surat --}}
        {{-- ===================== --}}
        <h5 class="fw-bold mb-3">📝 Isi Template Surat</h5>

        <div class="p-3 border rounded bg-light mb-4" style="white-space: pre-line;">
            {{ $jenisSurat->isi_template }}
        </div>

        {{-- ===================== --}}
        {{-- Bagian 3: Field Tambahan --}}
        {{-- ===================== --}}
        <h5 class="fw-bold mb-3">🧩 Field Tambahan (Form Schema)</h5>

        @php
            // Normalisasi form_schema jadi array
            $schema = $jenisSurat->form_schema;

            if (is_string($schema)) {
                $decoded = json_decode($schema, true);
                $schema = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($schema)) {
                $schema = [];
            }
        @endphp

        @if(!empty($schema) && count($schema) > 0)
            <ul class="list-group mb-4">
                @foreach($schema as $field)
                    <li class="list-group-item">
                        <strong>{{ $field['label'] ?? '-' }}</strong> <br>
                        Placeholder:
                        <code>[{{ $field['name'] ?? '-' }}]</code> <br>
                        Tipe Field:
                        <span class="badge bg-info text-dark">{{ $field['type'] ?? '-' }}</span>
                        @if(!empty($field['required']))
                            <span class="badge bg-danger ms-1">Wajib</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted fst-italic mb-4">Tidak ada field tambahan.</p>
        @endif

        {{-- ===================== --}}
        {{-- Bagian 4: Alur Approval --}}
       <h5 class="fw-bold mb-3">🔗 Alur Persetujuan (Approval)</h5>

@if($alurApprovals->count() > 0)
    <div class="list-group mb-4">
        @foreach($alurApprovals as $step)
            <div class="list-group-item py-3 d-flex justify-content-between align-items-center">

                <div>
                    <h6 class="fw-bold mb-1">{{ $step->masterJabatan->nama_jabatan }}</h6>
                    <small class="text-muted">
                        Scope: {{ $step->scope }}
                    </small>
                </div>

                <span class="badge bg-warning text-dark px-3 py-2">
                    Step #{{ $step->urutan }}
                </span>
            </div>
        @endforeach
    </div>
@else
    <p class="text-muted fst-italic">Belum ada alur approval.</p>
@endif


    </div>
</div>

@endsection
