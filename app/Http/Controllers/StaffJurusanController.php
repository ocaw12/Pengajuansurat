<?php

namespace App\Http\Controllers;

use App\Models\StaffJurusan;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffJurusanController extends Controller
{
    // tampilkan daftar staf
    public function index()
    {
        $staff = StaffJurusan::with('user', 'prodi')->get();
        return view('admin.staff.index', compact('staff'));
    }

    // form tambah
    public function create()
    {
        $prodi = Prodi::all();
        return view('admin.staff.create', compact('prodi'));
    }

    // simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'id_staff' => 'required|string|unique:staff_jurusan',
            'nama_staff' => 'required|string',
            'id_prodi' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // Buat user baru di tabel users
        $user = User::create([
            'id_user' => 'USR-' . Str::upper(Str::random(6)),
            'name' => $request->nama_staff,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => 2, // role 2 = Staf Jurusan
        ]);

        // Buat data staf jurusan baru
        StaffJurusan::create([
            'id_staff' => $request->id_staff,
            'nama_staff' => $request->nama_staff,
            'id_prodi' => $request->id_prodi,
            'id_user' => $user->id_user,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staf Jurusan berhasil ditambahkan!');
    }

    // form edit
    public function edit($id)
    {
        $staff = StaffJurusan::findOrFail($id);
        $prodi = Prodi::all();
        return view('admin.staff.edit', compact('staff', 'prodi'));
    }

    // update data
    public function update(Request $request, $id)
    {
        $staff = StaffJurusan::findOrFail($id);
        $user = $staff->user;

        $request->validate([
            'nama_staff' => 'required|string',
            'id_prodi' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
        ]);

        $staff->update([
            'nama_staff' => $request->nama_staff,
            'id_prodi' => $request->id_prodi,
        ]);

        $user->update(['email' => $request->email]);

        return redirect()->route('staff.index')->with('success', 'Data Staf Jurusan berhasil diperbarui.');
    }

    // hapus data
    public function destroy($id)
    {
        $staff = StaffJurusan::findOrFail($id);
        $staff->user->delete(); // hapus user juga
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Data Staf Jurusan berhasil dihapus.');
    }
}
