<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanSurat extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'pengajuan_surat';

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'mahasiswa_id',
        'jenis_surat_id',
        'admin_validator_id',
        'nomor_surat',
        'tanggal_pengajuan',
        'keperluan',
        'data_pendukung',
        'status_pengajuan',
        'file_hasil_pdf',
        'catatan_admin',
        'catatan_revisi',
        'metode_pengambilan',
        'tanggal_diambil',
    ];

    /**
     * Tipe data cast untuk atribut.
     *
     * @var array
     */
    protected $casts = [
        'data_pendukung' => 'array',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_diambil' => 'datetime',
    ];

    /**
     * Relasi ke Mahasiswa: Satu pengajuan dimiliki oleh satu mahasiswa.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke JenisSurat: Satu pengajuan memiliki satu jenis surat.
     */
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    /**
     * Relasi ke AdminStaff (Validator): Satu pengajuan divalidasi oleh satu admin staff.
     * INI ADALAH FUNGSI YANG HILANG/ERROR
     */
    public function adminValidator(): BelongsTo
    {
        // 'admin_validator_id' adalah foreign key di tabel ini
        return $this->belongsTo(AdminStaff::class, 'admin_validator_id');
    }

    /**
     * Relasi ke ApprovalPejabat: Satu pengajuan memiliki banyak approval pejabat.
     */
    public function approvalPejabats(): HasMany
    {
        return $this->hasMany(ApprovalPejabat::class, 'pengajuan_surat_id');
    }
}

