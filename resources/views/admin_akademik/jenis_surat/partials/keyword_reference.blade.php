{{--
    Partial: resources/views/admin_akademik/jenis_surat/partials/keyword_reference.blade.php

    Variabel yang dibutuhkan dari controller:
    - $tahunAjaranAktif  (model TahunAjaran|null)
    - $semesterAktif     (model Semester|null)
--}}

@php
    // Status badge tahun ajaran & semester
    $taNama  = $tahunAjaranAktif?->tahun ?? null;
    $semNama = $semesterAktif?->semester ?? null;
    $semLengkap = $semesterAktif
        ? ucfirst(strtolower($semesterAktif->semester)) . ' ' . ($taNama ?? '')
        : null;

    $keywords = [
        'Data Mahasiswa' => [
            ['kw' => '[nama_mahasiswa]',   'desc' => 'Nama lengkap mahasiswa'],
            ['kw' => '[nim]',              'desc' => 'Nomor Induk Mahasiswa'],
            ['kw' => '[prodi]',            'desc' => 'Nama program studi'],
            ['kw' => '[fakultas]',         'desc' => 'Nama fakultas'],
            ['kw' => '[angkatan]',         'desc' => 'Tahun angkatan masuk'],
            ['kw' => '[tempat_lahir]',     'desc' => 'Tempat lahir mahasiswa'],
            ['kw' => '[tanggal_lahir]',    'desc' => 'Tanggal lahir (format: 1 Januari 2000)'],
            ['kw' => '[jenis_kelamin]',    'desc' => 'Jenis kelamin mahasiswa'],
            ['kw' => '[alamat]',           'desc' => 'Alamat lengkap mahasiswa'],
        ],
        'Akademik & Semester' => [
            ['kw' => '[tahun_ajaran]',     'desc' => 'Tahun ajaran aktif', 'preview' => $taNama],
            ['kw' => '[semester]',         'desc' => 'Semester aktif (Ganjil/Genap)', 'preview' => $semNama ? ucfirst(strtolower($semNama)) : null],
            ['kw' => '[semester_lengkap]', 'desc' => 'Semester + tahun ajaran', 'preview' => $semLengkap],
            ['kw' => '[semester_angka]',   'desc' => 'Semester ke-berapa mahasiswa (dihitung dari angkatan)'],
        ],
        'Surat & Tanggal' => [
            ['kw' => '[nomor_surat]',      'desc' => 'Nomor surat yang digenerate otomatis'],
            ['kw' => '[keperluan]',        'desc' => 'Keperluan yang diisi mahasiswa saat pengajuan'],
            ['kw' => '[tanggal_sekarang]', 'desc' => 'Tanggal surat diterbitkan (format: 7 Mei 2026)'],
            ['kw' => '[bulan_sekarang]',   'desc' => 'Nama bulan saat surat diterbitkan'],
            ['kw' => '[tahun_sekarang]',   'desc' => 'Tahun saat surat diterbitkan'],
        ],
    ];
@endphp

<div class="kw-panel" id="kwPanel">

    {{-- ── Status Akademik Aktif ── --}}
    <div class="kw-status-bar">
        <div class="kw-status-item">
            <i class="bi bi-calendar-range"></i>
            <span class="kw-status-label">Tahun Ajaran</span>
            @if($taNama)
                <span class="kw-badge kw-badge-green">{{ $taNama }}</span>
            @else
                <span class="kw-badge kw-badge-red">Belum diset</span>
            @endif
        </div>
        <div class="kw-status-sep">|</div>
        <div class="kw-status-item">
            <i class="bi bi-calendar-week"></i>
            <span class="kw-status-label">Semester</span>
            @if($semNama)
                <span class="kw-badge kw-badge-blue">{{ ucfirst(strtolower($semNama)) }}</span>
            @else
                <span class="kw-badge kw-badge-red">Belum diset</span>
            @endif
        </div>
        @if(!$taNama || !$semNama)
        <a href="{{ route('admin_akademik.tahun-ajaran.index') }}"
           class="kw-warning-link ms-auto" target="_blank">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>Atur Tahun Ajaran
        </a>
        @endif
    </div>

    {{-- ── Header Panel ── --}}
    <div class="kw-panel-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-braces-asterisk text-warning fs-5"></i>
            <span class="kw-panel-title">Daftar Keyword / Placeholder</span>
        </div>
        <button type="button" class="kw-toggle-btn" id="kwToggleBtn" onclick="toggleKwPanel()">
            <i class="bi bi-chevron-up" id="kwChevron"></i>
        </button>
    </div>

    {{-- ── Isi Panel ── --}}
    <div class="kw-panel-body" id="kwPanelBody">
        <p class="kw-hint">
            Klik keyword untuk <strong>menyalin</strong> lalu tempel ke naskah surat.
            Sistem akan mengganti keyword ini secara otomatis saat PDF digenerate.
        </p>

        @foreach($keywords as $group => $items)
        <div class="kw-group">
            <div class="kw-group-label">{{ $group }}</div>
            <div class="kw-grid">
                @foreach($items as $item)
                <button type="button"
                        class="kw-chip"
                        onclick="copyKeyword('{{ $item['kw'] }}', this)"
                        title="{{ $item['desc'] }}">
                    <span class="kw-chip-code">{{ $item['kw'] }}</span>
                    <span class="kw-chip-desc">{{ $item['desc'] }}</span>
                    @if(!empty($item['preview']))
                    <span class="kw-chip-preview" title="Nilai aktif saat ini">
                        → {{ $item['preview'] }}
                    </span>
                    @endif
                    <span class="kw-chip-copy-icon"><i class="bi bi-clipboard"></i></span>
                </button>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Field dinamis dari form schema --}}
        <div class="kw-group" id="kwDynamicGroup" style="display:none;">
            <div class="kw-group-label">Field Tambahan (dari Form Schema)</div>
            <div class="kw-grid" id="kwDynamicGrid"></div>
        </div>

        <div class="kw-footer-note">
            <i class="bi bi-info-circle me-1"></i>
            Untuk field tambahan (Form Schema), gunakan <code>[nama_kunci]</code> sesuai
            yang didefinisikan di bagian "Field Tambahan untuk Mahasiswa".
        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════════════════
   KEYWORD PANEL STYLES
