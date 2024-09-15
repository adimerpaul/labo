<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntibioticoSanguinea extends Model
{
    use HasFactory;
    protected $table='antibiotico_sanguinea';
    protected $fillable = ['sanguinea_id','antibiotico_id','resultado'];
}
