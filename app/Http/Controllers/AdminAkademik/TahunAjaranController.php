<?php

namespace App\Http\Controllers\AdminAkademik;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::latest()->paginate(10);

        return view('admin_akademik.tahun_ajaran.index', compact('tahunAjarans'));
    }

    public function create()
    {
        return view('admin_akademik.tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|unique:tahun_ajaran,tahun'
        ]);

        TahunAjaran::create([
            'tahun' => $request->tahun,
            'is_aktif' => false
        ]);

        return redirect()
            ->route('admin_akademik.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        return view('admin_akademik.tahun_ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        $request->validate([
            'tahun' => 'required|unique:tahun_ajaran,tahun,' . $id
        ]);

        $tahunAjaran->update([
            'tahun' => $request->tahun
        ]);

        return redirect()
            ->route('admin_akademik.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        $tahunAjaran->delete();

        return redirect()
            ->route('admin_akademik.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function aktifkan($id)
    {
        TahunAjaran::query()->update([
            'is_aktif' => false
        ]);

        $tahunAjaran = TahunAjaran::findOrFail($id);

        $tahunAjaran->update([
            'is_aktif' => true
        ]);

        return redirect()
            ->route('admin_akademik.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diaktifkan.');
    }
}