@extends('layouts.app')

@section('title', 'Buat Pengajuan Baru')
@section('page-title', 'Formulir Pengajuan Surat Baru')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST">
                    @csrf
                    
                    <!-- 1. Jenis Surat (Pemicu) -->
                    <div class="mb-3">
                        <label for="jenis_surat_id" class="form-label fw-bold">Pilih Jenis Surat <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_surat_id" name="jenis_surat_id" required>
                            <option value="" selected disabled>-- Pilih salah satu --</option>
                            @foreach($jenis_surats as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_surat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Keperluan Standar -->
                    <div class="mb-3">
                        <label for="keperluan" class="form-label fw-bold">Jelaskan Keperluan Anda <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="keperluan" name="keperluan" rows="3" placeholder="Contoh: Untuk syarat mendaftar beasiswa..." required></textarea>
                    </div>

                    <!-- 3. Metode Pengambilan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Metode Pengambilan <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode_pengambilan" id="metode_digital" value="digital" checked>
                            <label class="form-check-label" for="metode_digital">
                                Digital (Download PDF)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode_pengambilan" id="metode_cetak" value="cetak">
                            <label class="form-check-label" for="metode_cetak">
                                Cetak (Ambil di Ruang Staff Jurusan)
                            </label>
                        </div>
                    </div>

                    <!-- 4. Kontainer untuk Form Dinamis -->
                    <hr>
                    <div id="form-dinamis-container" class="mb-3">
                        <!-- Field tambahan akan muncul di sini -->
                    </div>

                    <!-- 5. Tombol Submit -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send me-2"></i> Ajukan Surat
                        </button>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">Gagal Mengajukan!</h5>
                            <p>Pastikan semua data terisi dengan benar:</p>
                            <hr>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="alert alert-primary" role="alert">
            <h4 class="alert-heading"><i class="bi bi-info-circle-fill"></i> Perhatian!</h4>
            <p>Pastikan semua data yang Anda masukkan sudah benar sebelum diajukan.</p>
            <hr>
            <p class="mb-0">Data yang tidak valid atau salah akan memperlambat proses validasi dan approval.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Script untuk form dinamis -->
<script>
    document.getElementById('jenis_surat_id').addEventListener('change', function() {
        const jenisSuratId = this.value;
        const container = document.getElementById('form-dinamis-container');
        
        // Hapus field lama
        container.innerHTML = '<p class="text-muted fst-italic">Memuat field tambahan...</p>';

        if (!jenisSuratId) {
            container.innerHTML = '';
            return;
        }

        // Ambil skema form dari server
        // Pastikan Anda membuat rute ini di web.php:
        // Route::get('/api/form-schema/{jenis_surat}', [PengajuanController::class, 'getFormSchema'])->name('api.form-schema');
        
        // Ganti URL ini agar sesuai dengan rute Anda
        const url = `/mahasiswa/api/form-schema/${jenisSuratId}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(schema => {
                container.innerHTML = ''; // Bersihkan lagi
                
                if (schema && Array.isArray(schema) && schema.length > 0) {
                    schema.forEach(field => {
                        let fieldHtml = `<div class="mb-3">
                            <label for="dinamis_${field.name}" class="form-label">${field.label} <span class="text-danger">*</span></label>`;
                        
                        if(field.type === 'textarea') {
                             fieldHtml += `<textarea class="form-control" id="dinamis_${field.name}" name="data_pendukung[${field.name}]" required></textarea>`;
                        } else {
                             fieldHtml += `<input type="${field.type}" class="form-control" id="dinamis_${field.name}" name="data_pendukung[${field.name}]" required>`;
                        }
                        
                        fieldHtml += `</div>`;
                        container.innerHTML += fieldHtml;
                    });
                } else {
                    container.innerHTML = '<p class="text-muted fst-italic">Tidak ada data tambahan untuk surat ini.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching dynamic form:', error);
                container.innerHTML = '<div class="alert alert-danger">Gagal memuat field tambahan. Pastikan rute API sudah benar.</div>';
            });
    });
</script>
@endpush

