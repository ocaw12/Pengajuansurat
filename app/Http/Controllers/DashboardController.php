<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Jangan lupa use Auth

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     * Redirect user ke dashboard yang sesuai berdasarkan role.
     */
    public function index(): RedirectResponse
    {
        $user = Auth::user();

        // Cek apakah user punya role (penting setelah login/register)
        if (!$user || !$user->role) {
            // Jika tidak ada role, mungkin arahkan ke halaman profil atau logout
            Auth::logout();
            return redirect('/login')->with('error', 'Role pengguna tidak ditemukan.');
        }

        $role = $user->role->nama_role;

        // Logika Redirect Berdasarkan Role
        switch ($role) {
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            case 'staff jurusan':
                // Di controller StaffJurusan kita namai rutenya staff_jurusan.dashboard
return redirect()->route('staff_jurusan.dashboard');
            case 'pejabat':
                return redirect()->route('pejabat.approval.index');
            case 'admin akademik': // <-- TAMBAHKAN KONDISI INI
                return redirect()->route('admin_akademik.dashboard'); // Rute baru
            default:
                // Fallback jika role tidak dikenali
                Auth::logout();
                return redirect('/login')->with('error', 'Role tidak valid.');
        }
    }
}

