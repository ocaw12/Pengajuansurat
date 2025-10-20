<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::with('fakultas')->get();
        return view('admin.prodi.index', compact('prodi'));
    }

    public function create()
    {
        $fakultas = Fakultas::all();
        return view('admin.prodi.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_prodi' => 'required|unique:prodi',
            'nama_prodi' => 'required',
            'email_prodi' => 'required|email|unique:prodi',
            'id_fakultas' => 'required|exists:fakultas,id_fakultas'
        ]);

        Prodi::create($request->all());
        return redirect()->route('prodi.index')->with('success', 'Data Prodi berhasil ditambahkan.');
    }

    public function edit($id_prodi)
    {
        $prodi = Prodi::findOrFail($id_prodi);
        $fakultas = Fakultas::all();
        return view('admin.prodi.edit', compact('prodi', 'fakultas'));
    }

    public function update(Request $request, $id_prodi)
    {
        $prodi = Prodi::findOrFail($id_prodi);
        $request->validate([
            'nama_prodi' => 'required',
            'email_prodi' => 'required|email|unique:prodi,email_prodi,' . $id_prodi . ',id_prodi',
            'id_fakultas' => 'required|exists:fakultas,id_fakultas'
        ]);

        $prodi->update($request->all());
        return redirect()->route('prodi.index')->with('success', 'Data Prodi berhasil diperbarui.');
    }

    public function destroy($id_prodi)
    {
        $prodi = Prodi::findOrFail($id_prodi);
        $prodi->delete();

        return redirect()->route('prodi.index')->with('success', 'Data Prodi berhasil dihapus.');
    }
}
