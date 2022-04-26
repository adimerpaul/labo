<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $request;
//        return Laboratorio::create($request->all());
        $labo=new Laboratorio();
        $labo->tipomuestra=$request->tipomuestra==null?'':$request->tipomuestra;
        $labo->fechatoma=$request->fechatoma==null?'':$request->fechatoma;
        $labo->horatoma=$request->horatoma==null?'':$request->horatoma;
        $labo->d1=$request->d1==null?'':$request->d1;
        $labo->d2=$request->d2==null?'':$request->d2;
        $labo->d3=$request->d3==null?'':$request->d3;
        $labo->d4=$request->d4==null?'':$request->d4;
        $labo->d5=$request->d5==null?'':$request->d5;
        $labo->d6=$request->d6==null?'':$request->d6;
        $labo->d7=$request->d7==null?'':$request->d7;
        $labo->d8=$request->d8==null?'':$request->d8;
        $labo->d9=$request->d9==null?'':$request->d9;
        $labo->d10=$request->d10==null?'':$request->d10;
        $labo->d11=$request->d11==null?'':$request->d11;
        $labo->d12=$request->d12==null?'':$request->d12;
        $labo->d13=$request->d13==null?'':$request->d13;
        $labo->d14=$request->d14==null?'':$request->d14;
        $labo->d15=$request->d15==null?'':$request->d15;
        $labo->d16=$request->d16==null?'':$request->d16;
        $labo->d17=$request->d17==null?'':$request->d17;
        $labo->d18=$request->d18==null?'':$request->d18;
        $labo->d19=$request->d19==null?'':$request->d19;
        $labo->d20=$request->d20==null?'':$request->d20;
        $labo->d21=$request->d21==null?'':$request->d21;
        $labo->d22=$request->d22==null?'':$request->d22;
        $labo->d23=$request->d23==null?'':$request->d23;
        $labo->d24=$request->d24==null?'':$request->d24;
        $labo->d25=$request->d25==null?'':$request->d25;
        $labo->d26=$request->d26==null?'':$request->d26;
        $labo->d27=$request->d27==null?'':$request->d27;
        $labo->d28=$request->d28==null?'':$request->d28;
        $labo->d29=$request->d29==null?'':$request->d29;
        $labo->d30=$request->d30==null?'':$request->d30;
        $labo->d31=$request->d31==null?'':$request->d31;
        $labo->d32=$request->d32==null?'':$request->d32;
        $labo->d33=$request->d33==null?'':$request->d33;
        $labo->d34=$request->d34==null?'':$request->d34;
        $labo->d35=$request->d35==null?'':$request->d35;
        $labo->d36=$request->d36==null?'':$request->d36;
        $labo->d37=$request->d37==null?'':$request->d37;
        $labo->d38=$request->d38==null?'':$request->d38;
        $labo->d39=$request->d39==null?'':$request->d39;
        $labo->d40=$request->d40==null?'':$request->d40;
        $labo->d41=$request->d41==null?'':$request->d41;
        $labo->d42=$request->d42==null?'':$request->d42;
        $labo->d43=$request->d43==null?'':$request->d43;
        $labo->d44=$request->d44==null?'':$request->d44;
        $labo->d45=$request->d45==null?'':$request->d45;
        $labo->responsable=$request->responsable==null?'':$request->responsable;
        $labo->tipo_id=$request->tipo_id==null?'':$request->tipo_id;
        $labo->paciente_id=$request->paciente_id==null?'':$request->paciente_id;
        $labo->user_id=$request->user_id==null?'':$request->user_id;
        $labo->doctor_id=$request->doctor_id==null?'':$request->doctor_id;
        $labo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function show(Laboratorio $laboratorio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function edit(Laboratorio $laboratorio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Laboratorio $laboratorio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $laboratorio=Laboratorio::find($id);
        $laboratorio->delete();
    }
}
