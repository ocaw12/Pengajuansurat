<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIPAS — Sistem Informasi Pengajuan Surat</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ["Poppins", "ui-sans-serif", "system-ui"],
          },
          colors: {
            brand: {   /* Amber palette as 'brand' */
              50:  '#fffbeb',
              100: '#fef3c7',
              200: '#fde68a',
              300: '#fcd34d',
              400: '#fbbf24',
              500: '#f59e0b',
              600: '#d97706',
              700: '#b45309',
              800: '#92400e',
              900: '#78350f',
            },
          },
          boxShadow: {
            soft: '0 10px 25px -10px rgba(180, 83, 9, 0.18)'
          }
        },
      },
    };
  </script>

  <!-- Google Font: Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <meta name="description" content="SIPAS — Layanan digital pengajuan dan verifikasi surat untuk mahasiswa & akademik."/>
</head>

<body class="bg-gradient-to-br from-brand-50 via-white to-brand-100 text-slate-800 antialiased">
  <!-- Background decorations -->
  <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10">
    <div class="absolute -top-32 -right-24 h-72 w-72 rounded-full bg-gradient-to-br from-brand-200 to-white blur-3xl opacity-70"></div>
    <div class="absolute -bottom-40 -left-24 h-80 w-80 rounded-full bg-gradient-to-tr from-white to-brand-100 blur-3xl opacity-70"></div>
  </div>

  <!-- NAVBAR -->
  <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-brand-100">
    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <a href="#" class="inline-flex items-center gap-2 font-semibold text-brand-700 hover:text-brand-800">
  <img src="{{ asset('images/logoup45.png') }}" alt="Logo UP45" class="h-8 w-auto">
  <span class="text-lg tracking-tight">SISURAT</span>
