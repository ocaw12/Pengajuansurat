<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mengarahkan pengguna ke dashboard yang sesuai berdasarkan peran.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request) // <-- NAMA METHOD DIUBAH DI SINI
    {
        // Pastikan user sudah login (meskipun sudah ada middleware)
        if (!Auth::check()) {
            return redirect('/login');
        }

        $role = Auth::user()->role->nama_role;

        switch ($role) {
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            case 'staff jurusan':
                return redirect()->route('staff.validasi.index');
            case 'pejabat':
                return redirect()->route('pejabat.approval.index');
            case 'admin akademik':
                // Arahkan ke dashboard admin akademik jika sudah dibuat
                // return redirect()->route('admin.dashboard');
                // Untuk sementara, kita bisa redirect ke home atau halaman lain
                return redirect('/')->with('success', 'Login sebagai Admin Akademik berhasil.');
            default:
                // Jika role tidak dikenali, logout untuk keamanan
                Auth::logout();
                return redirect('/login')->with('error', 'Role pengguna tidak dikenali.');
        }
    }
}

