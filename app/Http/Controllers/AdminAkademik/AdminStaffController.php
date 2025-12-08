<?php

namespace App\Http\Controllers\AdminAkademik;

use App\Models\AdminStaff;
use App\Models\User;
use App\Models\ProgramStudi;
use App\Http\Requests\AdminAkademik\StoreAdminStaffRequest;
use App\Http\Requests\AdminAkademik\UpdateAdminStaffRequest; // Gunakan request khusus untuk update
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    // Menampilkan form untuk input data staff
    public function create()
    {
        $program_studis = ProgramStudi::all(); // Mengambil semua data program studi untuk ditampilkan dalam dropdown
        return view('admin_akademik.admin_staff.create', compact('program_studis'));
    }

    // Menyimpan data staff dan user
    public function store(StoreAdminStaffRequest $request)
    {
        // Validasi sudah dilakukan di StoreAdminStaffRequest

        // Menyimpan data ke tabel users terlebih dahulu (karena user_id diperlukan di admin_staff)
        $user = User::create([
            'email'     => $request->email,
            'password'  => Hash::make($request->nip_staff), // Password sama dengan NIP
            'role_id'   => 2, // Pastikan ini adalah ID role untuk admin_staff
            'is_active' => $request->boolean('is_active'), // ⬅️ is_active
        ]);

        // Menyimpan data ke tabel admin_staff
        $adminStaff = AdminStaff::create([
            'nip_staff'        => $request->nip_staff,
            'nama_lengkap'     => $request->nama_lengkap,
            'program_studi_id' => $request->program_studi_id,
            'no_telepon'       => $request->no_telepon,  // ⬅️ DITAMBAHKAN
            'user_id'          => $user->id, // Menyimpan ID user yang baru saja dibuat
        ]);

        // Redirect ke halaman daftar staff
        return redirect()
            ->route('admin_akademik.admin-staff.index')
            ->with('success', 'Data staff berhasil ditambahkan!');
    }

    // Menampilkan daftar staff
    public function index()
    {
        // Mengambil semua data admin staff dengan relasi program studi dan user
        $adminStaffs = AdminStaff::with('programStudi', 'user')->get();
        return view('admin_akademik.admin_staff.index', compact('adminStaffs'));
    }

    // Menampilkan form edit untuk data staff
    public function edit($id)
    {
        // Ambil admin staff berdasarkan ID
        $adminStaff = AdminStaff::with('programStudi', 'user')->findOrFail($id); // ⬅️ load user juga

        // Ambil data program studi untuk dropdown
        $program_studis = ProgramStudi::all();

        return view('admin_akademik.admin_staff.edit', compact('adminStaff', 'program_studis'));
    }

    // Update data staff dan user
    public function update(UpdateAdminStaffRequest $request, $id)
    {
        $adminStaff = AdminStaff::findOrFail($id);

        // Ambil ID user yang terkait dengan admin staff
        $user = $adminStaff->user;

        // Update data admin staff
        $adminStaff->update([
            'nip_staff'        => $request->nip_staff,
            'nama_lengkap'     => $request->nama_lengkap,
            'program_studi_id' => $request->program_studi_id,
            'no_telepon'       => $request->no_telepon, // ⬅️ DITAMBAHKAN
        ]);

        // Data dasar user
        $userData = [
            'email'     => $request->email, // Update email
            'is_active' => $request->boolean('is_active'), // ⬅️ is_active
        ];

        // Optional: Update password jika perlu
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()
            ->route('admin_akademik.admin-staff.index')
            ->with('success', 'Data staff berhasil diperbarui!');
    }

    // Menghapus data staff
    public function destroy($id)
    {
        // Mencari data adminStaff berdasarkan ID
        $adminStaff = AdminStaff::findOrFail($id);

        // Menghapus data user yang terkait
        $adminStaff->user->delete();

        // Menghapus data adminStaff
        $adminStaff->delete();

        // Redirect ke halaman daftar staff dengan pesan sukses
        return redirect()
            ->route('admin_akademik.admin-staff.index')
            ->with('success', 'Data staff berhasil dihapus!');
    }
}
