<?php

namespace App\Http\Controllers;

use App\Models\Reactivo;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReactivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Reactivo::with('inventarios')->get();
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
        $reactivo->unidad=strtoupper($request->unidad);
        $reactivo->minimo=$request->minimo;
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
        $reactivo->unidad=strtoupper($request->unidad);
        $reactivo->minimo=$request->minimo;
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
        $fec=date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 days'));
        return DB::SELECT("SELECT r.nombre from reactivos r inner join inventarios i on r.id=i.reactivo_id where i.estado='ACTIVO' and date(i.fechavencimiento)<='$fec'");

        //return Inventario::where('estado','ACTIVO')->whereDate('fechavencimiento','>=',$fec)->get();
    }

    public function minimo(){
        return DB::SELECT("SELECT nombre from reactivos where stock <= minimo");
    }

    public function impresion(Request $request){
        $resultado=DB::SELECT("(SELECT id,fecha, fechavencimiento, marca, lote, ingreso, anterior,0 as egreso, observacion,created_at from inventarios where reactivo_id=".$request->reactivo['id']." and fecha>='$request->fecha') union (SELECT id,fecharetiro as fecha, null as fechavencimiento,'' as marca,'' as lote, 0 as ingreso,anterior,egreso,observacion,created_at from retiros where reactivo_id=".$request->reactivo['id']." and fecharetiro>='$request->fecha' ) order by created_at asc");
        $col='white';
        $cadena='
        <tr>
        <th>FECHA</th>
        <th>VENC</th>
        <th>MARCA</th>
        <th>LOTE</th>
        <th>INGRESO</th>
        <th>EGRESO</th>
        <th>SALDO</th>
        <th>OBS</th>
        </tr>
        ';
            $total=0;
            foreach ($resultado as $r) {
                $total=$r->anterior + $r->ingreso - $r->egreso;
                if($r->ingreso>0)
                $col='red';
                else
                $col='white';
                $cadena.="<tr style='background:".$col."'>
                <td>$r->fecha</td>
                <td>$r->fechavencimiento</td>
                <td>$r->marca</td>
                <td>$r->lote</td>
                <td>$r->ingreso</td>
                <td>$r->egreso</td>
                <td>$total</td>
                <td>$r->observacion</td>
            </tr>";
        }
        $cadena.="</table></body></html>";
        return $cadena;
    }
}
