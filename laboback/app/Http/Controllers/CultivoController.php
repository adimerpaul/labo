<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CultivoController extends Controller
{
    //

    public function store(Request $request){
        return $request;
        $cultivo=new Cultivo;
        $cultivo->tipomuestra= $request->tipomuestra;
        $cultivo->fechatoma= $request->fechatoma;
        $cultivo->horatoma= $request->horatoma;
        $cultivo->fechaimp= $request->fechaimp;
        $cultivo->responsable= $request->responsable;
        $cultivo->solicitud= $request->solicitud;        
        $cultivo->tipoMuestra=$request->tipoMuestra;
        $cultivo->examenDirecto=$request->examenDirecto;
        $cultivo->tincionGram=$request->tincionGram;
        $cultivo->microorganizmo=$request->microorganizmo;
        $cultivo->observacion=$request->observacion;
        $cultivo->tipo_id=$request->tipo_id;
        $cultivo->paciente_id=$request->paciente_id;
        $cultivo->user_id=$request->user_id;
        $cultivo->doctor_id=$request->doctor_id;
        $cultivo->save();

        foreach ($request->detalle as $value) {
            # code...
        }

    }
}
