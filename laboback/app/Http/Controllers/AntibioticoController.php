<?php

namespace App\Http\Controllers;

use App\Models\Antibiotico;
use Illuminate\Http\Request;

class AntibioticoController extends Controller{
    public function index(){
        return Antibiotico::orderBy('id','desc')->get();
    }
    public function store(Request $request){
        $antibiotico = new Antibiotico();
        $antibiotico->nombre = $request->nombre;
        $antibiotico->unidad = $request->unidad;
        $antibiotico->rangoMin = $request->rangoMin;
        $antibiotico->rangoMax = $request->rangoMax;
        $antibiotico->referencia = $request->referencia;
        $antibiotico->tipo = $request->tipo;
        $antibiotico->descripcion = $request->descripcion;
        $antibiotico->save();
        return $antibiotico;

    }
    public function show($id){
        return Antibiotico::find($id);
    }
    public function update(Request $request, $id){
        $antibiotico = Antibiotico::find($id);
        $antibiotico->nombre = $request->nombre;
        $antibiotico->unidad = $request->unidad;
        $antibiotico->rangoMin = $request->rangoMin;
        $antibiotico->rangoMax = $request->rangoMax;
        $antibiotico->referencia = $request->referencia;
        $antibiotico->tipo = $request->tipo;
        $antibiotico->descripcion = $request->descripcion;
        $antibiotico->save();
        return $antibiotico;
    }
    public function destroy($id){
        $antibiotico = Antibiotico::find($id);
        $antibiotico->delete();
        return $antibiotico;
    }
    public function listAntib($tipo){
        return Antibiotico::where('tipo',$tipo)->get();
    }
}
