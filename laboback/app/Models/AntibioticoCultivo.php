<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntibioticoCultivo extends Model
{
    use HasFactory;
    protected $fillable=[
        'cultivo_id',
        'antibiotico_id',
        'interpretacion',
    ];
}
