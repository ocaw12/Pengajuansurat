<?php
namespace App\Http\Controllers\AdminAkademik;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class AdminFakultasController extends Controller
{
    // Menampilkan data fakultas
    public function index()
    {
        $fakultas = Fakultas::paginate(10);
        return view('admin_akademik.fakultas.index', compact('fakultas'));
    }

    // Menampilkan form tambah fakultas
    public function create()
    {
        return view('admin_akademik.fakultas.create');
    }

    // Menyimpan data fakultas
   public function store(Request $request)
{
    $request->validate([
        'nama_fakultas' => 'required|string|max:255|unique:fakultas,nama_fakultas',
        'kode_fakultas' => 'required|string|max:10|unique:fakultas,kode_fakultas',
    ]);

    Fakultas::create([
        'nama_fakultas' => $request->nama_fakultas,
        'kode_fakultas' => $request->kode_fakultas,
    ]);

    return redirect()
        ->route('admin_akademik.fakultas.index')
        ->with('success', 'Fakultas berhasil ditambahkan.');
}

    // Menampilkan form edit fakultas
    public function edit(Fakultas $fakultas)
    {
        return view('admin_akademik.fakultas.edit', compact('fakultas'));
    }

    // Memperbarui data fakultas
    public function update(Request $request, Fakultas $fakultas)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:255',
            'kode_fakultas' => 'required|string|max:10',
        ]);

        $fakultas->update($request->all());
        return redirect()->route('admin_akademik.fakultas.index');
    }

    // Menghapus fakultas
   public function destroy(Fakultas $fakultas)
{
    $fakultas->delete();

    return redirect()->route('admin_akademik.fakultas.index')
        ->with('success', 'Fakultas berhasil dihapus.');
}

}
