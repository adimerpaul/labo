<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ensayo extends Model
{
    use HasFactory;
    protected 
    $fillable=[
    'tipomuestra',
    'fechatoma',
    'horatoma',
    'd1',
    'd2',
    'd3',
    'd4',
    'd5',
    'd6',
    'd7',
    'd8',
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
