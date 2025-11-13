<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable; // <-- Tambahkan ini


class Mahasiswa extends Model
{
    use HasFactory, Notifiable; // <-- Tambahkan Notifiable
    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id',
        'nim',
        'nama_lengkap',
        'program_studi_id',
        'angkatan',
        // Kolom detail baru
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'jenis_kelamin',
        'no_telepon',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date', // Cast ke tipe date
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    public function pengajuanSurats(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class, 'mahasiswa_id');
    }

    public function routeNotificationForFonnte($notification)
    {
        if (empty($this->no_telepon)) {
            return null;
        }

        // Hapus karakter non-numerik
        $nomor = preg_replace('/[^0-9]/', '', $this->no_telepon);

        // Ubah awalan 0 menjadi 62
        if (str_starts_with($nomor, '0')) {
            return '62' . substr($nomor, 1);
        }
        
        // Jika sudah 62, langsung kembalikan
        if (str_starts_with($nomor, '62')) {
            return $nomor;
        }

        // Kembalikan null jika format tidak dikenal
        return null;
    }
}

