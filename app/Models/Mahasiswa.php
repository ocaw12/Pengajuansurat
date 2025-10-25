<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    
    protected $table = 'mahasiswa';
    protected $fillable = [
        'user_id', 
        'nim', 
        'nama_lengkap', 
        'program_studi_id', 
        'angkatan'
    ];

    /**
     * Mendapatkan akun user dari mahasiswa.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan program studi dari mahasiswa.
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    /**
     * Mendapatkan semua pengajuan surat oleh mahasiswa ini.
     */
    public function pengajuanSurats()
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}
