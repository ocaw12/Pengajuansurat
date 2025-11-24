@extends('layouts.app')

@section('title', 'Edit Jenis Surat')
@section('page-title', 'Edit Jenis Surat')

@push('styles')
<style>
    .schema-item, .approval-item {
        border: 1px solid #dee2e6;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-radius: 0.375rem;
        background-color: #f8f9fa;
        position: relative;
    }
    .remove-btn { position: absolute; top: 0.5rem; right: 0.5rem; cursor: pointer; }
    .edit-btn { position: absolute; top: 0.5rem; right: 2.8rem; cursor: pointer; }
    .sort-handle { cursor: grab; color: #6c757d; }
</style>
@endpush

@section('content')
{{-- Error validasi --}}
@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal Menyimpan!</h5>
        <p>Terdapat kesalahan pada input Anda:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Form Utama --}}
<form action="{{ route('admin_akademik.jenis-surat.update', $jenisSurat->id) }}" method="POST" id="jenisSuratForm">
    @csrf
    @method('PUT')

    <div class="row">
        {{-- Kolom Kiri --}}
        <div class="col-lg-8 mb-4">
            {{-- Detail Surat --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0 card-title">
                        <i class="bi bi-file-earmark-text me-2"></i>Detail Surat
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Nama Surat --}}
                    <div class="mb-3">
                        <label for="nama_surat" class="form-label fw-bold">
                            Nama Surat <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control @error('nama_surat') is-invalid @enderror"
                            id="nama_surat"
                            name="nama_surat"
                            value="{{ old('nama_surat', $jenisSurat->nama_surat) }}"
                            required
                            placeholder="Contoh: Surat Keterangan Aktif Kuliah"
                        >
                        @error('nama_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        {{-- Kode Surat --}}
                        <div class="col-md-6 mb-3">
                            <label for="kode_surat" class="form-label fw-bold">
                                Kode Surat <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control @error('kode_surat') is-invalid @enderror"
                                id="kode_surat"
                                name="kode_surat"
                                value="{{ old('kode_surat', $jenisSurat->kode_surat) }}"
                                required
                                placeholder="Contoh: SK, SP, SR"
                            >
                            <div class="form-text">Kode unik ini akan muncul di nomor surat.</div>
                            @error('kode_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label fw-bold">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-select @error('kategori') is-invalid @enderror"
                                id="kategori"
                                name="kategori"
                                required
                            >
                                @foreach($kategoriOptions as $kategori)
                                    <option
                                        value="{{ $kategori }}"
                                        {{ old('kategori', $jenisSurat->kategori) == $kategori ? 'selected' : '' }}
                                    >
                                        {{ $kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Format Penomoran --}}
                    <div class="mb-3">
                        <label for="format_penomoran" class="form-label fw-bold">
                            Format Penomoran <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control @error('format_penomoran') is-invalid @enderror"
                            id="format_penomoran"
                            name="format_penomoran"
                            value="{{ old('format_penomoran', $jenisSurat->format_penomoran) }}"
                            required
                        >
                        <div class="form-text">
                            Gunakan placeholder:
                            <code>{nomor_urut}</code>,
                            <code>{kode_surat}</code>,
                            <code>{kode_unit}</code>,
                            <code>{bulan_romawi}</code>,
                            <code>{tahun}</code>.
                        </div>
                        @error('format_penomoran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Isi Template Surat --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0 card-title">
                        <i class="bi bi-textarea-t me-2"></i>Isi Naskah Surat (Template)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info small p-2">
                        <p class="mb-1">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            <strong>Penting:</strong> Gunakan textarea polos, bukan Rich Text Editor!
                        </p>
                        <ul class="mb-0 ps-3">
                            <li>Gunakan placeholder standar: <code>[nama_mahasiswa]</code>, <code>[nim]</code>, <code>[prodi]</code>, <code>[fakultas]</code>, <code>[angkatan]</code>, <code>[keperluan]</code>, <code>[tanggal_sekarang]</code>.</li>
                            <li>Jika menambah "Field Tambahan", gunakan <strong>Nama Kunci</strong> sebagai placeholder (<code>[nama_kunci]</code>).</li>
                            <li>Tekan Enter untuk paragraf baru (otomatis diformat di PDF).</li>
                        </ul>
                    </div>
                    <textarea
                        class="form-control @error('isi_template') is-invalid @enderror"
                        id="isi_template"
                        name="isi_template"
                        rows="15"
                        required
                        placeholder="Tulis naskah surat di sini..."
                    >{{ old('isi_template', $jenisSurat->isi_template) }}</textarea>
                    @error('isi_template') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Field Tambahan (Form Schema) --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0 card-title">
                        <i class="bi bi-ui-checks-grid me-2"></i>Field Tambahan untuk Mahasiswa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning small p-2">
                        <p class="mb-1">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            Tambahkan field di sini <strong>hanya jika</strong> surat ini memerlukan data spesifik (selain "Keperluan").
                        </p>
                        <p class="mb-0">
                            Pastikan <strong>Nama Kunci</strong> unik dan sama persis dengan placeholder di "Isi Naskah Surat".
                        </p>
                    </div>

                    <div id="schema-container">
                        <p class="text-muted text-center py-3" id="schema-empty-msg">Belum ada field tambahan.</p>
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="addSchemaButton">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Field Tambahan
                    </button>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Alur Approval --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0 card-title">
                        <i class="bi bi-signpost-split me-2"></i>Alur Persetujuan (Approval)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info small p-2">
                        <p class="mb-1">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            Tentukan pejabat yang harus menyetujui, <strong>secara berurutan</strong>.
                        </p>
                        <p class="mb-0">Minimal harus ada 1 langkah.</p>
                    </div>

                    @error('approvals')
                        <div class="alert alert-danger small py-1 px-2 mb-2">{{ $message }}</div>
                    @enderror
                    @error('approvals.*')
                        <div class="alert alert-danger small py-1 px-2 mb-2">{{ $message }}</div>
                    @enderror

                    <div id="approval-container">
                        <p class="text-muted text-center py-3" id="approval-empty-msg">Belum ada langkah approval.</p>
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="addApprovalButton">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Langkah Approval
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Update Jenis Surat
        </button>
        <a href="{{ route('admin_akademik.jenis-surat.index') }}" class="btn btn-outline-secondary px-4 ms-2">
            Batal
        </a>
    </div>
</form>

{{-- Modal Schema --}}
<div class="modal fade" id="schemaModal" tabindex="-1" aria-labelledby="schemaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="schemaModalLabel">Tambah Field Tambahan Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" id="editingSchemaIndex">
          <div class="mb-3">
              <label for="schema_label" class="form-label">Label Field <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="schema_label" placeholder="Contoh: Judul Penelitian" required>
          </div>
          <div class="mb-3">
              <label for="schema_name" class="form-label">Nama Kunci (Placeholder) <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="schema_name" placeholder="Contoh: judul_penelitian" required pattern="^[a-zA-Z0-9_]+$">
              <div class="form-text">Hanya huruf, angka, underscore. Gunakan: <code>[nama_kunci]</code>.</div>
          </div>
          <div class="mb-3">
              <label for="schema_type" class="form-label">Tipe Field <span class="text-danger">*</span></label>
              <select class="form-select" id="schema_type" required>
                  <option value="text" selected>Teks Singkat (Input)</option>
                  <option value="textarea">Teks Panjang (Textarea)</option>
                  <option value="date">Tanggal (Date)</option>
                  <option value="number">Angka (Number)</option>
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

{{-- Modal Approval --}}
<div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="approvalModalLabel">Tambah Langkah Approval Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" id="editingApprovalIndex">
          <div class="mb-3">
              <label for="approval_jabatan" class="form-label">Jabatan Pejabat <span class="text-danger">*</span></label>
              <select class="form-select" id="approval_jabatan" required>
                   <option value="" disabled selected>-- Pilih Jabatan --</option>
                   @foreach($masterJabatans as $jabatan)
                      <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                   @endforeach
              </select>
          </div>
          <div class="mb-3">
              <label for="approval_scope" class="form-label">Scope Wewenang <span class="text-danger">*</span></label>
              <select class="form-select" id="approval_scope" required>
                  <option value="PRODI">PRODI (berdasarkan prodi mahasiswa)</option>
                  <option value="FAKULTAS">FAKULTAS (berdasarkan fakultas mahasiswa)</option>
                  <option value="UNIVERSITAS">UNIVERSITAS (berlaku umum)</option>
              </select>
              <div class="form-text">Pilih scope yang sesuai jabatan.</div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveApprovalButton">Simpan Langkah</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // --- DATA DARI SERVER ---
        let formSchema = @json(old(
            'form_schema',
            $jenisSurat->form_schema ?? []
        ));

        let approvals = @json(old(
            'approvals',
            $approvalData ?? []
        ));

        const masterJabatans = @json($masterJabatans->pluck('nama_jabatan', 'id'));

        const schemaModal   = new bootstrap.Modal(document.getElementById('schemaModal'));
        const approvalModal = new bootstrap.Modal(document.getElementById('approvalModal'));

        // =========================
        // RENDER LIST SCHEMA
        // =========================
        function renderSchemaList() {
            const $container = $('#schema-container');
            $container.empty();

            if (!formSchema || formSchema.length === 0) {
                $container.html('<p class="text-muted text-center py-3" id="schema-empty-msg">Belum ada field tambahan.</p>');
            } else {
                $.each(formSchema, function(index, field) {
                    const itemHtml = `
                        <div class="schema-item d-flex align-items-center mb-2">
                            <input type="hidden" name="form_schema[${index}][label]" value="${escapeHtml(field.label)}">
                            <input type="hidden" name="form_schema[${index}][name]" value="${escapeHtml(field.name)}">
                            <input type="hidden" name="form_schema[${index}][type]" value="${escapeHtml(field.type)}">
                            <div class="me-2 sort-handle"><i class="bi bi-grip-vertical"></i></div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-medium">${escapeHtml(field.label)}</p>
                                <small class="text-muted d-block">
                                    Placeholder: <code>[${escapeHtml(field.name)}]</code> |
                                    Tipe: <span>${escapeHtml(field.type)}</span>
                                </small>
                            </div>
                            <i class="bi bi-pencil-square text-secondary edit-btn edit-schema-btn" data-index="${index}" title="Edit Field"></i>
                            <i class="bi bi-trash text-danger remove-btn remove-schema-btn" data-index="${index}" title="Hapus Field"></i>
                        </div>
                    `;
                    $container.append(itemHtml);
                });
            }
        }

        // =========================
        // RENDER LIST APPROVAL
        // =========================
        function renderApprovalList() {
            const $container = $('#approval-container');
            $container.empty();

            if (!approvals || approvals.length === 0) {
                $container.html('<p class="text-muted text-center py-3" id="approval-empty-msg">Belum ada langkah approval.</p>');
            } else {
                $.each(approvals, function(index, approval) {
                    const jabatanName = masterJabatans[approval.master_jabatan_id] || 'Tidak Dikenal';
                    const itemHtml = `
                        <div class="approval-item d-flex align-items-center mb-2">
                            <input type="hidden" name="approvals[${index}][master_jabatan_id]" value="${escapeHtml(approval.master_jabatan_id)}">
                            <input type="hidden" name="approvals[${index}][scope]" value="${escapeHtml(approval.scope)}">
                            <div class="me-2 sort-handle"><i class="bi bi-grip-vertical"></i></div>
                            <div class="me-3">
                                <span class="badge bg-primary rounded-pill">#${index + 1}</span>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-medium">${escapeHtml(jabatanName)}</p>
                                <small class="text-muted d-block">Scope: ${escapeHtml(approval.scope)}</small>
                            </div>
                            <i class="bi bi-pencil-square text-secondary edit-btn edit-approval-btn" data-index="${index}" title="Edit Langkah"></i>
                            <i class="bi bi-trash text-danger remove-btn remove-approval-btn" data-index="${index}" title="Hapus Langkah"></i>
                        </div>
                    `;
                    $container.append(itemHtml);
                });
            }
        }

        // =========================
        // EVENT: Tambah Schema
        // =========================
        $('#addSchemaButton').on('click', function() {
            $('#editingSchemaIndex').val('');
            $('#schemaModalLabel').text('Tambah Field Tambahan Baru');
            $('#schema_label').val('').focus();
            $('#schema_name').val('');
            $('#schema_type').val('text');
            schemaModal.show();
        });

        // EVENT: Simpan Schema
        $('#saveSchemaButton').on('click', function() {
            const label = $('#schema_label').val().trim();
            const name  = $('#schema_name').val().trim();
            const type  = $('#schema_type').val();
            const editingIndex = $('#editingSchemaIndex').val();

            if (!label || !name || !type) {
                alert('Semua field wajib diisi.');
                return;
            }

            if (!/^[a-zA-Z0-9_]+$/.test(name)) {
                alert('Nama Kunci hanya boleh huruf, angka, dan underscore.');
                return;
            }

            const newField = { label, name, type };

            if (editingIndex !== '') {
                formSchema[parseInt(editingIndex)] = newField;
            } else {
                formSchema.push(newField);
            }

            renderSchemaList();
            schemaModal.hide();
        });

        // EVENT: Edit Schema
        $('#schema-container').on('click', '.edit-schema-btn', function() {
            const index = $(this).data('index');
            const field = formSchema[index];
            if (field) {
                $('#editingSchemaIndex').val(index);
                $('#schemaModalLabel').text('Edit Field Tambahan');
                $('#schema_label').val(field.label).focus();
                $('#schema_name').val(field.name);
                $('#schema_type').val(field.type);
                schemaModal.show();
            }
        });

        // EVENT: Hapus Schema
        $('#schema-container').on('click', '.remove-schema-btn', function() {
            const index = $(this).data('index');
            const fieldLabel = formSchema[index] ? formSchema[index].label : 'ini';
            if (confirm(`Yakin ingin menghapus field "${fieldLabel}"?`)) {
                formSchema.splice(index, 1);
                renderSchemaList();
            }
        });

        // =========================
        // EVENT: Tambah Approval
        // =========================
        $('#addApprovalButton').on('click', function() {
            $('#editingApprovalIndex').val('');
            $('#approvalModalLabel').text('Tambah Langkah Approval Baru');
            $('#approval_jabatan').val('').focus();
            $('#approval_scope').val('PRODI');
            approvalModal.show();
        });

        // EVENT: Simpan Approval
        $('#saveApprovalButton').on('click', function() {
            const jabatanId    = $('#approval_jabatan').val();
            const scope        = $('#approval_scope').val();
            const editingIndex = $('#editingApprovalIndex').val();

            if (!jabatanId || !scope) {
                alert('Jabatan dan Scope wajib dipilih.');
                return;
            }

            const newApproval = {
                master_jabatan_id: jabatanId,
                scope: scope
            };

            if (editingIndex !== '') {
                approvals[parseInt(editingIndex)] = newApproval;
            } else {
                approvals.push(newApproval);
            }

            renderApprovalList();
            approvalModal.hide();
        });

        // EVENT: Edit Approval
        $('#approval-container').on('click', '.edit-approval-btn', function() {
            const index    = $(this).data('index');
            const approval = approvals[index];
            if (approval) {
                $('#editingApprovalIndex').val(index);
                $('#approvalModalLabel').text('Edit Langkah Approval');
                $('#approval_jabatan').val(approval.master_jabatan_id).focus();
                $('#approval_scope').val(approval.scope);
                approvalModal.show();
            }
        });

        // EVENT: Hapus Approval
        $('#approval-container').on('click', '.remove-approval-btn', function() {
            const index = $(this).data('index');
            const approvalItem = approvals[index];
            const jabatanName = approvalItem ? (masterJabatans[approvalItem.master_jabatan_id] || 'ini') : 'ini';

            if (confirm(`Yakin ingin menghapus langkah #${index + 1} (${jabatanName})?`)) {
                approvals.splice(index, 1);
                renderApprovalList();
            }
        });

        // =========================
        // Utility escapeHtml
        // =========================
        function escapeHtml(unsafe) {
            if (unsafe === null || typeof unsafe === 'undefined') {
                return '';
            }
            return String(unsafe)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Render awal
        renderSchemaList();
        renderApprovalList();
    });
</script>
@endpush
