<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntibioticoInmunologia extends Model
{
    use HasFactory;
    protected $table='antibiotico_inmunologia';
    protected $fillable = ['inmunologia_id','antibiotico_id','resultado'];
}
