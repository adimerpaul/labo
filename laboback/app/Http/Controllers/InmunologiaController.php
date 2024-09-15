<?php

namespace App\Http\Controllers;

use App\Models\AntibioticoCultivo;
use App\Models\Cultivo;
use App\Models\Inmunologia;
use App\Models\AntibioticoInmunologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InmunologiaController extends Controller
{
    public function listImmunologia(Request $request ){
        return Inmunologia::where('paciente_id',$request->id)
            ->whereDate('fechatoma','>=',$request->fecha)
            ->with('doctor')->with('tipo')->with('antibioticos')->get();
    }
    
    public function store(Request $request){
        //return $request;
        $inmunologia=new Inmunologia();
        $inmunologia->tipomuestra= $request->tipomuestra==null?'':$request->tipomuestra;
        $inmunologia->fechatoma= $request->fechatoma;
        $inmunologia->horatoma= $request->horatoma;
        $inmunologia->fechaimp= $request->fechaimp;
        $inmunologia->responsable=  $request->responsable==null?'':$request->responsable;
        $inmunologia->solicitud= $request->solicitud;
//        $inmunologia->examenDirecto= $request->examendircto==null?'':$request->examendircto;
//        $inmunologia->tincionGram= $request->tinciongram==null?'':$request->tinciongram;
//        $inmunologia->microorganizmo= $request->microorganizmo==null?'':$request->microorganizmo;
        $inmunologia->observacion= $request->observacion==null?'':$request->observacion;
        $inmunologia->tipo_id=$request->tipo_id;
        $inmunologia->paciente_id=$request->paciente_id;
        $inmunologia->user_id=$request->user_id;
        $inmunologia->doctor_id=$request->doctor_id;
        $inmunologia->save();

        foreach ($request->antibiograma as $value) {
            $cultantib=new AntibioticoInmunologia();
            $cultantib->inmunologia_id= $inmunologia->id;
            $cultantib->antibiotico_id= $value['id'];
            $cultantib->resultado= $value['resultado'];
            $cultantib->save();
        }

    }
    public function update(Inmunologia $inmunologia,Request $request){
        //return $request;
        $inmunologia=Inmunologia::find($request->id);
        $inmunologia->tipomuestra= $request->tipomuestra==null?'':$request->tipomuestra;
        $inmunologia->fechatoma= $request->fechatoma;
        $inmunologia->horatoma= $request->horatoma;
        $inmunologia->fechaimp= $request->fechaimp;
        $inmunologia->responsable=  $request->responsable==null?'':$request->responsable;
        $inmunologia->solicitud= $request->solicitud;
//        $inmunologia->examenDirecto= $request->examendircto==null?'':$request->examendircto;
//        $inmunologia->tincionGram= $request->tinciongram==null?'':$request->tinciongram;
//        $inmunologia->microorganizmo= $request->microorganizmo==null?'':$request->microorganizmo;
        $inmunologia->observacion= $request->observacion==null?'':$request->observacion;
        $inmunologia->tipo_id=$request->tipo_id;
        $inmunologia->paciente_id=$request->paciente_id;
        $inmunologia->user_id=$request->user_id;
        $inmunologia->doctor_id=$request->doctor_id;
        $inmunologia->save();

        DB::SELECT("DELETE from antibiotico_inmunologia where inmunologia_id=$inmunologia->id ");

        foreach ($request->antibiograma as $value) {
            $cultantib=new AntibioticoInmunologia();
            $cultantib->inmunologia_id= $inmunologia->id;
            $cultantib->antibiotico_id= $value['id'];
            $cultantib->resultado= $value['resultado'];
            $cultantib->save();
        }

    }

    public function destroy($id){
        $inmunologia = Inmunologia::find($id);

        DB::SELECT("DELETE from antibiotico_inmunologia where inmunologia_id=$id ");
        $inmunologia->delete();

    }
}
