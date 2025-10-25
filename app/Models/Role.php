<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'roles'; // Sesuai ERD

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_role',
    ];

    /**
     * Mendapatkan semua user yang memiliki role ini.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
