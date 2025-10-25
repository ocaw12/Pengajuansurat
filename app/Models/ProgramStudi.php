<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;
    
    protected $table = 'program_studi';
    protected $fillable = ['nama_prodi', 'kode_prodi', 'fakultas_id'];

    /**
     * Mendapatkan fakultas dari program studi.
     */
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    /**
     * Mendapatkan semua mahasiswa di program studi ini.
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    /**
     * Mendapatkan semua pejabat (cth: Kaprodi) yang terkait dengan prodi ini.
     */
    public function pejabats()
    {
        return $this->hasMany(Pejabat::class);
    }

    /**
     * Mendapatkan semua staff jurusan yang terkait dengan prodi ini.
     */
    public function adminStaffs()
    {
        return $this->hasMany(AdminStaff::class);
    }
}
