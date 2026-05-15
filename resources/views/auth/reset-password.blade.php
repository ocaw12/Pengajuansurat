<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi – Sistem Pengajuan Surat UP45</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body style="background-color: #f3f4f6; min-height: 100vh; display: flex; align-items: center; justify-content: center;">

    <div style="background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 2rem 2.5rem; width: 100%; max-width: 420px;">

        <!-- Header -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background-color: #fef3c7; color: #d97706; border-radius: 50%; margin-bottom: 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">Atur Ulang Kata Sandi</h2>
            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">
                Silakan masukkan kata sandi baru Anda di bawah ini.
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Hidden Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email (readonly) -->
            <div style="margin-bottom: 1.25rem;">
                <label for="email" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem; font-size: 0.9rem;">
                    Email
                </label>
                <div style="position: relative;">
                    <span style="position: absolute; top: 50%; left: 0.75rem; transform: translateY(-50%); color: #9ca3af; pointer-events: none;">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', request()->email) }}"
                        required
                        readonly
                        autocomplete="username"
                        style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 10px; font-size: 0.95rem; background-color: #f9fafb; color: #6b7280; cursor: not-allowed; outline: none;"
                    >
                </div>
                @error('email')
                    <div style="color: #b45309; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Baru -->
            <div style="margin-bottom: 1.25rem;">
                <label for="password" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem; font-size: 0.9rem;">
                    Kata Sandi Baru
                </label>
                <div style="position: relative;">
                    <span style="position: absolute; top: 50%; left: 0.75rem; transform: translateY(-50%); color: #9ca3af; pointer-events: none;">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        autocomplete="new-password"
                        style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 10px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#d97706'; this.style.boxShadow='0 0 0 3px rgba(217,119,6,0.15)'"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                    >
                </div>
                @error('password')
                    <div style="color: #b45309; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div style="margin-bottom: 1.25rem;">
                <label for="password_confirmation" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem; font-size: 0.9rem;">
                    Konfirmasi Kata Sandi Baru
                </label>
                <div style="position: relative;">
                    <span style="position: absolute; top: 50%; left: 0.75rem; transform: translateY(-50%); color: #9ca3af; pointer-events: none;">
                        <i class="bi bi-shield-lock"></i>
                    </span>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        placeholder="••••••••"
                        autocomplete="new-password"
                        style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 10px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#d97706'; this.style.boxShadow='0 0 0 3px rgba(217,119,6,0.15)'"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                    >
                </div>
                @error('password_confirmation')
                    <div style="color: #b45309; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Actions -->
            <div style="display: flex; flex-direction: column; gap: 1rem; padding-top: 0.5rem;">
                <button
                    type="submit"
                    style="width: 100%; background: #f59e0b; color: #fff; font-weight: 700; padding: 0.75rem 1rem; border: none; border-radius: 10px; cursor: pointer; font-size: 0.9rem; letter-spacing: 0.05em; text-transform: uppercase; box-shadow: 0 4px 12px rgba(245,158,11,0.25); transition: background 0.2s;"
                    onmouseover="this.style.background='#d97706'"
                    onmouseout="this.style.background='#f59e0b'"
                >
                    Perbarui Kata Sandi
                </button>

                <div style="text-align: center;">
                    <a href="{{ route('login') }}"
                       style="display: inline-flex; align-items: center; font-size: 0.875rem; font-weight: 500; color: #6b7280; text-decoration: none; transition: color 0.2s;"
                       onmouseover="this.style.color='#d97706'"
                       onmouseout="this.style.color='#6b7280'">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; margin-right: 4px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Halaman Login
                    </a>
                </div>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>