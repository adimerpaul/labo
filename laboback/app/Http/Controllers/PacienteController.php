<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Laboratorio;
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
    public function index(Request  $request)
    {

        $paginate = 10;
        $search = $request->search;
        $pacientes = Paciente::with('seguro')
            ->orderBy('id','DESC')
            ->whereRaw("CONCAT(nombre, ' ', paterno, ' ', materno) LIKE '%$search%'")
            ->orWhere('ci','LIKE','%'.$search.'%')
            ->paginate($paginate);
//            ->get();
        return $pacientes;
    }

    public function listLabo(Request $request){

        return Laboratorio::where('paciente_id',$request->id)
        ->whereDate('fechatoma','>=',$request->fecha)->with('doctor')->with('tipo')->get();
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
        $paciente->nro=$request->nro==null?'':$request->nro;
        $paciente->nombre=strtoupper($request->nombre);
        $paciente->paterno=strtoupper($request->paterno);
        $paciente->materno=strtoupper($request->materno);
        $paciente->edad=$request->edad;
        $paciente->fechanac=$request->fechanac;
        $paciente->sexo=$request->sexo;
        $paciente->celular=$request->celular;
        $paciente->seguro_id=$request->seguro;
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
        $paciente=Paciente::find($request->id);
        $paciente->ci=$request->ci;
        $paciente->nro=$request->nro==null?'':$request->nro;
        $paciente->nombre=strtoupper($request->nombre);
        $paciente->paterno=strtoupper($request->paterno);
        $paciente->materno=strtoupper($request->materno);
        $paciente->fechanac=$request->fechanac;
        $paciente->edad=$request->edad;
        $paciente->sexo=$request->sexo;
        $paciente->celular=$request->celular;
        $paciente->seguro_id=$request->seguro;
        $paciente->save();
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
            "SELECT f.id,tipomuestra,fechatoma,'hemograma' as formulario, doctors.celular as dcelular FROM hemogramas f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'orina' as formulario, doctors.celular as dcelular FROM orinas f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'sanguinia' as formulario, doctors.celular as dcelular FROM sanguinias f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'uretral' as formulario , doctors.celular as dcelular FROM uretrals f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'vaginal' as formulario , doctors.celular as dcelular FROM vaginals f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'hece' as formulario , doctors.celular as dcelular FROM heces f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'simple' as formulario , doctors.celular as dcelular FROM simples f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'seriado' as formulario , doctors.celular as dcelular FROM seriados f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'serologia' as formulario , doctors.celular as dcelular FROM serologias f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'labserologia' as formulario , doctors.celular as dcelular FROM labserologias f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'reserologia' as formulario , doctors.celular as dcelular FROM reserologias f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'ensayo' as formulario , doctors.celular as dcelular FROM ensayos f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id' union
            SELECT f.id,tipomuestra,fechatoma,'embarazo' as formulario , doctors.celular as dcelular FROM embarazos f inner join doctors on f.doctor_id=doctors.id WHERE paciente_id='$id'
            "
        );
        return $r1;
    }


    public function muestra(){
        $r1=DB::select(
            "SELECT tipomuestra,'hemograma' as formulario FROM hemogramas group by tipomuestra union
            SELECT tipomuestra,'orina' as formulario FROM orinas group by tipomuestra union
            SELECT tipomuestra,'sanguinia' as formulario FROM sanguinias group by tipomuestra union
            SELECT tipomuestra,'uretral' as formulario FROM uretrals group by tipomuestra union
            SELECT tipomuestra,'vaginal' as formulario FROM vaginals group by tipomuestra union
            SELECT tipomuestra,'hece' as formulario FROM heces group by tipomuestra union
            SELECT tipomuestra,'simple' as formulario FROM simples group by tipomuestra union
            SELECT tipomuestra,'seriado' as formulario FROM seriados group by tipomuestra union
            SELECT tipomuestra,'serologia' as formulario FROM serologias group by tipomuestra union
            SELECT tipomuestra,'labserologia' as formulario FROM labserologias group by tipomuestra union
            SELECT tipomuestra,'reserologia' as formulario FROM reserologias group by tipomuestra union
            SELECT tipomuestra,'ensayo' as formulario FROM ensayos group by tipomuestra union
            SELECT tipomuestra,'embarazo' as formulario FROM embarazos group by tipomuestra"
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
            case 'embarazo':
                $tabla='embarazos';
                break;
            default:
                # code...
                break;
        }
        return DB::table($tabla)->where('id',$request->id)->delete();
    }
    public function reporte(Request $request){
        /*$r1=DB::select(
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
            SELECT count(*) as total,'ensayo' as formulario FROM ensayos WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'  union
            SELECT count(*) as total,'embarazo' as formulario FROM embarazos WHERE date(fechatoma)>= '$request->ini' and date(fechatoma)<='$request->fin'            "
        );
        return $r1;*/

        return DB::SELECT("select t.nombre,COUNT(*) cantidad from laboratorios l inner JOIN tipos t on l.tipo_id=t.id where l.fechatoma>='$request->ini' and l.fechatoma<='$request->fin' group by t.nombre");
    }

    public function repseguro(Request $request){
        return DB::SELECT("select s.nombre,COUNT(*) as cantidad from pacientes p INNER JOIN seguros s on p.seguro_id=s.id inner JOIN laboratorios l on p.id=l.paciente_id where l.fechatoma>='$request->ini' and l.fechatoma<='$request->fin' group by s.nombre;");
    }

    public function valpaciente(Request $request){
        return DB::SELECT("SELECT * from pacientes p where p.nombre='".strtoupper($request->nombre)."' and p.paterno='".strtoupper($request->paterno)."' and p.materno='".strtoupper($request->materno)."'");

    }
}
