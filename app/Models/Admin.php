<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang digunakan
    protected $table = 'admin';  

    // Tentukan kolom yang dapat diisi
    protected $fillable = ['id_user', 'id_admin', 'nama'];

    // Mengatur hubungan dengan tabel `users`
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
