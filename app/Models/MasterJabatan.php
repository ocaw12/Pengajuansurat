<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJabatan extends Model
{
    use HasFactory;
    
    protected $table = 'master_jabatan';
    protected $fillable = ['nama_jabatan'];

    /**
     * Mendapatkan semua pejabat yang memegang jabatan ini.
     */
    public function pejabats()
    {
        return $this->hasMany(Pejabat::class);
    }

    /**
     * Mendapatkan semua alur approval yang membutuhkan jabatan ini.
     */
    public function alurApprovals()
    {
        return $this->hasMany(AlurApproval::class);
    }
}
