<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffJurusan extends Model
{
    protected $table = 'staff_jurusan';
    protected $primaryKey = 'id_staff';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id_staff', 'nama_staff', 'id_prodi', 'id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function prodi()
{
    return $this->belongsTo(Prodi::class, 'id_prodi');
}


}
