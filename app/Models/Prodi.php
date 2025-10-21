<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';
    protected $primaryKey = 'id_prodi';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id_prodi', 'nama_prodi', 'email_prodi', 'id_fakultas'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_fakultas');
    }
    public function staff()
{
    return $this->hasMany(StaffJurusan::class, 'id_prodi');
}

}

