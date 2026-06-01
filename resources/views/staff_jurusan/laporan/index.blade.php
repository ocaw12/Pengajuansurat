{{-- resources/views/staff_jurusan/laporan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Pengajuan Surat')
@section('page-title', 'Laporan Pengajuan')

@push('styles')
<style>
    /* ── Pakai Plus Jakarta Sans dari layout utama ── */
    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Summary Cards ── */
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); transform: translateY(-1px); }
    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .stat-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #64748b; }
    .stat-value { font-size: 1.9rem; font-weight: 800; letter-spacing: -.04em; line-height: 1; margin-top: .2rem; }

    .stat-total   .stat-icon { background: #fef3c7; color: #d97706; }
    .stat-total   .stat-value { color: #92400e; }
    .stat-proses  .stat-icon { background: #eff6ff; color: #2563eb; }
    .stat-proses  .stat-value { color: #1d4ed8; }
    .stat-selesai .stat-icon { background: #dcfce7; color: #16a34a; }
    .stat-selesai .stat-value { color: #15803d; }
    .stat-ditolak .stat-icon { background: #fee2e2; color: #dc2626; }
    .stat-ditolak .stat-value { color: #b91c1c; }
    .stat-pending .stat-icon { background: #fef3c7; color: #d97706; }
    .stat-pending .stat-value { color: #92400e; }

    /* ── Filter card ── */
    .filter-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
    }
    .filter-card .form-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #64748b;
        margin-bottom: .3rem;
    }
    .filter-card .form-select,
    .filter-card .form-control {
        border-color: #e2e8f0;
        border-radius: 10px;
        font-size: .875rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: .45rem .75rem;
    }
    .filter-card .form-select:focus,
    .filter-card .form-control:focus {
        border-color: #fbbf24;
        box-shadow: 0 0 0 3px rgba(251,191,36,.15);
    }

    /* ── Tombol utama — amber/kuning sesuai sistem ── */
    .btn-amber {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #1a1a1a;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(245,158,11,.3);
    }
    .btn-amber:hover { background: linear-gradient(135deg, #f59e0b, #d97706); color: #1a1a1a; }

    .btn-excel-dl {
        background: #f0fdf4; color: #15803d;
        border: 1px solid #bbf7d0; border-radius: 10px; font-weight: 700;
    }
    .btn-excel-dl:hover { background: #dcfce7; color: #15803d; }

    .btn-pdf-dl {
        background: #fef2f2; color: #b91c1c;
        border: 1px solid #fecaca; border-radius: 10px; font-weight: 700;
    }
    .btn-pdf-dl:hover { background: #fee2e2; color: #b91c1c; }

    /* ── Rekap bar chart ── */
    .rekap-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        height: 100%;
    }
    .rekap-title { font-size: .85rem; font-weight: 700; color: #1e293b; margin-bottom: .125rem; }
    .rekap-kode  { font-size: .7rem; color: #94a3b8; font-weight: 600; }
    .rekap-total { font-size: .7rem; color: #94a3b8; margin-top: .5rem; }
    .bar-row     { display: flex; align-items: center; gap: .5rem; margin-top: .45rem; font-size: .78rem; }
    .bar-label   { width: 100px; flex-shrink: 0; color: #64748b; font-size: .72rem; }
    .bar-track   { flex: 1; background: #f1f5f9; border-radius: 99px; height: 7px; overflow: hidden; }
    .bar-fill    { height: 100%; border-radius: 99px; }
    .bar-fill.selesai { background: #22c55e; }
    .bar-fill.pending { background: #fbbf24; }
    .bar-fill.proses  { background: #60a5fa; }
    .bar-fill.ditolak { background: #f87171; }
    .bar-count   { width: 24px; text-align: right; font-weight: 700; font-size: .75rem; color: #1e293b; }

    /* ── Tabel ── */
    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
    }
    .table-card-head {
        padding: .875rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: .5rem;
        font-size: .875rem; font-weight: 700; color: #1e293b;
    }
    .table-card-head .count-badge {
        margin-left: auto;
        background: #f1f5f9;
        color: #64748b;
        font-size: .72rem;
        font-weight: 700;
        padding: .2rem .6rem;
        border-radius: 99px;
    }
    .laporan-table { width: 100%; border-collapse: collapse; font-size: .815rem; }
    .laporan-table thead th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: .6rem .85rem;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #64748b;
        white-space: nowrap;
    }
    .laporan-table tbody td {
        padding: .7rem .85rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
        color: #1e293b;
    }
    .laporan-table tbody tr:last-child td { border-bottom: none; }
    .laporan-table tbody tr:hover td { background: #fffbeb; }

    /* ── Status badges — sesuai warna sistem ── */
    .status-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .25rem .65rem;
        border-radius: 99px;
        font-size: .7rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .status-badge::before { content:''; width:6px; height:6px; border-radius:50%; background:currentColor; opacity:.7; }
    .sb-selesai        { background: #dcfce7; color: #15803d; }
    .sb-pending        { background: #fef3c7; color: #92400e; }
    .sb-ditolak        { background: #fee2e2; color: #b91c1c; }
    .sb-proses         { background: #eff6ff; color: #1d4ed8; }
    .sb-gagal_generate { background: #fce7f3; color: #9d174d; }

    /* ── Approval chain dot ── */
    .chain-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; margin-right: 4px; }
    .dot-ok    { background: #22c55e; }
    .dot-wait  { background: #fbbf24; }
    .dot-no    { background: #f87171; }

    /* ── Schema list ── */
    .schema-list { list-style: none; padding: 0; margin: 0; }
    .schema-list li { font-size: .75rem; margin-bottom: .2rem; }
    .schema-k { color: #94a3b8; margin-right: .25rem; }
    .schema-v { color: #1e293b; }

    /* ── Empty state ── */
    .empty-state { padding: 3rem 1rem; text-align: center; color: #94a3b8; }

    /* ── Section label ── */
    .section-label {
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        color: #94a3b8; margin-bottom: .75rem;
    }
</style>
@endpush

@section('content')

{{-- ── Page Header ── --}}
<div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h4 class="fw-800 mb-1" style="letter-spacing:-.03em;font-weight:800">Laporan Pengajuan Surat</h4>
        <p class="text-muted mb-0" style="font-size:.875rem">
            {{ Auth::user()->adminStaff->programStudi->nama_prodi ?? 'Program Studi' }}
            &nbsp;·&nbsp;
            {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('staff_jurusan.laporan.exportExcel', request()->query()) }}"
           class="btn btn-excel-dl btn-sm d-flex align-items-center gap-2 px-3 py-2">
            <i class="bi bi-file-earmark-spreadsheet"></i> Excel
        </a>
        <a href="{{ route('staff_jurusan.laporan.exportPdf', request()->query()) }}"
           class="btn btn-pdf-dl btn-sm d-flex align-items-center gap-2 px-3 py-2">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
    </div>
</div>

{{-- ── Summary Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card stat-total">
            <div class="stat-icon"><i class="bi bi-inbox-fill"></i></div>
            <div>
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $summary['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card stat-pending">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $summary['pending'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card stat-selesai">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-label">Selesai</div>
                <div class="stat-value">{{ $summary['selesai'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card stat-ditolak">
            <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
            <div>
                <div class="stat-label">Ditolak</div>
                <div class="stat-value">{{ $summary['ditolak'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Filter ── --}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('staff_jurusan.laporan.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" @selected($m == $bulan)>
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2 col-lg-2">
                <label class="form-label">Tahun</label>
                <input type="number" name="tahun" class="form-control form-control-sm"
                       value="{{ $tahun }}" min="2020" max="{{ now()->year + 1 }}">
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <label class="form-label">Jenis Surat</label>
                <select name="jenis_surat_id" class="form-select form-select-sm">
                    <option value="">— Semua Jenis —</option>
                    @foreach($jenisSurats as $js)
                        <option value="{{ $js->id }}" @selected($jenisSuratId == $js->id)>
                            [{{ $js->kode_surat }}] {{ $js->nama_surat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 col-lg-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">— Semua Status —</option>
                    @foreach($statusOptions as $val => $label)
                        <option value="{{ $val }}" @selected($statusFilter === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-auto">
                <button type="submit" class="btn btn-amber btn-sm w-100 d-flex align-items-center justify-content-center gap-2 px-3">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ── Rekap per Jenis Surat ── --}}
@if($rekapJenisSurat->isNotEmpty())
<div class="mb-4">
    <div class="section-label">Rekap per Jenis Surat</div>
    <div class="row g-3">
        @foreach($rekapJenisSurat as $jsId => $rows)
            @php
                $totalJs  = $rows->sum('total');
                $jsSurat  = $rows->first()->jenisSurat;
                $statuses = $rows->keyBy('status_pengajuan');
                $selesai  = $statuses->get('selesai')?->total ?? 0;
                $pending  = $statuses->get('pending')?->total ?? 0;
                $ditolak  = $statuses->get('ditolak')?->total ?? 0;
                $proses   = $totalJs - $selesai - $pending - $ditolak;
            @endphp
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="rekap-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="rekap-title">{{ $jsSurat->nama_surat ?? '-' }}</div>
                            <div class="rekap-kode">{{ $jsSurat->kode_surat ?? '' }}</div>
                        </div>
                        <span class="badge rounded-pill"
                              style="background:#fef3c7;color:#92400e;font-size:.7rem;font-weight:700">
                            {{ $totalJs }}
                        </span>
                    </div>
                    @foreach([
                        ['Selesai', $selesai,  'selesai'],
                        ['Pending', $pending,  'pending'],
                        ['Proses',  $proses,   'proses'],
                        ['Ditolak', $ditolak,  'ditolak'],
                    ] as [$lbl, $cnt, $cls])
                        @if($cnt > 0)
                        <div class="bar-row">
                            <span class="bar-label">{{ $lbl }}</span>
                            <div class="bar-track">
                                <div class="bar-fill {{ $cls }}"
                                     style="width:{{ $totalJs > 0 ? round($cnt/$totalJs*100) : 0 }}%"></div>
                            </div>
                            <span class="bar-count">{{ $cnt }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- ── Tabel Detail ── --}}
<div class="table-card">
    <div class="table-card-head">
        <i class="bi bi-table" style="color:#d97706"></i>
        Detail Pengajuan
        <span class="count-badge">{{ $pengajuans->total() }} entri</span>
    </div>

    @if($pengajuans->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox" style="font-size:2.5rem;opacity:.2;display:block;margin-bottom:.75rem"></i>
            <p class="mb-0" style="font-size:.875rem">Tidak ada data untuk periode &amp; filter yang dipilih.</p>
        </div>
    @else
    <div class="table-responsive">
        <table class="laporan-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Mahasiswa</th>
                    <th>Jenis Surat</th>
                    <th>Keperluan</th>
                    <th>Data Form</th>
                    <th>Approval</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Nomor Surat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuans as $i => $p)
                @php
                    $statusClass = match($p->status_pengajuan) {
                        'selesai', 'sudah_diambil' => 'sb-selesai',
                        'pending'                  => 'sb-pending',
                        'ditolak'                  => 'sb-ditolak',
                        'gagal_generate'           => 'sb-gagal_generate',
                        default                    => 'sb-proses',
                    };
                @endphp
                <tr>
                    <td style="color:#94a3b8;font-size:.75rem">{{ $pengajuans->firstItem() + $i }}</td>

                    <td style="white-space:nowrap">
                        <div style="font-weight:600;font-size:.8rem">
                            {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d/m/Y') }}
                        </div>
                        <div style="color:#94a3b8;font-size:.7rem">
                            {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('H:i') }}
                        </div>
                    </td>

                    <td>
                        <div style="font-weight:700;font-size:.82rem">{{ $p->mahasiswa->nama_lengkap ?? '-' }}</div>
                        <div style="color:#94a3b8;font-size:.72rem;font-family:monospace">{{ $p->mahasiswa->nim ?? '-' }}</div>
                    </td>

                    <td>
                        <div style="font-weight:600;font-size:.8rem">{{ $p->jenisSurat->nama_surat ?? '-' }}</div>
                        <div style="color:#94a3b8;font-size:.7rem">{{ $p->jenisSurat->kode_surat ?? '' }}</div>
                    </td>

                    <td style="max-width:180px">
                        <div style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;font-size:.8rem;color:#374151">
                            {{ $p->keperluan }}
                        </div>
                    </td>

                    <td style="min-width:170px">
                        @if(is_array($p->data_pendukung) && count($p->data_pendukung))
                            <ul class="schema-list">
                                @foreach($p->data_pendukung as $key => $val)
                                    <li>
                                        <span class="schema-k">{{ str_replace('_', ' ', $key) }}:</span>
                                        <span class="schema-v">
                                            @if(is_string($val) && Str::startsWith($val, 'dokumen_pengajuan/'))
                                                <a href="{{ asset('storage/' . $val) }}" target="_blank"
                                                   style="color:#d97706;font-weight:600;text-decoration:none">
                                                    <i class="bi bi-paperclip"></i> Lihat
                                                </a>
                                            @else
                                                {{ is_array($val) ? implode(', ', $val) : $val }}
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span style="color:#cbd5e1;font-size:.75rem">—</span>
                        @endif
                    </td>

                    <td style="min-width:150px">
                        @if($p->approvalPejabats->isNotEmpty())
                            @foreach($p->approvalPejabats->sortBy('urutan_approval') as $ap)
                                <div style="display:flex;align-items:center;gap:.3rem;font-size:.75rem;margin-bottom:.2rem">
                                    <span class="chain-dot
                                        {{ $ap->status_approval === 'disetujui' ? 'dot-ok' : ($ap->status_approval === 'ditolak' ? 'dot-no' : 'dot-wait') }}
                                    "></span>
                                    <span style="color:#475569">{{ $ap->pejabat->masterJabatan->nama_jabatan ?? '-' }}</span>
                                    @if($ap->tanggal_approval)
                                        <span style="color:#94a3b8;font-size:.68rem">
                                            · {{ \Carbon\Carbon::parse($ap->tanggal_approval)->format('d/m') }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <span style="color:#cbd5e1;font-size:.75rem">Belum ada</span>
                        @endif
                    </td>

                    <td>
                        <span style="font-size:.78rem;text-transform:capitalize;color:#475569">
                            {{ $p->metode_pengambilan }}
                        </span>
                    </td>

                    <td>
                        <span class="status-badge {{ $statusClass }}">
                            {{ str_replace('_', ' ', $p->status_pengajuan) }}
                        </span>
                        @if($p->catatan_admin)
                            <div style="font-size:.68rem;color:#94a3b8;margin-top:.3rem;max-width:120px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">
                                {{ $p->catatan_admin }}
                            </div>
                        @endif
                    </td>

                    <td style="white-space:nowrap;font-size:.75rem;font-family:monospace;color:#374151">
                        {{ $p->nomor_surat ?? '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-3 py-3 border-top" style="border-color:#f1f5f9!important">
        {{ $pengajuans->links() }}
    </div>
    @endif
</div>

@endsection