@extends('layouts.app')

@section('title', 'Edit Jenis Surat')
@section('page-title', 'Edit Jenis Surat')

@push('styles')
<style>
    .schema-item, .approval-item {
        border: 1px solid #dee2e6; padding: 1rem; margin-bottom: 0.5rem;
        border-radius: 0.375rem; background-color: #f8f9fa; position: relative;
    }
    .remove-btn { position: absolute; top: 0.5rem; right: 0.5rem; cursor: pointer; }
    .edit-btn   { position: absolute; top: 0.5rem; right: 2.8rem; cursor: pointer; }
    .sort-handle { cursor: grab; color: #6c757d; }

    #isi_template {
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.88rem;
        line-height: 1.7;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #fdfdff;
        transition: border-color 0.2s;
    }
    #isi_template:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245,158,11,0.1);
        background: #fff;
    }
</style>
@endpush

@section('content')

@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Gagal Menyimpan!</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin_akademik.jenis-surat.update', $jenisSurat->id) }}"
      method="POST" id="jenisSuratForm">
    @csrf
    @method('PUT')

    <div class="row">

        <div class="col-lg-8 mb-4">

            {{-- Detail Surat --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0 card-title"><i class="bi bi-file-earmark-text me-2"></i>Detail Surat</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nama_surat" class="form-label fw-bold">Nama Surat <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('nama_surat') is-invalid @enderror"
                               id="nama_surat" name="nama_surat"
                               value="{{ old('nama_surat', $jenisSurat->nama_surat) }}" required>
                        @error('nama_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_surat" class="form-label fw-bold">Kode Surat <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('kode_surat') is-invalid @enderror"
                                   id="kode_surat" name="kode_surat"
                                   value="{{ old('kode_surat', $jenisSurat->kode_surat) }}" required>
                            <div class="form-text">Kode unik ini muncul di nomor surat.</div>
                            @error('kode_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori') is-invalid @enderror"
                                    id="kategori" name="kategori" required>
                                @foreach($kategoriOptions as $kategori)
                                    <option value="{{ $kategori }}"
                                        {{ old('kategori', $jenisSurat->kategori) == $kategori ? 'selected' : '' }}>
                                        {{ $kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="format_penomoran" class="form-label fw-bold">Format Penomoran <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('format_penomoran') is-invalid @enderror"
                               id="format_penomoran" name="format_penomoran"
                               value="{{ old('format_penomoran', $jenisSurat->format_penomoran) }}" required>
                        <div class="form-text">
                            Placeholder: <code>{nomor_urut}</code> <code>{kode_surat}</code>
                            <code>{kode_unit}</code> <code>{bulan_romawi}</code> <code>{tahun}</code>
                        </div>
                        @error('format_penomoran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            @include('admin_akademik.jenis_surat.partials.keyword_reference', [
                'tahunAjaranAktif' => $tahunAjaranAktif,
                'semesterAktif'    => $semesterAktif,
            ])

            {{-- Isi Template --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 card-title"><i class="bi bi-textarea-t me-2"></i>Isi Naskah Surat (Template)</h5>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size:0.68rem;">
                        <i class="bi bi-cursor-text me-1"></i>Klik keyword lalu sisipkan di posisi kursor
                    </span>
                </div>
                <div class="card-body">
                    <div class="alert alert-info small p-2 mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>Tips:</strong> Klik keyword di panel kuning di atas, lalu posisikan kursor
                        di textarea ini — keyword akan otomatis disisipkan di posisi kursor.
                    </div>
                    <textarea class="form-control @error('isi_template') is-invalid @enderror"
                              id="isi_template" name="isi_template"
                              rows="18" required>{{ old('isi_template', $jenisSurat->isi_template) }}</textarea>
                    @error('isi_template') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Field Tambahan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0 card-title"><i class="bi bi-ui-checks-grid me-2"></i>Field Tambahan untuk Mahasiswa</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning small p-2 mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Tambahkan hanya jika surat memerlukan data spesifik di luar "Keperluan".
                        <strong>Nama Kunci</strong> harus sama persis dengan placeholder di naskah,
                        dan akan otomatis muncul sebagai keyword di panel atas.
                    </div>
                    <div id="schema-container">
                        <p class="text-muted text-center py-3" id="schema-empty-msg">Belum ada field tambahan.</p>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="addSchemaButton">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Field Tambahan
                    </button>
                </div>
            </div>
        </div>

        {{-- Alur Approval --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm sticky-top" style="top: 80px;">
                <div class="card-header">
                    <h5 class="mb-0 card-title"><i class="bi bi-signpost-split me-2"></i>Alur Persetujuan</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info small p-2 mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Tentukan pejabat yang menyetujui <strong>secara berurutan</strong>. Minimal 1 langkah.
                    </div>
                    @error('approvals')
                        <div class="alert alert-danger small py-1 px-2 mb-2">{{ $message }}</div>
                    @enderror
                    <div id="approval-container">
                        <p class="text-muted text-center py-3" id="approval-empty-msg">Belum ada langkah approval.</p>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="addApprovalButton">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Langkah Approval
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-2 mb-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i>Update Jenis Surat
        </button>
        <a href="{{ route('admin_akademik.jenis-surat.index') }}"
           class="btn btn-outline-secondary px-4 ms-2">Batal</a>
    </div>
</form>

{{-- Modal: Form Schema --}}
<div class="modal fade" id="schemaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="schemaModalLabel">Tambah Field Tambahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editingSchemaIndex">
                <div class="mb-3">
                    <label class="form-label">Label Field <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="schema_label" placeholder="Judul Penelitian" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Kunci (Placeholder) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="schema_name"
                           placeholder="judul_penelitian" pattern="^[a-zA-Z0-9_]+$" required>
                    <div class="form-text">Hanya huruf, angka, underscore. Gunakan sebagai <code>[nama_kunci]</code> di template.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipe Field <span class="text-danger">*</span></label>
                    <select class="form-select" id="schema_type" required>
                        <option value="text">Teks Singkat</option>
                        <option value="textarea">Teks Panjang</option>
                        <option value="date">Tanggal</option>
                        <option value="number">Angka</option>
                        <option value="file">Upload Dokumen</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveSchemaButton">Simpan Field</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Alur Approval --}}
<div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel">Tambah Langkah Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editingApprovalIndex">
                <div class="mb-3">
                    <label class="form-label">Jabatan Pejabat <span class="text-danger">*</span></label>
                    <select class="form-select" id="approval_jabatan" required>
                        <option value="" disabled selected>-- Pilih Jabatan --</option>
                        @foreach($masterJabatans as $jabatan)
                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Scope Wewenang <span class="text-danger">*</span></label>
                    <select class="form-select" id="approval_scope" required>
                        <option value="PRODI">PRODI – berdasarkan prodi mahasiswa</option>
                        <option value="FAKULTAS">FAKULTAS – berdasarkan fakultas mahasiswa</option>
                        <option value="UNIVERSITAS">UNIVERSITAS – berlaku umum</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveApprovalButton">Simpan Langkah</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    let formSchema = @json(old('form_schema', $jenisSurat->form_schema ?? []));
    let approvals  = @json(old('approvals',  $approvalData ?? []));
    const masterJabatans = @json($masterJabatans->pluck('nama_jabatan', 'id'));

    const schemaModal   = new bootstrap.Modal(document.getElementById('schemaModal'));
    const approvalModal = new bootstrap.Modal(document.getElementById('approvalModal'));

    // ── RENDER SCHEMA ────────────────────────────────────────────
    function renderSchemaList() {
        const $c = $('#schema-container');
        $c.empty();
        if (!formSchema || !formSchema.length) {
            $c.html('<p class="text-muted text-center py-3" id="schema-empty-msg">Belum ada field tambahan.</p>');
        } else {
            formSchema.forEach((field, idx) => {
                $c.append(`
                    <div class="schema-item d-flex align-items-center mb-2">
                        <input type="hidden" name="form_schema[${idx}][label]" value="${escHtml(field.label)}">
                        <input type="hidden" name="form_schema[${idx}][name]"  value="${escHtml(field.name)}">
                        <input type="hidden" name="form_schema[${idx}][type]"  value="${escHtml(field.type)}">
                        <div class="me-2 sort-handle"><i class="bi bi-grip-vertical"></i></div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-medium">${escHtml(field.label)}</p>
                            <small class="text-muted">Placeholder: <code>[${escHtml(field.name)}]</code> | Tipe: ${escHtml(field.type)}</small>
                        </div>
                        <i class="bi bi-pencil-square text-secondary edit-btn edit-schema-btn" data-index="${idx}"></i>
                        <i class="bi bi-trash text-danger remove-btn remove-schema-btn" data-index="${idx}"></i>
                    </div>`);
            });
        }
        if (typeof updateDynamicKeywords === 'function') updateDynamicKeywords(formSchema);
    }

    // ── RENDER APPROVAL ──────────────────────────────────────────
    function renderApprovalList() {
        const $c = $('#approval-container');
        $c.empty();
        if (!approvals || !approvals.length) {
            $c.html('<p class="text-muted text-center py-3" id="approval-empty-msg">Belum ada langkah approval.</p>');
        } else {
            approvals.forEach((ap, idx) => {
                const jabNama = masterJabatans[ap.master_jabatan_id] || 'Tidak Dikenal';
                $c.append(`
                    <div class="approval-item d-flex align-items-center mb-2">
                        <input type="hidden" name="approvals[${idx}][master_jabatan_id]" value="${escHtml(ap.master_jabatan_id)}">
                        <input type="hidden" name="approvals[${idx}][scope]"             value="${escHtml(ap.scope)}">
                        <div class="me-2 sort-handle"><i class="bi bi-grip-vertical"></i></div>
                        <div class="me-3"><span class="badge bg-primary rounded-pill">#${idx+1}</span></div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-medium">${escHtml(jabNama)}</p>
                            <small class="text-muted">Scope: ${escHtml(ap.scope)}</small>
                        </div>
                        <i class="bi bi-pencil-square text-secondary edit-btn edit-approval-btn" data-index="${idx}"></i>
                        <i class="bi bi-trash text-danger remove-btn remove-approval-btn" data-index="${idx}"></i>
                    </div>`);
            });
        }
    }

    // ── SCHEMA EVENTS ────────────────────────────────────────────
    $('#addSchemaButton').on('click', () => {
        $('#editingSchemaIndex').val('');
        $('#schemaModalLabel').text('Tambah Field Tambahan');
        $('#schema_label, #schema_name').val('');
        $('#schema_type').val('text');
        schemaModal.show();
    });

    $('#saveSchemaButton').on('click', () => {
        const label = $('#schema_label').val().trim();
        const name  = $('#schema_name').val().trim();
        const type  = $('#schema_type').val();
        const idx   = $('#editingSchemaIndex').val();
        if (!label || !name) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Label dan Nama Kunci wajib diisi.', confirmButtonColor: '#f59e0b' }); return; }
        if (!/^[a-zA-Z0-9_]+$/.test(name)) { Swal.fire({ icon: 'warning', title: 'Format Salah', text: 'Nama Kunci hanya boleh huruf, angka, dan underscore.', confirmButtonColor: '#f59e0b' }); return; }
        const obj = { label, name, type };
        idx !== '' ? formSchema[parseInt(idx)] = obj : formSchema.push(obj);
        renderSchemaList();
        schemaModal.hide();
    });

    $('#schema-container').on('click', '.edit-schema-btn', function () {
        const idx = $(this).data('index');
        const f   = formSchema[idx];
        if (!f) return;
        $('#editingSchemaIndex').val(idx);
        $('#schemaModalLabel').text('Edit Field Tambahan');
        $('#schema_label').val(f.label);
        $('#schema_name').val(f.name);
        $('#schema_type').val(f.type);
        schemaModal.show();
    });

    $('#schema-container').on('click', '.remove-schema-btn', function () {
        const idx   = $(this).data('index');
        const label = formSchema[idx]?.label || 'field ini';
        Swal.fire({
            title: 'Hapus Field?',
            html: `Field <strong>${label}</strong> akan dihapus dari daftar.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'btn btn-danger px-4 rounded-pill fw-bold ms-2',
                cancelButton: 'btn btn-light px-4 rounded-pill fw-bold'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                formSchema.splice(idx, 1);
                renderSchemaList();
            }
        });
    });

    // ── APPROVAL EVENTS ──────────────────────────────────────────
    $('#addApprovalButton').on('click', () => {
        $('#editingApprovalIndex').val('');
        $('#approvalModalLabel').text('Tambah Langkah Approval');
        $('#approval_jabatan').val('');
        $('#approval_scope').val('PRODI');
        approvalModal.show();
    });

    $('#saveApprovalButton').on('click', () => {
        const jabId = $('#approval_jabatan').val();
        const scope = $('#approval_scope').val();
        const idx   = $('#editingApprovalIndex').val();
        if (!jabId) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih jabatan terlebih dahulu.', confirmButtonColor: '#f59e0b' }); return; }
        const obj = { master_jabatan_id: jabId, scope };
        idx !== '' ? approvals[parseInt(idx)] = obj : approvals.push(obj);
        renderApprovalList();
        approvalModal.hide();
    });

    $('#approval-container').on('click', '.edit-approval-btn', function () {
        const idx = $(this).data('index');
        const ap  = approvals[idx];
        if (!ap) return;
        $('#editingApprovalIndex').val(idx);
        $('#approvalModalLabel').text('Edit Langkah Approval');
        $('#approval_jabatan').val(ap.master_jabatan_id);
        $('#approval_scope').val(ap.scope);
        approvalModal.show();
    });

    $('#approval-container').on('click', '.remove-approval-btn', function () {
        const idx  = $(this).data('index');
        const jabN = masterJabatans[approvals[idx]?.master_jabatan_id] || 'langkah ini';
        Swal.fire({
            title: 'Hapus Langkah Approval?',
            html: `Langkah <strong>#${idx+1} – ${jabN}</strong> akan dihapus dari alur persetujuan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'btn btn-danger px-4 rounded-pill fw-bold ms-2',
                cancelButton: 'btn btn-light px-4 rounded-pill fw-bold'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                approvals.splice(idx, 1);
                renderApprovalList();
            }
        });
    });

    // ── UTILITY ──────────────────────────────────────────────────
    function escHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    renderSchemaList();
    renderApprovalList();
});
</script>
@endpush