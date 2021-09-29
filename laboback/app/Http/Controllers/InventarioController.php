<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class InventarioController extends Controller
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
        $inventario=new Inventario;
        $inventario->fecha=date('Y-m-d');
        $inventario->fechavencimiento=$request->fechavencimiento;
        $inventario->marca=$request->marca;
        $inventario->lote=$request->lote;
        $inventario->ingreso=$request->ingreso;
        $inventario->saldo=$request->ingreso;
        $inventario->observacion=$request->observacion;
        $inventario->reactivo_id=$request->reactivo_id;
        $inventario->user_id=Auth::user()->id;
        return $inventario->save();
        

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Inventario::where('reactivo_id',$id)->get();
    }

    public function listinventario(Request $request)
    {
        //
        return DB::select("SELECT id,fecha, fechavencimiento, marca, lote, ingreso, saldo,0 as egreso, observacion, 0 as inventario_id 
        from inventarios where reactivo_id=$request->id and fecha>='$request->fecha'");
        //return Inventario::where('reactivo_id',$request->id)->whereDate('fecha','>=',$request->fecha)->get();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventario $inventario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
        //
    }
}
