<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalPejabat extends Model
{
    use HasFactory;
    
    protected $table = 'approval_pejabat';
    protected $fillable = [
        'pengajuan_surat_id',
        'pejabat_id',
        'urutan_approval',
        'status_approval',
        'tanggal_approval',
        'catatan_pejabat',
    ];

    /**
     * Meng-casting atribut tanggal.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_approval' => 'datetime',
    ];

    /**
     * Mendapatkan data pengajuan surat terkait.
     */
    public function pengajuanSurat()
    {
        return $this->belongsTo(PengajuanSurat::class);
    }

    /**
     * Mendapatkan data pejabat yang melakukan approval.
     */
    public function pejabat()
    {
        return $this->belongsTo(Pejabat::class);
    }
}
