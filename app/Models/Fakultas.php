<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;
    
    protected $table = 'fakultas';
    protected $fillable = ['nama_fakultas'];

    /**
     * Mendapatkan semua program studi di bawah fakultas ini.
     */
    public function programStudis()
    {
        return $this->hasMany(ProgramStudi::class);
    }

    /**
     * Mendapatkan semua pejabat (cth: Dekan) yang terkait dengan fakultas ini.
     */
    public function pejabats()
    {
        return $this->hasMany(Pejabat::class);
    }
}
