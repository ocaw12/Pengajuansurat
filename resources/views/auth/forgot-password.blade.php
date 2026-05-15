<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi – Sistem Pengajuan Surat UP45</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body style="background-color: #f3f4f6; min-height: 100vh; display: flex; align-items: center; justify-content: center;">

    <div style="background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 2rem 2.5rem; width: 100%; max-width: 420px;">

        <!-- Header -->
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background-color: #fef3c7; color: #d97706; border-radius: 50%; margin-bottom: 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">Lupa Kata Sandi?</h2>
            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; padding: 0 1rem;">
                Jangan khawatir! Cukup masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; background-color: #f0fdf4; border-left: 4px solid #4ade80; color: #15803d; font-size: 0.875rem; border-radius: 6px; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div style="margin-bottom: 1.25rem;">
                <label for="email" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem; font-size: 0.9rem;">
                    Alamat Email
                </label>
                <div style="position: relative;">
                    <span style="position: absolute; top: 50%; left: 0.75rem; transform: translateY(-50%); color: #9ca3af; pointer-events: none;">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="nama@email.com"
                        style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 10px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#d97706'; this.style.boxShadow='0 0 0 3px rgba(217,119,6,0.15)'"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                    >
                </div>
                @error('email')
                    <div style="color: #b45309; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Actions -->
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <button
                    type="submit"
                    style="width: 100%; background: #f59e0b; color: #fff; font-weight: 700; padding: 0.75rem 1rem; border: none; border-radius: 10px; cursor: pointer; font-size: 0.9rem; letter-spacing: 0.05em; text-transform: uppercase; box-shadow: 0 4px 12px rgba(245,158,11,0.3); transition: background 0.2s;"
                    onmouseover="this.style.background='#d97706'"
                    onmouseout="this.style.background='#f59e0b'"
                >
                    Kirim Link Reset Password
                </button>

                <div style="text-align: center;">
                    <a href="{{ route('login') }}"
                       style="font-size: 0.875rem; font-weight: 500; color: #d97706; text-decoration: none;"
                       onmouseover="this.style.color='#b45309'"
                       onmouseout="this.style.color='#d97706'">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Login
                    </a>
                </div>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>