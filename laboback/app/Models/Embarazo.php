<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Embarazo extends Model
{
    use HasFactory;
    protected 
    $fillable=[
    'tipomuestra',
    'fechatoma',
    'horatoma',
    'd1',
    'fum',
    'obs',
    'responsable',
    'doctor_id',
    'paciente_id',
    'user_id',];

    protected $hidden = ["created_at", "updated_at"];
    public function paciente(){
        return $this->belongsTo(Paciente::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
}
