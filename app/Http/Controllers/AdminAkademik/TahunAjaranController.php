<?php

namespace App\Http\Controllers\AdminAkademik;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::with('semesters')
            ->latest()
            ->paginate(10);

        return view('admin_akademik.tahun_ajaran.index', compact('tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|unique:tahun_ajaran,tahun|regex:/^\d{4}\/\d{4}$/',
        ], [
            'tahun.regex' => 'Format tahun ajaran harus seperti 2025/2026.',
            'tahun.unique' => 'Tahun ajaran ini sudah terdaftar.',
        ]);

        DB::beginTransaction();
        try {
            $tahunAjaran = TahunAjaran::create([
                'tahun'    => $request->tahun,
                'is_aktif' => false,
            ]);

            // Otomatis buat 2 semester
            Semester::create([
                'tahun_ajaran_id' => $tahunAjaran->id,
                'semester'        => 'GANJIL',
                'is_aktif'        => false,
            ]);
            Semester::create([
                'tahun_ajaran_id' => $tahunAjaran->id,
                'semester'        => 'GENAP',
                'is_aktif'        => false,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        return back()->with('success', 'Tahun ajaran berhasil ditambahkan beserta 2 semester.');
    }

    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        $request->validate([
            'tahun' => 'required|regex:/^\d{4}\/\d{4}$/|unique:tahun_ajaran,tahun,' . $id,
        ], [
            'tahun.regex'  => 'Format tahun ajaran harus seperti 2025/2026.',
            'tahun.unique' => 'Tahun ajaran ini sudah terdaftar.',
        ]);

        $tahunAjaran->update(['tahun' => $request->tahun]);

        return back()->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        if ($tahunAjaran->is_aktif) {
            return back()->with('error', 'Tahun ajaran aktif tidak dapat dihapus.');
        }

        $tahunAjaran->delete(); // cascade delete semester

        return back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function aktifkan($id)
    {
        // Non-aktifkan semua tahun ajaran & semester
        TahunAjaran::query()->update(['is_aktif' => false]);
        Semester::query()->update(['is_aktif' => false]);

        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->update(['is_aktif' => true]);

        return back()->with('success', "Tahun ajaran {$tahunAjaran->tahun} berhasil diaktifkan.");
    }

    // ─── SEMESTER ───────────────────────────────────────────

    public function aktifkanSemester($semesterId)
    {
        $semester = Semester::with('tahunAjaran')->findOrFail($semesterId);

        // Non-aktifkan semua semester dulu
        Semester::query()->update(['is_aktif' => false]);

        // Aktifkan tahun ajaran induknya juga
        TahunAjaran::query()->update(['is_aktif' => false]);
        $semester->tahunAjaran->update(['is_aktif' => true]);

        $semester->update(['is_aktif' => true]);

        return back()->with('success', "Semester {$semester->semester} ({$semester->tahunAjaran->tahun}) berhasil diaktifkan.");
    }

    public function updateSemester(Request $request, $semesterId)
    {
        $semester = Semester::findOrFail($semesterId);

        $request->validate([
            'semester' => 'required|in:GANJIL,GENAP',
        ]);

        $semester->update(['semester' => $request->semester]);

        return back()->with('success', 'Semester berhasil diperbarui.');
    }
}