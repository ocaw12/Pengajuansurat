<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminStaff extends Model
{
    use HasFactory;
    
    protected $table = 'admin_staff';
    protected $fillable = [
        'user_id',
        'nip_staff',
        'nama_lengkap',
        'program_studi_id',
    ];

    /**
     * Mendapatkan akun user dari staff.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan program studi yang diurus oleh staff ini.
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    /**
     * Mendapatkan semua pengajuan surat yang divalidasi oleh staff ini.
     */
    public function pengajuanSuratsValidated()
    {
        return $this->hasMany(PengajuanSurat::class, 'admin_validator_id');
    }
}
