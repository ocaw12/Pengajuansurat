<?php
namespace App\Http\Controllers\AdminAkademik;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAkademik\MasterJabatanRequest;
use App\Models\MasterJabatan;

class MasterJabatanController extends Controller
{
    public function index()
    {
        $jabatans = MasterJabatan::paginate(10);
        return view('admin_akademik.masterjabatan.index', compact('jabatans'));
    }

    public function create()
    {
        return view('admin_akademik.masterjabatan.create');
    }

    public function store(MasterJabatanRequest $request)
    {
        MasterJabatan::create($request->validated());
        return redirect()->route('admin_akademik.master-jabatan.index')->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jabatan = MasterJabatan::findOrFail($id);
        return view('admin_akademik.masterjabatan.edit', compact('jabatan'));
    }

    public function update(MasterJabatanRequest $request, $id)
    {
        $jabatan = MasterJabatan::findOrFail($id);
        $jabatan->update($request->validated());
        return redirect()->route('admin_akademik.master-jabatan.index')->with('success', 'Jabatan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jabatan = MasterJabatan::findOrFail($id);
        $jabatan->delete();
        return redirect()->route('admin_akademik.master-jabatan.index')->with('success', 'Jabatan berhasil dihapus');
    }
}
