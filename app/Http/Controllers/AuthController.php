<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Menangani proses login
    public function login(Request $request)
    {
        // Validasi input login
        $request->validate([
            'id' => ['required', 'string'], // ID unik (ID Admin)
            'password' => ['required', 'string'],
        ]);

        // Cek admin berdasarkan ID (id_admin)
        $admin = Admin::where('id_admin', $request->id)->first(); // Cek berdasarkan id_admin

        // Jika admin tidak ditemukan
        if (!$admin) {
            return back()->withErrors(['id' => 'ID Admin atau password salah.'])->onlyInput('id');
        }

        // Cari user terkait dengan admin berdasarkan id_user
        $user = User::where('id_user', $admin->id_user)->first();

        // Jika user tidak ditemukan atau password salah
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['id' => 'ID Admin atau password salah.'])->onlyInput('id');
        }

        // Login berhasil, set session
        Auth::login($user);
        $request->session()->regenerate();  // Pastikan session diperbarui

        // Debug untuk memastikan login berhasil
        // dd('Login berhasil, ID User:', auth()->user()->id_user); // Hapus baris ini setelah selesai debug

        // Setelah login, arahkan ke dashboard admin
        return redirect()->route('admin.dashboard');
    }
}
