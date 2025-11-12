<?php
// app/Http/Controllers/DownloadController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    // Preview file PDF di browser
    public function preview($fileName)
    {
        $fileName = basename($fileName);
        $path = 'surat_selesai/' . $fileName;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $file = Storage::disk('public')->get($path);
        $type = Storage::disk('public')->mimeType($path);

        // Tampilkan langsung di browser
        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    public function download($fileName)
    {
        // Pastikan hanya nama file-nya yang digunakan (tanpa path tambahan)
        $fileName = basename($fileName);

        // Tentukan path relatif terhadap disk public
        $path = 'surat_selesai/' . $fileName;

        // Cek apakah file ada di storage/app/public/surat_selesai/
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        // Unduh file
        return Storage::disk('public')->download($path);
    }
}
