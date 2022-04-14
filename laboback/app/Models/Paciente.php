<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;
    protected $fillable=[
        'ci',
    'nombre',
    'paterno',
    'materno',
    'edad',
    'fechanac','sexo','celular'];
    protected $hidden = ["created_at", "updated_at"];

    public function age()
    {
         if($this->attributes['fechanac']!=null) return Carbon::parse($this->attributes['fechanac'])->age;
         else
         return $this->attributes['edad'];
    }
    public function seguro(){
        return $this->belongsTo(Seguro::class);
    }
    public function laboratorios(){
        return $this->hasMany(Laboratorio::class)->with('tipo');
    }
}
