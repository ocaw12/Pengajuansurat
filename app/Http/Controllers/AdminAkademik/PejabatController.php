<?php

namespace App\Http\Controllers\AdminAkademik;

use App\Http\Controllers\Controller;
use App\Models\Pejabat;
use App\Models\User;
use App\Models\MasterJabatan;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Http\Requests\AdminAkademik\StorePejabatRequest;
use Illuminate\Support\Facades\Hash;

class PejabatController extends Controller
{
    // Menampilkan daftar pejabat
    public function index()
    {
        $pejabat = Pejabat::with('user', 'masterJabatan', 'fakultas', 'programStudi')->get();
        return view('admin_akademik.pejabat.index', compact('pejabat'));
    }

    // Menampilkan form untuk membuat pejabat baru
    public function create()
    {
        $masterJabatan = MasterJabatan::all();
        $fakultas = Fakultas::all();
        $programStudi = ProgramStudi::all();
        return view('admin_akademik.pejabat.create', compact('masterJabatan', 'fakultas', 'programStudi'));
    }

    // Menyimpan data pejabat dan user
    public function store(StorePejabatRequest $request)
    {
        // Password default
        $password = $request->nip_atau_nidn;

        // Buat user
        $user = User::create([
            'email'     => $request->email,
            'password'  => Hash::make($password),
            'role_id'   => 3, // Role Pejabat
            'is_active' => $request->boolean('is_active'), // ⬅️ TAMBAH DI SINI
        ]);

        // Buat pejabat
        $pejabat = Pejabat::create([
            'user_id'           => $user->id,
            'nip_atau_nidn'     => $request->nip_atau_nidn,
            'nama_lengkap'      => $request->nama_lengkap,
            'master_jabatan_id' => $request->jabatan,
            'fakultas_id'       => $request->fakultas_id,
            'program_studi_id'  => $request->program_studi_id,
            'no_telepon'        => $request->no_telepon,
        ]);

        return redirect()->route('admin_akademik.pejabat.index')->with('success', 'Pejabat berhasil ditambahkan!');
    }

    // Menampilkan form untuk edit pejabat
    public function edit($id)
    {
        $pejabat = Pejabat::with('user')->findOrFail($id); // ⬅️ sekalian load user
        $masterJabatan = MasterJabatan::all();
        $fakultas = Fakultas::all();
        $programStudi = ProgramStudi::all();
        return view('admin_akademik.pejabat.edit', compact('pejabat', 'masterJabatan', 'fakultas', 'programStudi'));
    }

    // Memperbarui data pejabat
    public function update(StorePejabatRequest $request, $id)
    {
        $pejabat = Pejabat::with('user')->findOrFail($id);
        $user = $pejabat->user;

        // Siapkan data user
        $userData = [
            'email'     => $request->email,
            'is_active' => $request->boolean('is_active'), // ⬅️ TAMBAH DI SINI
        ];

        // Update password jika ada input password
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update data pejabat
        $pejabat->update([
            'nip_atau_nidn'     => $request->nip_atau_nidn,
            'nama_lengkap'      => $request->nama_lengkap,
            'master_jabatan_id' => $request->jabatan,
            'fakultas_id'       => $request->fakultas_id,
            'program_studi_id'  => $request->program_studi_id,
            'no_telepon'        => $request->no_telepon,
        ]);

        return redirect()->route('admin_akademik.pejabat.index')->with('success', 'Pejabat berhasil diperbarui!');
    }

    // Menghapus data pejabat
    public function destroy($id)
    {
        $pejabat = Pejabat::findOrFail($id);

        $pejabat->delete();

        return redirect()->route('admin_akademik.pejabat.index')->with('success', 'Pejabat berhasil dihapus!');
    }
}
