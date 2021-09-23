<?php

namespace App\Http\Controllers;

use App\Models\Reactivo;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $reactivos=Reactivo::with('inventarios')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reactivo=new Reactivo;
        $reactivo->codigo=$request->codigo;
        $reactivo->nombre=strtoupper($request->nombre);
        $reactivo->user_id=Auth::user()->id;
        return $reactivo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reactivo  $reactivo
     * @return \Illuminate\Http\Response
     */
    public function show(Reactivo $reactivo)
    {
        if ($reactivo->estado=='ACTIVO'){
            $reactivo->estado='INACTIVO';
        }else{
            $reactivo->estado='ACTIVO';
        }
        return $reactivo->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reactivo  $reactivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reactivo $reactivo)
    {
        $reactivo=reactivo::find($request->id);
        $reactivo->codigo=$request->codigo;
        $reactivo->nombre=strtoupper($request->nombre);
        return $reactivo->save();
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reactivo  $reactivo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $reactivo=Reactivo::find($id);
        $reactivo->delete();
//        return redirect('reactivos');
    }
    public function reactivoestado(Request $request)
    {
        $reactivo=Reactivo::find($request->id);
        if ($reactivo->activo==1)
            $reactivo->activo=0;
        else
            $reactivo->activo=1;
        $reactivo->save();
//        return redirect('/reactivos');
    }
    public function caduca(){
        return $reactivos=Inventario::where('estado','ACTIVO')->whereDate('fechavencimiento','>=',now())->get();
    }
}
