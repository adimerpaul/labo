<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cultivo;
use App\Models\AntibioticoCultivo;
use Illuminate\Support\Facades\DB;


class CultivoController extends Controller
{
    //

    public function listCultivo(Request $request ){
        return Cultivo::where('paciente_id',$request->id)
        ->whereDate('fechatoma','>=',$request->fecha)
        ->with('doctor')->with('tipo')->with('antibioticos')->get();
    }

    public function store(Request $request){
        //return $request;
        $cultivo=new Cultivo;
        $cultivo->tipomuestra= $request->tipomuestra==null?'':$request->tipomuestra;
        $cultivo->fechatoma= $request->fechatoma;
        $cultivo->horatoma= $request->horatoma;
        $cultivo->fechaimp= $request->fechaimp;
        $cultivo->responsable=  $request->responsable==null?'':$request->responsable;
        $cultivo->solicitud= $request->solicitud;
        $cultivo->examenDirecto= $request->examendircto==null?'':$request->examendircto;
        $cultivo->tincionGram= $request->tinciongram==null?'':$request->tinciongram;
        $cultivo->microorganizmo= $request->microorganizmo==null?'':$request->microorganizmo;
        $cultivo->observacion= $request->observacion==null?'':$request->observacion;
        $cultivo->tipo_id=$request->tipo_id;
        $cultivo->paciente_id=$request->paciente_id;
        $cultivo->user_id=$request->user_id;
        $cultivo->doctor_id=$request->doctor_id;
        $cultivo->save();

        foreach ($request->antibiograma as $value) {
            # code...
            $cultantib=new AntibioticoCultivo();
            $cultantib->cultivo_id= $cultivo->id;
            $cultantib->antibiotico_id= $value['id'];
            $cultantib->interpretacion= $value['interpretacion'];
            $cultantib->save();
        }

    }

    public function update($cultivo, Request $request){
        //return $request;
        $cultivo=Cultivo::find($request->id);
        $cultivo->tipomuestra= $request->tipomuestra==null?'':$request->tipomuestra;
        $cultivo->fechatoma= $request->fechatoma;
        $cultivo->horatoma= $request->horatoma;
        $cultivo->fechaimp= $request->fechaimp;
        $cultivo->responsable=  $request->responsable==null?'':$request->responsable;
        $cultivo->solicitud= $request->solicitud;
        $cultivo->examenDirecto= $request->examenDirecto==null?'':$request->examenDirecto;
        $cultivo->tincionGram= $request->tincionGram==null?'':$request->tincionGram;
        $cultivo->microorganizmo= $request->microorganizmo==null?'':$request->microorganizmo;
        $cultivo->observacion= $request->observacion==null?'':$request->observacion;
        $cultivo->tipo_id=$request->tipo_id;
        $cultivo->paciente_id=$request->paciente_id;
        $cultivo->user_id=$request->user_id;
        $cultivo->doctor_id=$request->doctor_id;
        $cultivo->save();

        DB::SELECT("DELETE from antibiotico_cultivo where cultivo_id=$cultivo->id");
        foreach ($request->antibiograma as $value) {
            # code...
            $cultantib=new AntibioticoCultivo();
            $cultantib->cultivo_id= $cultivo->id;
            $cultantib->antibiotico_id= $value['id'];
            $cultantib->interpretacion= $value['interpretacion'];
            $cultantib->save();
        }

    }



}
