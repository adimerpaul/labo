<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retiro extends Model
{
    use HasFactory;
    protected $fillable=[
        'fecharetiro',
        'egreso',
        'observacion',
        'reactivo_id',
        'inventario_id',
        'user_id',
    ];
}