</a>


        <div class="flex items-center gap-3">
          @if (Route::has('login'))
            @auth
              <a href="{{ url('/dashboard') }}" class="hidden sm:inline-flex items-center rounded-lg bg-gradient-to-r from-brand-600 to-brand-500 px-4 py-2 text-white text-sm font-medium shadow-soft hover:from-brand-700 hover:to-brand-500/90 focus:outline-none focus:ring-2 focus:ring-brand-200">Dashboard</a>
            @else
              <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg bg-gradient-to-r from-brand-600 to-brand-500 px-4 py-2 text-white text-sm font-medium shadow-soft hover:from-brand-700 hover:to-brand-500/90 focus:outline-none focus:ring-2 focus:ring-brand-200">Login</a>
            @endauth
          @endif
        </div>
      </div>
    </nav>
  </header>

  <!-- HERO -->
  <section class="relative">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-2 gap-10 items-center py-14 sm:py-20">
        <div>
          <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white px-3 py-1 text-xs font-medium text-brand-700 shadow">
            Proses cepat & terdokumentasi
          </span>
          <h1 class="mt-4 text-4xl sm:text-5xl font-bold tracking-tight text-brand-700">Sistem Informasi Pengajuan Surat</h1>
          <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">Layanan digital untuk memudahkan mahasiswa dan akademik dalam proses pengajuan, verifikasi, dan penerbitan surat menyurat di lingkungan kampus.</p>

          <div class="mt-6 flex flex-wrap gap-3">
            @guest
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-brand-400 px-5 py-3 text-white font-semibold shadow-soft hover:from-brand-700 hover:to-brand-400/90 focus:outline-none focus:ring-2 focus:ring-brand-200">
              Mulai Pengajuan
            </a>
            @endguest
            <a href="#tata-cara" class="inline-flex items-center gap-2 rounded-xl border border-brand-200 bg-white px-5 py-3 font-semibold text-brand-700 hover:bg-brand-50">
              Lihat Tata Cara
            </a>
          </div>
        </div>

        <div class="relative">
          <div class="absolute -inset-4 -z-10 rounded-3xl bg-gradient-to-br from-brand-100 via-white to-brand-50 blur-2xl"></div>
          <figure class="overflow-hidden rounded-3xl border border-brand-200 bg-white shadow-soft">
            <img src="{{ asset('images/up45gedung.png') }}" alt="Kampus UP 45 — Gedung utama" class="h-72 w-full object-cover sm:h-96" />
          </figure>
          <figcaption class="mt-3 text-center text-sm text-brand-700">Universitas Proklamasi 45 Yogyakarta</figcaption>
        </div>
      </div>
    </div>
  </section>

  <!-- FITUR UTAMA -->
  <section aria-labelledby="fitur" class="py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 id="fitur" class="text-2xl sm:text-3xl font-bold text-slate-900">Fitur Utama</h2>
      <p class="mt-2 text-slate-600">Semua yang Anda butuhkan untuk pengajuan surat yang rapi dan cepat.</p>

      <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Card -->
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <div class="flex items-center gap-3">
            <div class="rounded-xl bg-gradient-to-br from-brand-50 to-white p-3 text-brand-700">
              <!-- Document Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M19.5 14.25v2.378a2.25 2.25 0 01-2.25 2.25h-10.5A2.25 2.25 0 014.5 16.628V7.372a2.25 2.25 0 012.25-2.25h5.379a2.25 2.25 0 011.59.659l3.621 3.621a2.25 2.25 0 01.66 1.59V9.75"/></svg>
            </div>
            <h3 class="text-lg font-semibold">Pengajuan Online</h3>
          </div>
          <p class="mt-3 text-sm text-slate-600">Ajukan surat kapan saja tanpa harus datang ke kampus.</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <div class="flex items-center gap-3">
            <div class="rounded-xl bg-gradient-to-br from-brand-50 to-white p-3 text-brand-700">
              <!-- Check Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-semibold">Persetujuan Cepat</h3>
          </div>
          <p class="mt-3 text-sm text-slate-600">Verifikasi otomatis & tracking status secara real-time.</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <div class="flex items-center gap-3">
            <div class="rounded-xl bg-gradient-to-br from-white to-brand-50 p-3 text-brand-700">
              <!-- Academic Cap Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M12 14.25L3 9l9-5.25L21 9l-9 5.25z"/><path d="M19.5 11.25v3.75c0 2.071-3.806 3.75-7.5 3.75S4.5 17.071 4.5 15v-3.75"/></svg>
            </div>
            <h3 class="text-lg font-semibold">Jenis Surat Lengkap</h3>
          </div>
          <p class="mt-3 text-sm text-slate-600">Dukungan surat mahasiswa & dosen untuk berbagai keperluan.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- TATA CARA -->
  <section id="tata-cara" aria-labelledby="tata-cara-title" class="py-12 sm:py-16 bg-white/70">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex items-start justify-between gap-6">
        <div>
          <h2 id="tata-cara-title" class="text-2xl sm:text-3xl font-bold text-slate-900">Tata Cara Mengajukan Surat</h2>
          <p class="mt-2 text-slate-600 max-w-2xl">Ikuti langkah singkat berikut agar pengajuan Anda cepat diproses.</p>
        </div>
        <a href="#faq" class="hidden sm:inline-flex items-center text-brand-700 hover:text-brand-800 font-medium">Lihat FAQ →</a>
      </div>

      <ol class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Step Item -->
        <li class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <span class="absolute -top-3 left-6 inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-brand-600 to-brand-400 text-white text-sm font-bold shadow">1</span>
          <h3 class="mt-2 font-semibold">Login ke SIPAS</h3>
          <p class="mt-1 text-sm text-slate-600">Gunakan akun SSO kampus untuk masuk.</p>
        </li>
        <li class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <span class="absolute -top-3 left-6 inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-brand-600 to-brand-400 text-white text-sm font-bold shadow">2</span>
          <h3 class="mt-2 font-semibold">Pilih Jenis Surat</h3>
          <p class="mt-1 text-sm text-slate-600">Contoh: Surat Aktif Kuliah, Rekomendasi, Penelitian, Magang, dsb.</p>
        </li>
        <li class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <span class="absolute -top-3 left-6 inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-brand-600 to-brand-400 text-white text-sm font-bold shadow">3</span>
          <h3 class="mt-2 font-semibold">Lengkapi Form</h3>
          <p class="mt-1 text-sm text-slate-600">Isi data & unggah berkas pendukung sesuai persyaratan.</p>
        </li>
        <li class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <span class="absolute -top-3 left-6 inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-brand-600 to-brand-400 text-white text-sm font-bold shadow">4</span>
          <h3 class="mt-2 font-semibold">Kirim Pengajuan</h3>
          <p class="mt-1 text-sm text-slate-600">Sistem mengirim ke verifikator program studi/fakultas.</p>
        </li>
        <li class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <span class="absolute -top-3 left-6 inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-brand-600 to-brand-400 text-white text-sm font-bold shadow">5</span>
          <h3 class="mt-2 font-semibold">Pantau Status</h3>
          <p class="mt-1 text-sm text-slate-600">Notifikasi status: <em>Diterima, Revisi, Disetujui, Terbit</em>.</p>
        </li>
        <li class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <span class="absolute -top-3 left-6 inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-brand-600 to-brand-400 text-white text-sm font-bold shadow">6</span>
          <h3 class="mt-2 font-semibold">Unduh/ambil Surat</h3>
          <p class="mt-1 text-sm text-slate-600">Surat digital bertanda tangan elektronik atau ambil fisik di TU.</p>
        </li>
      </ol>
    </div>
  </section>

  <!-- JENIS SURAT & PERSYARATAN -->
  <section aria-labelledby="jenis-surat" class="py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 id="jenis-surat" class="text-2xl sm:text-3xl font-bold text-slate-900">Jenis Surat & Persyaratan</h2>
      <p class="mt-2 text-slate-600">Contoh jenis surat yang umum diajukan oleh mahasiswa dan dosen.</p>

      <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Card item -->
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <h3 class="font-semibold">Surat Keterangan Aktif Kuliah</h3>
          <ul class="mt-2 list-disc pl-5 text-sm text-slate-600">
            <li>KTM & KRS terbaru</li>
            <li>Keperluan (beasiswa/administrasi)</li>
          </ul>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <h3 class="font-semibold">Surat Rekomendasi</h3>
          <ul class="mt-2 list-disc pl-5 text-sm text-slate-600">
            <li>CV ringkas</li>
            <li>Tujuan & kontak instansi</li>
          </ul>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <h3 class="font-semibold">Izin Penelitian/Magang</h3>
          <ul class="mt-2 list-disc pl-5 text-sm text-slate-600">
            <li>Proposal singkat</li>
            <li>Surat pengantar prodi</li>
          </ul>
        </article>
      </div>

      <div class="mt-6">
        <a href="#" class="inline-flex items-center text-brand-700 hover:text-brand-800 font-medium">Lihat daftar lengkap jenis surat →</a>
      </div>
    </div>
  </section>

  <!-- ALUR PERSETUJUAN (Timeline) -->
  <section aria-labelledby="alur" class="py-12 sm:py-16 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 id="alur" class="text-2xl sm:text-3xl font-bold text-slate-900">Alur Persetujuan</h2>
      <p class="mt-2 text-slate-600">Setiap pengajuan melalui tahapan berikut hingga surat diterbitkan.</p>

      <div class="mt-8 relative">
        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-brand-100 to-brand-200 sm:left-1/2"></div>
        <ul class="space-y-8">
          <!-- Item -->
          <li class="relative sm:flex sm:items-center">
            <div class="sm:w-1/2 sm:pr-10">
              <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-soft">
                <h3 class="font-semibold">Verifikator Prodi</h3>
                <p class="mt-1 text-sm text-slate-600">Memeriksa kelengkapan data & persyaratan.</p>
              </div>
            </div>
            <div class="absolute left-4 sm:left-1/2 -ml-1.5 h-3 w-3 rounded-full bg-gradient-to-r from-brand-600 to-brand-400"></div>
          </li>
          <li class="relative sm:flex sm:items-center">
            <div class="sm:w-1/2 sm:ml-auto sm:pl-10">
              <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-soft">
                <h3 class="font-semibold">Fakultas</h3>
                <p class="mt-1 text-sm text-slate-600">Validasi akademik & paraf pejabat terkait.</p>
              </div>
            </div>
            <div class="absolute left-4 sm:left-1/2 -ml-1.5 h-3 w-3 rounded-full bg-gradient-to-r from-brand-600 to-brand-400"></div>
          </li>
          <li class="relative sm:flex sm:items-center">
            <div class="sm:w-1/2 sm:pr-10">
              <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-soft">
                <h3 class="font-semibold">BAAK/Universitas</h3>
                <p class="mt-1 text-sm text-slate-600">Finalisasi, tanda tangan elektronik, & penerbitan.</p>
              </div>
            </div>
            <div class="absolute left-4 sm:left-1/2 -ml-1.5 h-3 w-3 rounded-full bg-gradient-to-r from-brand-600 to-brand-400"></div>
          </li>
        </ul>
      </div>
    </div>
  </section>

  <!-- FAQ / INFORMASI PENTING -->
  <section id="faq" aria-labelledby="faq-title" class="py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 id="faq-title" class="text-2xl sm:text-3xl font-bold text-slate-900">Informasi Penting</h2>
      <p class="mt-2 text-slate-600">Pertanyaan umum terkait proses pengajuan surat.</p>

      <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <!-- Accordion via details/summary for accessibility -->
        <details class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-soft" open>
          <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
            <h3 class="font-semibold">Berapa lama proses penerbitan surat?</h3>
            <span class="rounded-full border border-slate-200 px-2 text-xs text-slate-500 group-open:hidden">buka</span>
            <span class="rounded-full border border-slate-200 px-2 text-xs text-slate-500 hidden group-open:inline">tutup</span>
          </summary>
          <p class="mt-3 text-sm text-slate-600">Umumnya 1–3 hari kerja setelah seluruh verifikator menyetujui. Waktu dapat bervariasi tergantung antrian.</p>
        </details>

        <details class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
            <h3 class="font-semibold">Apakah ada biaya?</h3>
            <span class="rounded-full border border-slate-200 px-2 text-xs text-slate-500 group-open:hidden">buka</span>
            <span class="rounded-full border border-slate-200 px-2 text-xs text-slate-500 hidden group-open:inline">tutup</span>
          </summary>
          <p class="mt-3 text-sm text-slate-600">Kebanyakan surat tidak berbiaya. Jika ada, akan muncul informasi biaya di form pengajuan.</p>
        </details>

        <details class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
            <h3 class="font-semibold">Bagaimana jika pengajuan perlu revisi?</h3>
            <span class="rounded-full border border-slate-200 px-2 text-xs text-slate-500 group-open:hidden">buka</span>
            <span class="rounded-full border border-slate-200 px-2 text-xs text-slate-500 hidden group-open:inline">tutup</span>
          </summary>
          <p class="mt-3 text-sm text-slate-600">Anda akan menerima notifikasi beserta catatan revisi. Perbaiki data/berkas lalu kirim ulang.</p>
        </details>

        <details class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
          <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
            <h3 class="font-semibold">Apakah surat digital sah?</h3>
            <span class="rounded-full border border-slate-200 px-2 text-xs text-slate-500 group-open:hidden">buka</span>
            <span class="rounded-full border border-slate-200 px-2 text-xs text-slate-500 hidden group-open:inline">tutup</span>
          </summary>
          <p class="mt-3 text-sm text-slate-600">Surat digital bertanda tangan elektronik dari universitas sah dan dapat diverifikasi.</p>
        </details>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-brand-700 via-brand-600 to-brand-500 p-8 sm:p-12 text-white shadow-soft">
        <div class="grid gap-8 sm:grid-cols-2 sm:items-center">
          <div>
            <h2 class="text-2xl sm:text-3xl font-bold">Siap ajukan surat sekarang?</h2>
            <p class="mt-2 text-brand-100/90">Masuk ke SIPAS dan mulai pengajuan Anda hari ini.</p>
          </div>
          <div class="flex flex-wrap items-center gap-3 sm:justify-end">
            @guest
            <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl bg-white px-5 py-3 font-semibold text-brand-700 hover:bg-brand-50 focus:outline-none focus:ring-2 focus:ring-white/50">Login</a>
            @endguest
            <a href="#tata-cara" class="inline-flex items-center rounded-xl bg-white/15 px-5 py-3 font-semibold text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/40">Lihat Tata Cara</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="border-t border-slate-200/70 bg-white/80">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col items-center justify-between gap-4 py-6 sm:flex-row">
        <p class="text-sm text-slate-500">© {{ date('Y') }} Universitas — Sistem Informasi Pengajuan Surat.</p>
        <nav class="flex items-center gap-5 text-sm text-slate-600">
          <a href="#" class="hover:text-slate-900">Kebijakan</a>
          <a href="#" class="hover:text-slate-900">Bantuan</a>
          <a href="#" class="hover:text-slate-900">Kontak</a>
        </nav>
      </div>
    </div>
  </footer>

</body>
</html>
