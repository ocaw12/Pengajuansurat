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

    .detail-muted {
        color: #6b7280;
        font-size: 0.85rem;
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

        {{-- CARD DATA PENGAJUAN SURAT --}}
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
                    {{ $pengajuanSurat->mahasiswa->nama_lengkap ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">NPM</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pengajuanSurat->mahasiswa->nim ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Jurusan</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pengajuanSurat->mahasiswa->programStudi->nama_prodi ?? '-' }}
                </div>
            </div>

            {{-- DATA SURAT --}}
            <div class="detail-row">
                <div class="detail-label">Judul Surat</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pengajuanSurat->jenisSurat->nama_surat ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">ID Pengajuan</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pengajuanSurat->nomor_surat ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tanggal Pengajuan</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ optional($pengajuanSurat->tanggal_pengajuan)->format('d-m-Y') ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status Pengajuan</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    <span class="status-badge">
                        <span class="status-dot"></span>
                        {{ $pengajuanSurat->status_pengajuan ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- CARD DATA APPROVAL --}}
        <div class="card-verifikasi">
            <div class="card-verifikasi-header">
                <h3 class="card-verifikasi-title">Data Approval Pejabat</h3>
                <span class="card-verifikasi-pill">Approval</span>
            </div>

            <hr class="verifikasi-divider">

            <div class="detail-row">
                <div class="detail-label">Nama Pejabat</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pejabat->nama_lengkap ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Jabatan</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pejabat->masterJabatan->nama_jabatan ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">NIP / NIDN</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $pejabat->nip_atau_nidn ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status Approval</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    <span class="status-badge status-pending">
                        <span class="status-dot"></span>
                        {{ $approval->status_approval ?? '-' }}
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tanggal Approval</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ optional($approval->tanggal_approval)->format('d-m-Y H:i:s') ?? '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Catatan</div>
                <div class="detail-separator">:</div>
                <div class="detail-value">
                    {{ $approval->catatan_pejabat ?? 'Tidak ada catatan.' }}
                </div>
            </div>
        </div>

    </div>

    <div class="verifikasi-footer-note">
        Sistem secara otomatis menyesuaikan status berdasarkan hasil scan tanda tangan digital.
    </div>

</div>
@endsection