═══════════════════════════════════════════════════════ */
.kw-panel {
    border: 1.5px solid #fde68a;
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    margin-bottom: 1rem;
}

/* ── Status bar ── */
.kw-status-bar {
    background: #fffbeb;
    border-bottom: 1px solid #fde68a;
    padding: 0.6rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    font-size: 0.8rem;
}
.kw-status-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #64748b;
}
.kw-status-item i { color: #d97706; font-size: 0.85rem; }
.kw-status-label { font-weight: 600; color: #78350f; }
.kw-status-sep { color: #d1d5db; }

.kw-badge {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.kw-badge-green { background: #dcfce7; color: #166534; }
.kw-badge-blue  { background: #dbeafe; color: #1e40af; }
.kw-badge-red   { background: #fee2e2; color: #991b1b; }

.kw-warning-link {
    font-size: 0.72rem;
    font-weight: 700;
    color: #b45309;
    text-decoration: none;
    background: #fef3c7;
    padding: 3px 10px;
    border-radius: 6px;
    border: 1px solid #fde68a;
    transition: 0.15s;
}
.kw-warning-link:hover { background: #fde68a; color: #92400e; }

/* ── Header ── */
.kw-panel-header {
    background: #fffbeb;
    border-bottom: 1px solid #fde68a;
    padding: 0.7rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.kw-panel-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: #78350f;
}
.kw-toggle-btn {
    background: none;
    border: 1px solid #fde68a;
    border-radius: 7px;
    padding: 2px 8px;
    color: #d97706;
    cursor: pointer;
    transition: 0.15s;
    font-size: 0.8rem;
}
.kw-toggle-btn:hover { background: #fef3c7; }

/* ── Body ── */
.kw-panel-body {
    padding: 0.85rem 1rem 1rem;
}
.kw-hint {
    font-size: 0.78rem;
    color: #64748b;
    margin-bottom: 0.85rem;
    padding: 0.5rem 0.75rem;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 3px solid #fbbf24;
}
.kw-hint strong { color: #1e293b; }

/* ── Group ── */
.kw-group {
    margin-bottom: 0.85rem;
}
.kw-group-label {
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: #94a3b8;
    margin-bottom: 0.4rem;
}

/* ── Grid chip ── */
.kw-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

/* ── Chip ── */
.kw-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 5px 9px;
    cursor: pointer;
    transition: all 0.15s;
    text-align: left;
    max-width: 100%;
}
.kw-chip:hover {
    background: #fffbeb;
    border-color: #fbbf24;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(251,191,36,0.2);
}
.kw-chip.copied {
    background: #ecfdf5;
    border-color: #6ee7b7;
}
.kw-chip-code {
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
    font-weight: 700;
    color: #7c3aed;
    flex-shrink: 0;
}
.kw-chip-desc {
    font-size: 0.68rem;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 160px;
}
.kw-chip-preview {
    font-size: 0.65rem;
    font-weight: 700;
    color: #059669;
    background: #ecfdf5;
    padding: 1px 6px;
    border-radius: 4px;
    flex-shrink: 0;
    white-space: nowrap;
}
.kw-chip-copy-icon {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-left: 2px;
    flex-shrink: 0;
    transition: color 0.15s;
}
.kw-chip:hover .kw-chip-copy-icon { color: #d97706; }
.kw-chip.copied .kw-chip-copy-icon { color: #059669; }

/* ── Footer note ── */
.kw-footer-note {
    font-size: 0.72rem;
    color: #94a3b8;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #f1f5f9;
}
.kw-footer-note code {
    background: #f1f5f9;
    padding: 1px 5px;
    border-radius: 4px;
    font-size: 0.72rem;
    color: #7c3aed;
}

/* ── Collapsed state ── */
.kw-panel-body.collapsed {
    display: none;
}

/* ── Toast copy notif ── */
#kwCopyToast {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 9999;
    background: #1e293b;
    color: #fff;
    padding: 0.55rem 1rem;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
    opacity: 0;
    transform: translateY(8px);
    transition: all 0.2s;
    pointer-events: none;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}
#kwCopyToast.show {
    opacity: 1;
    transform: translateY(0);
}
</style>

{{-- Toast element --}}
<div id="kwCopyToast">
    <i class="bi bi-clipboard-check-fill text-warning"></i>
    <span id="kwCopyToastText">Disalin!</span>
</div>

<script>
// ── Toggle panel buka/tutup ──────────────────────────────
function toggleKwPanel() {
    const body    = document.getElementById('kwPanelBody');
    const chevron = document.getElementById('kwChevron');
    body.classList.toggle('collapsed');
    chevron.className = body.classList.contains('collapsed')
        ? 'bi bi-chevron-down'
        : 'bi bi-chevron-up';
}

// ── Copy keyword ke clipboard ────────────────────────────
function copyKeyword(keyword, btn) {
    navigator.clipboard.writeText(keyword).then(() => {
        // Visual feedback pada chip
        btn.classList.add('copied');
        const icon = btn.querySelector('.kw-chip-copy-icon i');
        if (icon) { icon.className = 'bi bi-clipboard-check-fill'; }

        setTimeout(() => {
            btn.classList.remove('copied');
            if (icon) { icon.className = 'bi bi-clipboard'; }
        }, 1800);

        // Toast notifikasi
        showCopyToast(keyword);

        // Insert ke textarea jika ada yang focused
        insertToActiveTextarea(keyword);
    }).catch(() => {
        // Fallback untuk browser lama
        const el = document.createElement('textarea');
        el.value = keyword;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        showCopyToast(keyword);
    });
}

// ── Masukkan keyword ke posisi kursor textarea ───────────
let lastFocusedTextarea = null;
document.addEventListener('focusin', function(e) {
    if (e.target && e.target.id === 'isi_template') {
        lastFocusedTextarea = e.target;
    }
});

function insertToActiveTextarea(keyword) {
    const ta = lastFocusedTextarea || document.getElementById('isi_template');
    if (!ta) return;

    const start = ta.selectionStart;
    const end   = ta.selectionEnd;
    const val   = ta.value;

    ta.value = val.substring(0, start) + keyword + val.substring(end);
    ta.selectionStart = ta.selectionEnd = start + keyword.length;
    ta.focus();
    ta.dispatchEvent(new Event('input'));
}

// ── Toast ────────────────────────────────────────────────
let toastTimer;
function showCopyToast(keyword) {
    const toast = document.getElementById('kwCopyToast');
    const text  = document.getElementById('kwCopyToastText');
    text.textContent = keyword + ' — disalin!';
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 2000);
}

// ── Update keyword dinamis dari form schema ──────────────
function updateDynamicKeywords(schemaFields) {
    const group = document.getElementById('kwDynamicGroup');
    const grid  = document.getElementById('kwDynamicGrid');
    if (!group || !grid) return;

    if (!schemaFields || schemaFields.length === 0) {
        group.style.display = 'none';
        return;
    }

    grid.innerHTML = '';
    schemaFields.forEach(field => {
        if (!field.name) return;
        const kw   = '[' + field.name + ']';
        const desc = field.label || field.name;
        const btn  = document.createElement('button');
        btn.type        = 'button';
        btn.className   = 'kw-chip';
        btn.title       = desc;
        btn.onclick     = () => copyKeyword(kw, btn);
        btn.innerHTML   = `
            <span class="kw-chip-code">${kw}</span>
            <span class="kw-chip-desc">${desc}</span>
            <span class="kw-chip-copy-icon"><i class="bi bi-clipboard"></i></span>
        `;
        grid.appendChild(btn);
    });

    group.style.display = 'block';
}
</script>