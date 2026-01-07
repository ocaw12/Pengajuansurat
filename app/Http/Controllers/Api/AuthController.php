<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle Login Request
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Cek Kredensial
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        // 3. Ambil User
        $user = User::where('email', $request->email)->firstOrFail();

        // 4. Cek Role (LOGIKA DIPERBAIKI)
        // Load relasi role untuk mengecek isinya
        $user->load('role');

        if (!$user->role) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak memiliki role yang valid.'
            ], 403);
        }

        // Deteksi nama kolom secara dinamis (nama_role / name / role)
        // Ini menghindari error "Column not found"
        $roleName = $user->role->nama_role 
                 ?? $user->role->name 
                 ?? $user->role->role 
                 ?? '';

        // Debugging: Jika masih gagal, pesan ini akan memberitahu kita apa nama role yang terbaca
        if (strtolower($roleName) !== 'mahasiswa') {
             // Opsional: Logout user dari session web agar tidak nyangkut
             // Auth::logout(); 
             
             return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Aplikasi ini khusus Mahasiswa.',
                'debug_role_detected' => $roleName // Info untuk debugging di Postman
            ], 403);
        }
        
        // 5. Cek Status Aktif (Opsional, jika kolom ada)
        if (isset($user->is_active) && !$user->is_active) {
             return response()->json([
                'success' => false,
                'message' => 'Akun Anda dinonaktifkan.'
            ], 403);
        }

        // 6. Buat Token
        $token = $user->createToken('mobile_app')->plainTextToken;

        // 7. Load data Mahasiswa terkait
        $mahasiswa = $user->mahasiswa;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $roleName,
                    'nim' => $mahasiswa ? $mahasiswa->nim : null,
                    'prodi' => ($mahasiswa && $mahasiswa->prodi) ? $mahasiswa->prodi->nama_prodi : null,
                ]
            ]
        ], 200);
    }

    /**
     * Handle Logout Request
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }
    
    /**
     * Get User Profile
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load(['role', 'mahasiswa.prodi.fakultas']);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}