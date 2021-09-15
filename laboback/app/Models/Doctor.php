<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable=['nombre',
    'ci',
    'nombre',
    'paterno',
    'materno',
    'especialidad',
    'matricula',
    'celular',
    'activo'
    ];
}
