<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Daftar role yang diizinkan (dipisah koma)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil role user yang sedang login
        // Kita menggunakan relasi 'role' dari model User
        $userRole = Auth::user()->role->nama_role;

        // 3. Cek apakah role user ada di dalam daftar $roles yang diizinkan
        if (in_array($userRole, $roles)) {
            // 4. Jika diizinkan, lanjutkan request
            return $next($request);
        }

        // 5. Jika tidak diizinkan, kembalikan error 403 (Forbidden)
        abort(403, 'ANDA TIDAK MEMILIKI WEWENANG UNTUK MENGAKSES HALAMAN INI.');
    }
}
