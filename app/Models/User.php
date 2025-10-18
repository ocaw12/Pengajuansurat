<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_user','name','email','password','id_role'];
    protected $hidden = ['password','remember_token'];

    public function role() {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }
}
