<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role_id', // Tambahkan role_id
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // === RELASI ===

    /**
     * Mendapatkan role (peran) dari user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Mendapatkan profil mahasiswa jika user ini adalah mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    /**
     * Mendapatkan profil pejabat jika user ini adalah pejabat.
     */
    public function pejabat()
    {
        return $this->hasOne(Pejabat::class);
    }

    /**
     * Mendapatkan profil staff jurusan jika user ini adalah staff jurusan.
     */
    public function adminStaff()
    {
        return $this->hasOne(AdminStaff::class);
    }
}
