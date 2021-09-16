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
    'fechanac','sexo','celular'];
    protected $hidden = ["created_at", "updated_at"];

    public function age()
    {
        return Carbon::parse($this->attributes['fechanac'])->age;
    }
}
