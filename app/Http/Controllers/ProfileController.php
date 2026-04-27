<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil data profil berdasarkan role
        $profile = $this->getProfileData($user);

        return view('profile.index', [
            'user' => $user,
            'profile' => $profile,
            'role' => $user->role->nama_role
        ]);
    }

    /**
     * Function untuk ambil data sesuai role
     */
    private function getProfileData($user)
    {
        return match($user->role->nama_role) {
            'mahasiswa' => $user->mahasiswa,
            'staff jurusan' => $user->adminStaff,
            'pejabat' => $user->pejabat,
            'admin akademik' => $user->adminAkademik,
            default => null
        };
    }
    public function update(Request $request)
{
     $user = $request->user();

    $request->validate([
        'email' => 'required|email|unique:users,email,' . $user->id,
        'no_telepon' => 'nullable|string|max:15',
    ]);

    // update email
    $user->update([
        'email' => $request->email
    ]);

    // ambil profile sesuai role
    $profile = $user->mahasiswa 
        ?? $user->adminStaff 
        ?? $user->pejabat 
        ?? $user->adminAkademik;

    if ($profile) {
        $profile->update([
            'no_telepon' => $request->no_telepon
        ]);
    }

    return back()->with('success', 'Profil berhasil diperbarui');
}
public function destroy(Request $request)
{
    $user = $request->user();

    // optional: validasi password dulu
    $request->validate([
        'password' => ['required', 'current_password'],
    ]);

    Auth::logout();

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'Akun berhasil dihapus');
}
}