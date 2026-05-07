<?php

namespace App\Http\Controllers\AdminAkademik;
use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\ProgramStudi;
use App\Http\Requests\AdminAkademik\StoreMahasiswaRequest;
use App\Http\Requests\AdminAkademik\UpdateMahasiswaRequest; // Gunakan request khusus untuk update
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MahasiswaController extends Controller
{
    // Menampilkan form untuk input data mahasiswa
    public function create()
    {
        $program_studis = ProgramStudi::all(); // Mengambil semua data program studi untuk dropdown
        return view('admin_akademik.mahasiswa.create', compact('program_studis'));
    }

    // Menyimpan data mahasiswa dan user
    public function store(StoreMahasiswaRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Menyimpan data ke tabel users terlebih dahulu (karena user_id diperlukan di mahasiswa)
            $user = User::create([
                'email'     => $request->email,
                'password'  => Hash::make($request->nim), // Password sama dengan NIM
                'role_id'   => 1, // Pastikan ini adalah ID role untuk mahasiswa
                'is_active' => $request->boolean('is_active'), // <--- baru
            ]);

            // Menyimpan data ke tabel mahasiswa
            $mahasiswa = Mahasiswa::create([
                'nim'              => $request->nim,
                'nama_lengkap'     => $request->nama_lengkap,
                'tempat_lahir'     => $request->tempat_lahir,
                'tanggal_lahir'    => $request->tanggal_lahir,
                'alamat'           => $request->alamat,
                'jenis_kelamin'    => $request->jenis_kelamin,
                'angkatan'         => $request->angkatan,
                'program_studi_id' => $request->program_studi_id,
                'no_telepon'       => $request->no_telepon,
                'user_id'          => $user->id, // Menyimpan ID user yang baru saja dibuat
            ]);
        });

        return redirect()->route('admin_akademik.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }

    // Menampilkan daftar mahasiswa
    public function index()
    {
        // Mengambil semua data mahasiswa dengan relasi program studi dan user
        $mahasiswas = Mahasiswa::with('programStudi', 'user')->paginate(10);
        return view('admin_akademik.mahasiswa.index', compact('mahasiswas'));
    }

    // Menampilkan form edit untuk data mahasiswa
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with('programStudi', 'user')->findOrFail($id); // sekalian load user
        $program_studis = ProgramStudi::all(); // Ambil data program studi untuk dropdown
        return view('admin_akademik.mahasiswa.edit', compact('mahasiswa', 'program_studis'));
    }

    // Update data mahasiswa dan user
    public function update(UpdateMahasiswaRequest $request, $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        DB::transaction(function () use ($request, $mahasiswa) {
            // Update data mahasiswa
            $mahasiswa->update([
                'nim'              => $request->nim,
                'nama_lengkap'     => $request->nama_lengkap,
                'tempat_lahir'     => $request->tempat_lahir,
                'tanggal_lahir'    => $request->tanggal_lahir,
                'alamat'           => $request->alamat,
                'jenis_kelamin'    => $request->jenis_kelamin,
                'angkatan'         => $request->angkatan,
                'program_studi_id' => $request->program_studi_id,
                'no_telepon'       => $request->no_telepon,
            ]);

            // Update data user yang terkait
            $mahasiswa->user->update([
                'email'     => $request->email,
                'is_active' => $request->boolean('is_active'), // <--- baru
            ]);

            // Optional: Update password jika perlu
            if ($request->filled('password')) {
                $mahasiswa->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        });

        return redirect()->route('admin_akademik.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui!');
    }
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv|max:2048'
    ]);

    try {

        Excel::import(new MahasiswaImport, $request->file('file'));

        return redirect()->route('admin_akademik.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diimport!');

    } catch (\Exception $e) {

        return redirect()->back()
            ->with('error', 'Import gagal. Pastikan format file benar.');
    }
}
    // Menghapus data mahasiswa
    public function destroy($id)
    {
        // Mencari data mahasiswa berdasarkan ID
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        // Menghapus data user yang terkait
        $mahasiswa->user->delete();

        // Menghapus data mahasiswa
        $mahasiswa->delete();

        // Redirect ke halaman daftar mahasiswa dengan pesan sukses
        return redirect()->route('admin_akademik.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus!');
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::with(['programStudi', 'user'])->findOrFail($id);
        return view('admin_akademik.mahasiswa.show', compact('mahasiswa'));
    }
    public function downloadTemplate()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $headers = [
        'nim',
        'email',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'jenis_kelamin',
        'no_telepon',
        'program_studi_id',
        'angkatan'
    ];

    // Isi header
    $column = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($column . '1', $header);
        $column++;
    }

    // Contoh data
    $sheet->setCellValue('A2', '24010410012');
    $sheet->setCellValue('B2', 'budisantoso@up45.ac.id');
    $sheet->setCellValue('C2', 'Budi Santoso');
    $sheet->setCellValue('D2', 'Sleman');
    $sheet->setCellValue('E2', '2004-01-01');
    $sheet->setCellValue('F2', 'Sleman, Yogyakarta');
    $sheet->setCellValue('G2', 'Laki_laki');
    $sheet->setCellValue('H2', '081234567890');
    $sheet->setCellValue('I2', '4');
    $sheet->setCellValue('J2', '2024');

    // Auto size kolom
    foreach (range('A', 'J') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, 'template_mahasiswa.xlsx');
}
}
