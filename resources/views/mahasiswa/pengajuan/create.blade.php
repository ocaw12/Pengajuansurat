@extends('layouts.app')

@section('title', 'Buat Pengajuan Baru')
@section('page-title', 'Formulir Pengajuan Surat Baru')

@push('styles')
<style>
    .card { border: none; border-radius: 12px; width: 100%; }
    .card-body { padding: 1.75rem; }

    .form-label { font-size: 0.8rem; color: #475569; margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        border-color: #e2e8f0;
        background-color: #fff;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .method-card {
        border: 2px solid #f1f5f9;
        border-radius: 10px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        height: 100%;
    }
    .method-card i { font-size: 1.5rem; display: block; margin-bottom: 5px; color: #64748b; }
    .method-card span { font-size: 0.9rem; font-weight: 600; display: block; }
    
    .form-check-input:checked + .method-card {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    .form-check-input:checked + .method-card i { color: #3b82f6; }
    .form-check-input { display: none; }

    #form-dinamis-container {
        border: 1px dashed #cbd5e1;
        background-color: #f8fafc;
        border-radius: 12px;
    }

    .btn-submit { 
        border-radius: 8px; 
        padding: 0.7rem 2rem; 
        font-weight: 600; 
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2); }

    /* Alert pengajuan aktif */
    .alert-active-pengajuan {
        background-color: #fefce8;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 0.9rem 1rem;
        font-size: 0.85rem;
        color: #92400e;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 p-3 mb-4 shadow-sm">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <strong class="small">Mohon perbaiki kesalahan berikut:</strong>
                    </div>
                    <ul class="mb-0 small ps-4">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label for="jenis_surat_id" class="form-label">1. Jenis Dokumen <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_surat_id') is-invalid @enderror" id="jenis_surat_id" name="jenis_surat_id" required>
                                <option value="" selected disabled>Pilih kategori surat yang ingin diajukan...</option>
                                @foreach($jenis_surats as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_surat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_surat_id')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Warning pengajuan aktif (muncul via JS) --}}
                        <div id="warning-pengajuan-aktif" class="alert-active-pengajuan mb-4" style="display: none;">
                            <i class="bi bi-clock-history me-2"></i>
                            <strong>Pengajuan Sedang Berjalan</strong>
                            <p class="mb-0 mt-1">Anda sudah memiliki pengajuan aktif untuk jenis surat ini. Silakan tunggu hingga proses <strong>selesai</strong> atau <strong>ditolak</strong> sebelum mengajukan kembali.</p>
                        </div>

                        <div class="mb-4">
                            <label for="keperluan" class="form-label">2. Deskripsi Keperluan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('keperluan') is-invalid @enderror" 
                                      id="keperluan" name="keperluan" rows="4" 
                                      placeholder="Jelaskan alasan pengajuan secara detail..." required>{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="form-dinamis-container" class="p-4 mb-4" style="display: none;">
                            <h6 class="fw-bold text-primary mb-3 small text-uppercase letter-spacing-1">
                                <i class="bi bi-patch-plus me-2"></i>Informasi Tambahan Dokumen
                            </h6>
                            <div id="dynamic-fields-wrapper"></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label">3. Metode Pengambilan</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="w-100 mb-0">
                                        <input class="form-check-input" type="radio" name="metode_pengambilan" value="digital" {{ old('metode_pengambilan', 'digital') == 'digital' ? 'checked' : '' }}>
                                        <div class="method-card">
                                            <i class="bi bi-cloud-check"></i>
                                            <span>Digital</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="w-100 mb-0">
                                        <input class="form-check-input" type="radio" name="metode_pengambilan" value="cetak" {{ old('metode_pengambilan') == 'cetak' ? 'checked' : '' }}>
                                        <div class="method-card">
                                            <i class="bi bi-printer"></i>
                                            <span>Cetak</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-10 mb-4">
                            <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-info-circle-fill me-2"></i>Catatan Penting</h6>
                            <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.5;">
                                Pastikan deskripsi keperluan minimal sesuai aturan karakter. Inputan tambahan di kolom kiri akan otomatis tersimpan jika terjadi kesalahan kirim.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center border-top pt-4 mt-2">
                    <button type="submit" id="btn-submit" class="btn btn-primary btn-submit shadow-sm">
                        <i class="bi bi-send-check-fill me-2"></i>Kirim Pengajuan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jenisSuratSelect = document.getElementById('jenis_surat_id');
        const container = document.getElementById('form-dinamis-container');
        const wrapper = document.getElementById('dynamic-fields-wrapper');
        const warningAktif = document.getElementById('warning-pengajuan-aktif');
        const btnSubmit = document.getElementById('btn-submit');

        const oldData = @json(old('data_pendukung') ?? []);

        // Data pengajuan aktif milik mahasiswa yang dikirim dari controller
        const pengajuanAktif = @json($pengajuan_aktif_ids ?? []);

        function checkAktif(id) {
            const aktif = pengajuanAktif.includes(parseInt(id));
            warningAktif.style.display = aktif ? 'block' : 'none';
            btnSubmit.disabled = aktif;
            if (aktif) {
                btnSubmit.classList.add('disabled');
            } else {
                btnSubmit.classList.remove('disabled');
            }
        }

        function loadFields(id) {
            if (!id) {
                container.style.display = 'none';
                return;
            }

            container.style.display = 'block';
            wrapper.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';

            fetch(`/mahasiswa/api/form-schema/${id}`)
                .then(res => res.json())
                .then(schema => {
                    wrapper.innerHTML = '';
                    if (schema && schema.length > 0) {
                        schema.forEach(field => {
                            const val = oldData[field.name] || '';
                            let html = `<div class="mb-3">
                                <label class="form-label small fw-bold">${field.label} <span class="text-danger">*</span></label>`;
                            
                            if (field.type === 'textarea') {
                                html += `<textarea class="form-control" name="data_pendukung[${field.name}]" rows="2" required>${val}</textarea>`;
                            } else if (field.type === 'file') {
                                html += `<input type="file" class="form-control" name="data_pendukung[${field.name}]" required>`;
                            } else if (field.type === 'date') {
                                const today = new Date().toISOString().split('T')[0];
                                html += `<input type="date" class="form-control" name="data_pendukung[${field.name}]" value="${val}" min="${today}" required>`;
                            } else {
                                html += `<input type="${field.type}" class="form-control" name="data_pendukung[${field.name}]" value="${val}" required>`;
                            }
                            html += `</div>`;
                            wrapper.innerHTML += html;
                        });
                    } else {
                        container.style.display = 'none';
                    }
                })
                .catch(() => {
                    wrapper.innerHTML = '<div class="text-danger small">Gagal memuat field tambahan.</div>';
                });
        }

        jenisSuratSelect.addEventListener('change', function() {
            checkAktif(this.value);
            loadFields(this.value);
        });

        if (jenisSuratSelect.value) {
            checkAktif(jenisSuratSelect.value);
            loadFields(jenisSuratSelect.value);
        }
    });
</script>
@endpush