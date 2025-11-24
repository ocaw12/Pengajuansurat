<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RiwayatController extends Controller{
    /**
     * Menampilkan riwayat pengajuan surat mahasiswa.
     */
    public function index(): View
    {
        $mahasiswaId = Auth::user()->mahasiswa->id;
        
        $pengajuan_surats = \App\Models\PengajuanSurat::where('mahasiswa_id', $mahasiswaId)
            ->with('jenisSurat') // Eager load relasi
            ->latest('tanggal_pengajuan') // Urutkan berdasarkan terbaru
            ->get();

 return view('mahasiswa.riwayat.index', compact('pengajuan_surats'));    }
}
