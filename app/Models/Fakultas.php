<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas';
    protected $primaryKey = 'id_fakultas';
    public $incrementing = false; // karena id bukan auto increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_fakultas',
        'nama_fakultas',
    ];
}
