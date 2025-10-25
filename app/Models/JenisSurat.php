<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;
    
    protected $table = 'jenis_surat';
    protected $fillable = [
        'kode_surat',
        'nama_surat',
        'kategori',
        'isi_template',
        'form_schema',
        'format_penomoran',
        'counter_nomor_urut',
        'counter_tahun',
    ];

    /**
     * Meng-casting atribut JSON.
     *
     * @var array
     */
    protected $casts = [
        'form_schema' => 'array',
    ];

    /**
     * Mendapatkan semua alur approval untuk jenis surat ini.
     */
    public function alurApprovals()
    {
        return $this->hasMany(AlurApproval::class)->orderBy('urutan');
    }

    /**
     * Mendapatkan semua pengajuan yang menggunakan jenis surat ini.
     */
    public function pengajuanSurats()
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}
