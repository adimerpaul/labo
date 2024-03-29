<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    use HasFactory;
    protected $fillable=[
        'tipomuestra',
        'fechatoma',
        'horatoma',
        'fechaimp',
        'd1',
        'd2',
        'd3',
        'd4',
        'd5',
        'd6',
        'd7',
        'd8',
        'd9',
        'd10',
        'd11',
        'd12',
        'd13',
        'd14',
        'd15',
        'd16',
        'd17',
        'd18',
        'd19',
        'd20',
        'd21',
        'd22',
        'd23',
        'd24',
        'd25',
        'd26',
        'd27',
        'd28',
        'd29',
        'd30',
        'd31',
        'd32',
        'd33',
        'd34',
        'd35',
        'd36',
        'd37',
        'd38',
        'd39',
        'd40',
        'd41',
        'd42',
        'd43',
        'd44',
        'd45',
        'solicitud',
        'imagen',
        'responsable',
        'tipo_id',
        'paciente_id',
        'user_id',
        'doctor_id',
    ];
    public function tipo(){
        return $this->belongsTo(Tipo::class);
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function paciente(){
        return $this->belongsTo(Paciente::class);
    }
}
