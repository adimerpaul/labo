<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Seriado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
class SeriadoController extends Controller
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
        $dato=Seriado::create($request->seriado+ ['fechatoma'=>date('Y-m-d'),'user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('seriado.pdf');
        //return redirect('/pacientes');
    }

    public function generar($id){
        $row= seriado::with('paciente')
            ->with('user')
            ->with('doctor')
        ->where('id',$id)
        ->get();
        $row=$row[0];
        $cadena='
        <style>
.tablex, .tdx , .thx {
  border: 1px solid black;
  border-collapse: collapse;
}
*{
margin: 0px;
padding: 0px;
border: 0px;
font-size: 13px;
}
</style>
            <table style="padding-right:15px;padding-left:10px;width: 100%;color: black;padding-top: 10px">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="./images/natividad2.png" alt="Logo Clinica" srcset="" style="height: 10px; width:200px;"></td>
                <td style="text-align:center;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="text-align:center;">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
            <tr>
                <td style="text-align:center;">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style="text-align:center; ">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
        </table>
        <table class="tablex" border="1" style="padding-right:15px;padding-left:10px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center"><h3>COPROPARASITOLOGICO SERIADO</h3></td>
                <td class="tdx">Form. 008</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue">PACIENTE</td>
                <td class="tdx">'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</td>
                <td class="tdx" style="color: darkblue">EDAD</td>
                <td class="tdx">'.$row->paciente->age().'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue">REQUERIDO POR</td>
                <td class="tdx">'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                <td class="tdx" style="color: darkblue">SEXO</td>
                <td class="tdx">'.$row->paciente->sexo.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue">TIPO MUESTRA</td>
                <td class="tdx">'.$row->tipomuestra.'</td>
                <td class="tdx" style="color: darkblue">N PACIENTE</td>
                <td class="tdx">'.$row->paciente->id.'</td>
            </tr>

        </table>
        <br>
        <table class="tablex" border="1" style="padding-right:15px;padding-left:10px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center;font-weight: bold;color: darkred ">COPROPARASITOLOGICO SERIADO</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="text-align:center; color:red; width: 20%">1 MUESTRA</td>
                <td class="tdx" rowspan="3">'.$row->muestra1.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Fecha</td>
                <td class="tdx" >'.$row->fecha1.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Hora</td>
                <td class="tdx" >'.$row->hora1.'</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="text-align:center; color:red; width: 20%">2 MUESTRA</td>
                <td class="tdx" rowspan="3">'.$row->muestra2.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Fecha</td>
                <td class="tdx" >'.$row->fecha2.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Hora</td>
                <td class="tdx" >'.$row->hora2.'</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="text-align:center; color:red; width: 20%">3 MUESTRA</td>
                <td class="tdx" rowspan="3">'.$row->muestra3.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Fecha</td>
                <td class="tdx" >'.$row->fecha3.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Hora</td>
                <td class="tdx" >'.$row->hora3.'</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="text-align:center; color:red;">OBSERVACIONES</td>
                <td class="tdx" >'.$row->observaciones.'</td>
            </tr>

            <tr >
                <td class="tdx" colspan="2" style="color: blue;font-weight: bold" >RESPONSABLE: '.$row->user->name.'</td>

                <td class="tdx" style="color: blue;font-weight: bold">
                    FECHA DE ENTREGA RESULTADOS: '.date('Y-m-d').'
                </td>
            </tr>

        </table>

            ';
        $pdf = App::make('dompdf.wrapper');
//        $customPaper = array(0,0,360,360);
        $pdf->setPaper('letter');
        $pdf->loadHTML($cadena);
        return $pdf->stream();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\seriado  $seriado
     * @return \Illuminate\Http\Response
     */
    public function show(seriado $seriado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\seriado  $seriado
     * @return \Illuminate\Http\Response
     */
    public function edit(seriado $seriado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\seriado  $seriado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, seriado $seriado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\seriado  $seriado
     * @return \Illuminate\Http\Response
     */
    public function destroy(seriado $seriado)
    {
        //
    }
}
