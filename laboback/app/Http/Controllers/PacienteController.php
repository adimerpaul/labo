<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Paciente::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $paciente=new Paciente;
        $paciente->ci=$request->ci;
        $paciente->nombre=strtoupper($request->nombre);
        $paciente->paterno=strtoupper($request->paterno);
        $paciente->materno=strtoupper($request->materno);
        $paciente->fechanac=$request->fechanac;
        $paciente->sexo=$request->sexo;
        $paciente->celular=$request->celular;
        $paciente->save();
        return $paciente;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function edit(Paciente $paciente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Paciente $paciente)
    {
        $paciente->update($request->all());
        return $paciente;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Paciente::find($id)->delete();
    }

    public function historialform($id){
        $r1=DB::select(
            "SELECT id,tipomuestra,fechatoma,'hemograma' as formulario FROM hemogramas WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'orina' as formulario FROM orinas WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'sanguinia' as formulario FROM sanguinias WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'uretral' as formulario FROM uretrals WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'vaginal' as formulario FROM vaginals WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'hece' as formulario FROM heces WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'simple' as formulario FROM simples WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'seriado' as formulario FROM seriados WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'serologia' as formulario FROM serologias WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'labserologia' as formulario FROM labserologias WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'reserologia' as formulario FROM reserologias WHERE paciente_id='$id' union
            SELECT id,tipomuestra,fechatoma,'ensayo' as formulario FROM ensayos WHERE paciente_id='$id' "
        );
        return $r1;
    }


    public function muestra(){
        $r1=DB::select(
            "SELECT id,tipomuestra,fechatoma,'hemograma' as formulario FROM hemogramas union
            SELECT id,tipomuestra,fechatoma,'orina' as formulario FROM orinas union
            SELECT id,tipomuestra,fechatoma,'sanguinia' as formulario FROM sanguinias union
            SELECT id,tipomuestra,fechatoma,'uretral' as formulario FROM uretrals union
            SELECT id,tipomuestra,fechatoma,'vaginal' as formulario FROM vaginals union
            SELECT id,tipomuestra,fechatoma,'hece' as formulario FROM heces union
            SELECT id,tipomuestra,fechatoma,'simple' as formulario FROM simples union
            SELECT id,tipomuestra,fechatoma,'seriado' as formulario FROM seriados union
            SELECT id,tipomuestra,fechatoma,'serologia' as formulario FROM serologias union
            SELECT id,tipomuestra,fechatoma,'labserologia' as formulario FROM labserologias union
            SELECT id,tipomuestra,fechatoma,'reserologia' as formulario FROM reserologias union
            SELECT id,tipomuestra,fechatoma,'ensayo' as formulario FROM ensayos "
        );
        return $r1;
    }


    public function eliminar(Request $request){
        switch ($request->formulario) {
            case 'hemograma':
                $tabla='hemogramas';
                break;
            case 'orina':
                $tabla='orinas';
                break;
            case 'sanguinia':
                $tabla='sanguinias';
                break;
            case 'uretral':
                $tabla='uretrals';
                break;
            case 'vaginal':
                $tabla='vaginals';
                break;
            case 'hece':
                $tabla='heces';
                break;

            case 'simple':
                $tabla='simples';
                break;
            case 'seriado':
                $tabla='seriados';
                break;
            case 'serologia':
                $tabla='serologias';
                break;
            case 'labserologia':
                $tabla='labserologias';
                break;
            case 'reserologia':
                $tabla='reserologias';
                break;
            case 'ensayo':
                $tabla='ensayos';
                break;
            default:
                # code...
                break;
        }
        return DB::table($tabla)->where('id',$request->id)->delete();
    }

    public function reporte(Request $request){
        $r1=DB::select(
            "SELECT count(*) as total,'hemograma' as formulario FROM hemogramas WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin' union
            SELECT count(*) as total,'orina' as formulario FROM orinas WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'sanguinia' as formulario FROM sanguinias WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'uretral' as formulario FROM uretrals WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'vaginal' as formulario FROM vaginals WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'hece' as formulario FROM heces WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'simple' as formulario FROM simples WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'seriado' as formulario FROM seriados WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'serologia' as formulario FROM serologias WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'labserologia' as formulario FROM labserologias WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'reserologia' as formulario FROM reserologias WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'ensayo' as formulario FROM ensayos WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  "
        );
        return $r1;
    }


}
