<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAkademik extends Model
{
    use HasFactory;

    protected $table = 'admin_akademik';

    protected $fillable = [
        'user_id',
        'nip_akademik',
        'nama_lengkap',
         'no_telepon',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
