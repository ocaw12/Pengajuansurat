<?php
namespace App\Http\Controllers;

use App\Models\ApprovalPejabat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    /**
     * Menampilkan halaman verifikasi berdasarkan kode verifikasi
     */
    public function show($kode_verifikasi)
    {
        // Mencari data approval berdasarkan kode verifikasi dari QR Code
        $approval = \App\Models\ApprovalPejabat::where('kode_verifikasi', $kode_verifikasi)->firstOrFail();
        
        // Memuat data pengajuan surat beserta SEMUA approval-nya
        $pengajuan = $approval->pengajuanSurat()->with([
            'mahasiswa', 
            'jenisSurat', 
            'approvalPejabats.pejabat.masterJabatan' // <-- Pastikan relasi ini di-load
        ])->firstOrFail();

        return view('verifikasi.show', compact('pengajuan'));
    }
}
