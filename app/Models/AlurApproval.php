<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlurApproval extends Model
{
    use HasFactory;
    
    protected $table = 'alur_approval';
    protected $fillable = [
        'jenis_surat_id',
        'urutan',
        'master_jabatan_id',
        'scope',
    ];

    /**
     * Mendapatkan jenis surat dari alur ini.
     */
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    /**
     * Mendapatkan jabatan yang dibutuhkan oleh alur ini.
     */
    public function masterJabatan()
    {
        return $this->belongsTo(MasterJabatan::class);
    }
}
