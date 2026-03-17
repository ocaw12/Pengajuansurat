@extends('layouts.app')

@section('content')
<style>
    .page-verifikasi-wrapper {
        max-width: 960px;
        margin: 0 auto;
        padding: 24px 16px 40px;
    }

    .page-verifikasi-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .page-verifikasi-icon {
        width: 52px;
        height: 52px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: #ffffff;
        font-weight: 700;
        font-size: 24px;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35);
    }

    .page-verifikasi-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
    }

    .page-verifikasi-subtitle {
        margin: 2px 0 0;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .verifikasi-grid {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 20px;
        align-items: start; /* Mencegah card kiri memanjang mengikuti kolom kanan */
    }

    @media (max-width: 768px) {
        .verifikasi-grid {
            grid-template-columns: 1fr;
        }
    }

    .card-verifikasi {
        background: #ffffff;
        border-radius: 18px;
        padding: 18px 20px 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        border: 1px solid #e5e7eb;
    }

    .card-verifikasi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .card-verifikasi-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
    }

    .card-verifikasi-pill {
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 500;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .verifikasi-divider {
        border: none;
        border-top: 1px dashed #e5e7eb;
        margin: 10px 0 16px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 38% 4% 58%;
        font-size: 0.9rem;
        margin-bottom: 6px;
    }

    .detail-label {
        font-weight: 500;
        color: #4b5563;
    }

    .detail-separator {
        color: #9ca3af;
    }

    .detail-value {
        color: #111827;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #ecfdf5;
        color: #15803d;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        margin-right: 6px;
        background: #22c55e;
    }

    .status-pending {
        background: #fffbeb;
        color: #92400e;
    }
    .status-pending .status-dot {
        background: #f59e0b;
    }

    .status-rejected {
        background: #fef2f2;
        color: #b91c1c;
    }
    .status-rejected .status-dot {
        background: #ef4444;
    }

    .verifikasi-footer-note {
        margin-top: 24px;
        text-align: center;
        font-size: 0.85rem;
        color: #6b7280;
    }

    /* Pembungkus jika ada banyak approval */
    .approval-list-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
</style>

<div class="page-verifikasi-wrapper">

    {{-- HEADER --}}
    <div class="page-verifikasi-header">
        <div class="page-verifikasi-icon">✓</div>
        <div>
            <h1 class="page-verifikasi-title">Verifikasi Pengajuan Surat</h1>
            <p class="page-verifikasi-subtitle">
                Hasil verifikasi keaslian pengajuan surat berdasarkan pemindaian tanda tangan.
            </p>
        </div>
    </div>

    <div class="verifikasi-grid">

        {{-- KOLOM KIRI: CARD DATA PENGAJUAN SURAT --}}
        <div class="card-verifikasi">
            <div class="card-verifikasi-header">
                <h3 class="card-verifikasi-title">Data Pengajuan Surat</h3>
                <span class="card-verifikasi-pill">Pengajuan</span>
            </div>

            <hr class="verifikasi-divider">

            {{-- DATA MAHASISWA --}}
            <div class="detail-row">
                <div class="detail-label">Nama Mahasiswa</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pengajuan->mahasiswa->nama_lengkap ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">NPM</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pengajuan->mahasiswa->nim ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Jurusan</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pengajuan->mahasiswa->programStudi->nama_prodi ?? '-' }}
                </div>
            </div>

            {{-- DATA SURAT --}}
            <div class="detail-row mt-4">
                <div class="detail-label">Judul Surat</div>
                <div class="detail-separator">:</div>
                <div class="detail-value font-semibold">
                    {{ $pengajuan->jenisSurat->nama_surat ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">ID / No Surat</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pengajuan->nomor_surat ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tgl Pengajuan</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ optional($pengajuan->created_at)->format('d-m-Y') ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status Akhir</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    <span class="status-badge {{ $pengajuan->status_pengajuan == 'selesai' ? '' : 'status-pending' }}">
                        <span class="status-dot"></span>
                        {{ strtoupper(str_replace('_', ' ', $pengajuan->status_pengajuan ?? '-')) }}
                    </span>
                </div>
            </div>
        </div>


        {{-- KOLOM KANAN: LIST DATA APPROVAL PEJABAT --}}
        <div class="approval-list-wrapper">
            @if($pengajuan->approvalPejabats && count($pengajuan->approvalPejabats) > 0)
                @foreach($pengajuan->approvalPejabats->sortBy('urutan_approval') as $index => $approval)
                    <div class="card-verifikasi">
                        <div class="card-verifikasi-header">
                            <h3 class="card-verifikasi-title">Persetujuan #{{ $index + 1 }}</h3>
                            <span class="card-verifikasi-pill">Approval Pejabat</span>
                        </div>

                        <hr class="verifikasi-divider">

                        <div class="detail-row">
                            <div class="detail-label">Nama Pejabat</div>
                            <div class="detail-separator">:</div>
                            <div class="detail-value font-medium">
                                {{ $approval->pejabat->nama_lengkap ?? '-' }}
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Jabatan</div>
                            <div class="detail-separator">:</div>
                            <div class="detail-value">
                                {{ $approval->pejabat->masterJabatan->nama_jabatan ?? '-' }}
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">NIP / NIDN</div>
                            <div class="detail-separator">:</div>
                            <div class="detail-value">
                                {{ $approval->pejabat->nip_atau_nidn ?? '-' }}
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-separator">:</div>
                            <div class="detail-value">
                                @php
                                    $statusClass = '';
                                    if(strtolower($approval->status_approval) == 'ditolak') {
                                        $statusClass = 'status-rejected';
                                    } elseif(strtolower($approval->status_approval) != 'disetujui') {
                                        $statusClass = 'status-pending';
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    <span class="status-dot"></span>
                                    {{ ucfirst($approval->status_approval ?? 'Menunggu') }}
                                </span>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Tanggal</div>
                            <div class="detail-separator">:</div>
                            <div class="detail-value">
                                @if($approval->status_approval == 'disetujui' || $approval->status_approval == 'ditolak')
                                    {{ optional($approval->updated_at)->format('d-m-Y H:i:s') ?? '-' }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Catatan</div>
                            <div class="detail-separator">:</div>
                            <div class="detail-value text-gray-500 italic">
                                {{ $approval->catatan_pejabat ?? 'Tidak ada catatan.' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card-verifikasi text-center text-gray-500 py-8">
                    Belum ada data approval untuk surat ini.
                </div>
            @endif
        </div>

    </div>

    <div class="verifikasi-footer-note">
        Sistem secara otomatis menyesuaikan status berdasarkan hasil scan tanda tangan digital.
    </div>

</div>
@endsection