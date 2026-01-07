<?php
// app/Http/Controllers/ProdiController.php
namespace App\Http\Controllers\AdminAkademik;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAkademik\ProdiRequest;

class ProdiController extends Controller
{
    // Menampilkan form untuk tambah Prodi
    public function create()
    {
        // Ambil data fakultas untuk dropdown
        $fakultas = Fakultas::all();
        return view('admin_akademik.prodi.create', compact('fakultas'));
    }

    // Menyimpan data Prodi

public function store(ProdiRequest $request)
{
    // Logic penyimpanan
    $validated = $request->validated(); // Ini akan memastikan hanya data yang valid yang disimpan
    
    ProgramStudi::create($validated);

    return redirect()->route('admin_akademik.prodi.index')->with('success', 'Program Studi berhasil ditambahkan.');
}


    // Menampilkan daftar Prodi
    public function index()
    {
        $prodis = ProgramStudi::with('fakultas')->paginate(10); // 10 data per halaman
    return view('admin_akademik.prodi.index', compact('prodis'));

    }
     public function edit($id)
    {
        $prodi = ProgramStudi::findOrFail($id);
        $fakultas = Fakultas::all(); // Mengambil semua fakultas untuk dropdown
        return view('admin_akademik.prodi.edit', compact('prodi', 'fakultas'));
    }

    // Proses untuk mengupdate data Prodi
    public function update(ProdiRequest $request, $id)
    {
        $prodi = ProgramStudi::findOrFail($id);
        $prodi->update($request->validated()); // Mengupdate data Prodi

        return redirect()->route('admin_akademik.prodi.index')->with('success', 'Data Prodi berhasil diperbarui!');
    }
     public function destroy($id)
    {
        $prodi = ProgramStudi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('admin_akademik.prodi.index')->with('success', 'Program Studi berhasil dihapus!');
    }
}

