<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    use HasFactory;
    protected $fillable=[
        'tipomuestra',
        'fechatoma',
        'horatoma',
        'fechaimp',
        'responsable',
        'solicitud',
        'examenDirecto',
        'tincionGram',
        'microorganizmo',
        'observacion',

        'tipo_id',
        'paciente_id',
        'user_id',
        'doctor_id',
    ];

    protected $hidden = ["created_at", "updated_at"];

    public function tipo(){
        return $this->belongsTo(Tipo::class);
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function paciente(){
        return $this->belongsTo(Paciente::class);
    }

    public function antibioticos(){
        return $this->belongsToMany(Antibiotico::class)->withPivot('interpretacion');
    }
}
