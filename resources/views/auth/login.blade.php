<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Sistem Pengajuan Surat UP45</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f3f4f6; min-height: 100vh; display: flex; align-items: center; justify-content: center;">

    <div style="background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 2rem 2.5rem; width: 100%; max-width: 420px;">

        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <img src="{{ asset('images/logoup45.png') }}" alt="UP45 Logo" style="height: 80px; width: auto; display: block; margin: 0 auto;">
            <h2 style="margin-top: 0.75rem; font-size: 1.25rem; font-weight: 700; color: #1f2937;">Sistem Pengajuan Surat</h2>
            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Universitas Proklamasi 45</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div style="background: #fef3c7; color: #92400e; border-radius: 8px; padding: 0.5rem 1rem; margin-bottom: 1rem; font-size: 0.875rem; text-align: center;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Identifier -->
            <div style="margin-bottom: 1rem;">
                <label for="identifier" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem; font-size: 0.9rem;">
                    NIM / NIP / Email
                </label>
                <input
                    id="identifier"
                    type="text"
                    name="identifier"
                    value="{{ old('identifier') }}"
                    required
                    autofocus
                    placeholder="Masukkan NIM, NIP, atau Email Anda"
                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                    onfocus="this.style.borderColor='#d97706'; this.style.boxShadow='0 0 0 3px rgba(217,119,6,0.15)'"
                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                >
                @error('identifier')
                    <div style="color: #b45309; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div style="margin-bottom: 1rem;">
                <label for="password" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem; font-size: 0.9rem;">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    placeholder="Masukkan password Anda"
                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                    onfocus="this.style.borderColor='#d97706'; this.style.boxShadow='0 0 0 3px rgba(217,119,6,0.15)'"
                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                >
                @error('password')
                    <div style="color: #b45309; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    style="width: 16px; height: 16px; accent-color: #d97706; cursor: pointer;"
                >
                <label for="remember_me" style="margin-left: 0.5rem; font-size: 0.875rem; color: #4b5563; cursor: pointer;">
                    Remember me
                </label>
            </div>

            <!-- Actions -->
            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.5rem;">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       style="font-size: 0.875rem; color: #d97706; text-decoration: none;"
                       onmouseover="this.style.textDecoration='underline'"
                       onmouseout="this.style.textDecoration='none'">
                        Lupa Password?
                    </a>
                @endif

                <button
                    type="submit"
                    style="background: linear-gradient(to right, #b45309, #f59e0b); color: #fff; font-weight: 600; padding: 0.5rem 1.25rem; border: none; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 8px rgba(180,83,9,0.15); font-size: 0.95rem; transition: opacity 0.2s;"
                    onmouseover="this.style.opacity='0.88'"
                    onmouseout="this.style.opacity='1'"
                >
                    Masuk
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div style="text-align: center; font-size: 0.75rem; color: #9ca3af; margin-top: 1.5rem;">
            &copy; {{ date('Y') }} Universitas Proklamasi 45 &bull; Sistem Pengajuan Surat
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>