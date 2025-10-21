<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\StaffJurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba cari admin
        $admin = Admin::where('id_admin', $request->id)->first();
        if ($admin) {
            $user = User::where('id_user', $admin->id_user)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
        }

        // Coba cari staff jurusan
        $staff = StaffJurusan::where('id_staff', $request->id)->first();
        if ($staff) {
            $user = User::where('id_user', $staff->id_user)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('staff.dashboard');
            }
        }

        return back()->withErrors(['id' => 'ID atau password salah'])->onlyInput('id');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
