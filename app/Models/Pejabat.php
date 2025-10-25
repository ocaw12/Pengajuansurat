<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pejabat extends Model
{
    use HasFactory;
    
    protected $table = 'pejabat';
    protected $fillable = [
        'user_id',
        'nip_atau_nidn',
        'nama_lengkap',
        'tanda_tangan_path',
        'master_jabatan_id',
        'fakultas_id',
        'program_studi_id',
    ];

    /**
     * Mendapatkan akun user dari pejabat.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan jabatan master dari pejabat (cth: "Dekan").
     */
    public function masterJabatan()
    {
        return $this->belongsTo(MasterJabatan::class);
    }

    /**
     * Mendapatkan fakultas (jika scope-nya fakultas).
     */
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    /**
     * Mendapatkan program studi (jika scope-nya prodi).
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    /**
     * Mendapatkan semua history approval yang dilakukan pejabat ini.
     */
    public function approvalPejabats()
    {
        return $this->hasMany(ApprovalPejabat::class);
    }
}
