<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antibiotico extends Model
{
    use HasFactory;
    protected $fillable=[
        'tipomuestra',
        'fechatoma',
        'horatoma',
        'fechaimp',
        'responsable',
        'solicitud',
        
        'tipoMuestra',
        'examenDirecto',
        'tincionGram',
        'microorganizmo',
        'observacion',
        'tipo_id',
        'paciente_id',
        'user_id',
        'doctor_id',
    ];
}
