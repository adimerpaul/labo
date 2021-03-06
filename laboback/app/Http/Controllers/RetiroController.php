<?php

namespace App\Http\Controllers;

use App\Models\Retiro;
use App\Models\Reactivo;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RetiroController extends Controller
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
        //
        $reactivo=Reactivo::find($request->reactivo_id);
        $retiro= new Retiro;
        $retiro->fecharetiro=$request->fecha;
        $retiro->egreso=$request->egreso;
        $retiro->anterior=$reactivo->stock;
        $retiro->observacion=$request->observacion;
        $retiro->reactivo_id=$request->reactivo_id;
        $retiro->user_id=Auth::user()->id;
        $retiro->save();

        /*$inventario=Inventario::find($request->inventario_id);
        if($inventario->saldo <= $request->egreso)
        {
            $inventario->saldo=0;
            $inventario->estado='INACTIVO';
        }
        else
            $inventario->saldo=$inventario->saldo - $request->egreso;
        $inventario->save();*/
        
        $reactivo->stock= intval($reactivo->stock) - intval($request->egreso);
        $reactivo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Retiro  $retiro
     * @return \Illuminate\Http\Response
     */
    public function show(Retiro $retiro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Retiro  $retiro
     * @return \Illuminate\Http\Response
     */

    public function listretiro(Request $request)
    {
        //
        //return Retiro::where('inventario_id',$request->id)->get();
        return DB::select("SELECT id,inventario_id ,fecharetiro as fecha, null as fechavencimiento,'' as marca,'' as lote, '' as ingreso,'' as saldo,egreso, observacion from retiros where inventario_id=$request->id");
    }
    public function edit(Retiro $retiro)
    {
        //
    }

    public function invent(Request $request){
        return DB::SELECT("SELECT * from inventarios where estado='ACTIVO' and reactivo_id=$request->reactivo_id");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Retiro  $retiro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Retiro $retiro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Retiro  $retiro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Retiro $retiro)
    {
        //
    }

}
