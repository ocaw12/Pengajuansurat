<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini
use Illuminate\Database\Eloquent\Relations\HasOne; // Tambahkan ini
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Hapus 'name', tambahkan 'role_id' jika belum
        'email',
        'password',
        'role_id',
        'is_active',
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
            'is_active' => 'boolean'
        ];
    }

    /**
     * Relasi ke Role: Satu user memiliki satu role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relasi ke Mahasiswa: Satu user bisa jadi satu mahasiswa.
     */
    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }

    /**
     * Relasi ke Pejabat: Satu user bisa jadi satu pejabat.
     */
    public function pejabat(): HasOne
    {
        return $this->hasOne(Pejabat::class, 'user_id');
    }

    /**
     * Relasi ke AdminStaff: Satu user bisa jadi satu admin staff.
     */
    public function adminStaff(): HasOne
    {
        return $this->hasOne(AdminStaff::class, 'user_id');
    }

    /**
     * Relasi ke AdminAkademik: Satu user bisa jadi satu admin akademik.
     */
    public function adminAkademik(): HasOne
    {
        return $this->hasOne(AdminAkademik::class, 'user_id');
    }
}

