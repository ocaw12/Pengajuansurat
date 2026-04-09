@extends('layouts.app')

@section('title', 'Manajemen Jenis Surat')
@section('page-title', 'Manajemen Jenis Surat')

@push('styles')
<style>
    /* Styling Tabel Modern */
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #6c757d;
        border-top: none;
    }
    
    .table tbody tr {
        transition: all 0.2s;
    }
    
    .table tbody tr:hover {
        background-color: rgba(255, 193, 7, 0.03) !important;
    }

    /* Custom Badges */
    .badge-code {
        background-color: #e9ecef;
        color: #495057;
        font-weight: 600;
        padding: 0.5em 0.8em;
    }

    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: transform 0.2s;
    }
    
    .btn-action:hover {
        transform: scale(1.1);
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')

<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-0">Pengaturan Persuratan</h4>
        <p class="text-muted small mb-0">Kelola daftar dokumen dan alur persetujuan sistem.</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin_akademik.jenis-surat.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Tambah Baru
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Nama Surat</th>
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th class="text-center">Alur Approval</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisSurats as $index => $jenis)
                    <tr>
                        <td class="ps-4">
                            <span class="text-muted fw-bold">{{ $index + 1 }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $jenis->nama_surat }}</div>
                        </td>
                        <td>
                            <span class="badge badge-code rounded-pill">{{ $jenis->kode_surat }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $jenis->kategori }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2">
                                <i class="bi bi-layers me-1"></i> {{ $jenis->alurApprovals()->count() }} Level
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('admin_akademik.jenis-surat.show', $jenis->id) }}" 
                                   class="btn btn-action btn-light border" title="Lihat Detail">
                                    <i class="bi bi-eye text-info"></i>
                                </a>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin_akademik.jenis-surat.edit', $jenis->id) }}" 
                                   class="btn btn-action btn-light border" title="Edit">
                                    <i class="bi bi-pencil-square text-warning"></i>
                                </a>

                                {{-- Tombol Delete --}}
                                <button type="button" class="btn btn-action btn-light border" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $jenis->id }}" title="Hapus">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-5 text-center">
                            <img src="https://illustrations.popsy.co/amber/files-sent.svg" alt="Empty" style="height: 150px;" class="mb-3">
                            <h6 class="text-muted">Belum ada jenis surat yang ditambahkan.</h6>
                            <a href="{{ route('admin_akademik.jenis-surat.create') }}" class="btn btn-link text-decoration-none">Buat sekarang &rarr;</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus yang Lebih Rapi --}}
@foreach($jenisSurats as $jenis)
<div class="modal fade" id="deleteModal{{ $jenis->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4 text-center">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <h5 class="mt-3 fw-bold">Hapus Jenis Surat?</h5>
                <p class="text-muted">Anda akan menghapus <strong>{{ $jenis->nama_surat }}</strong>. Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin_akademik.jenis-surat.destroy', $jenis->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 rounded-pill">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection