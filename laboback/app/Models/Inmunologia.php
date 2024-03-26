<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inmunologia extends Model
{
    use HasFactory;
    protected $fillable = ['tipomuestra','fechatoma','horatoma','fechaimp','responsable','solicitud','observacion','tipo_id','paciente_id','user_id','doctor_id'];
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
    public function tipo(){
        return $this->belongsTo(Tipo::class);
    }
//    public function antibioticos(){
//        return $this->hasMany(AntibioticoInmunologia::class);
//    }
    public function antibioticos(){
        return $this->belongsToMany(Antibiotico::class)->withPivot('resultado');
    }
}
