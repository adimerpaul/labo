<?php

namespace App\Http\Controllers;

use App\Models\Sanguinea;
use App\Http\Requests\StoreSanguineaRequest;
use App\Http\Requests\UpdateSanguineaRequest;
use Illuminate\Http\Request;
use App\Models\AntibioticoSanguinea;
use Illuminate\Support\Facades\DB;

class SanguineaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function listSanguinea(Request $request ){
        return Sanguinea::where('paciente_id',$request->id)
            ->whereDate('fechatoma','>=',$request->fecha)
            ->with('doctor')->with('tipo')->with('antibioticos')->get();
    }

    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreSanguineaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $sanguinea=new Sanguinea();
        $sanguinea->tipomuestra= $request->tipomuestra==null?'':$request->tipomuestra;
        $sanguinea->fechatoma= $request->fechatoma;
        $sanguinea->horatoma= $request->horatoma;
        $sanguinea->fechaimp= $request->fechaimp;
        $sanguinea->responsable=  $request->responsable==null?'':$request->responsable;
        $sanguinea->solicitud= $request->solicitud;
        $sanguinea->observacion= $request->observacion==null?'':$request->observacion;
        $sanguinea->tipo_id=$request->tipo_id;
        $sanguinea->paciente_id=$request->paciente_id;
        $sanguinea->user_id=$request->user_id;
        $sanguinea->doctor_id=$request->doctor_id;
        $sanguinea->save();

        foreach ($request->antibiograma as $value) {
            $cultantib=new AntibioticoSanguinea();
            $cultantib->sanguinea_id= $sanguinea->id;
            $cultantib->antibiotico_id= $value['id'];
            $cultantib->resultado= $value['resultado'];
            $cultantib->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sanguinea  $sanguinea
     * @return \Illuminate\Http\Response
     */
    public function show(Sanguinea $sanguinea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sanguinea  $sanguinea
     * @return \Illuminate\Http\Response
     */
    public function edit(Sanguinea $sanguinea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSanguineaRequest  $request
     * @param  \App\Models\Sanguinea  $sanguinea
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSanguineaRequest $request, Sanguinea $sanguinea)
    {
        //
        $sanguinea=Sanguinea::find($request->id);
        $sanguinea->tipomuestra= $request->tipomuestra==null?'':$request->tipomuestra;
        $sanguinea->fechatoma= $request->fechatoma;
        $sanguinea->horatoma= $request->horatoma;
        $sanguinea->fechaimp= $request->fechaimp;
        $sanguinea->responsable=  $request->responsable==null?'':$request->responsable;
        $sanguinea->solicitud= $request->solicitud;
        $sanguinea->observacion= $request->observacion==null?'':$request->observacion;
        $sanguinea->tipo_id=$request->tipo_id;
        $sanguinea->paciente_id=$request->paciente_id;
        $sanguinea->user_id=$request->user_id;
        $sanguinea->doctor_id=$request->doctor_id;
        $sanguinea->save();

        DB::SELECT("DELETE from antibiotico_sanguinea where sanguinea_id=$sanguinea->id ");

        foreach ($request->antibiograma as $value) {
            $cultantib=new AntibioticoSanguinea();
            $cultantib->sanguinea_id= $sanguinea->id;
            $cultantib->antibiotico_id= $value['id'];
            $cultantib->resultado= $value['resultado'];
            $cultantib->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sanguinea  $sanguinea
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        //
        $sanguinea = Sanguinea::find($id);

        DB::SELECT("DELETE from antibiotico_sanguinea where sanguinea_id=$id ");
        $sanguinea->delete();
    }
}
