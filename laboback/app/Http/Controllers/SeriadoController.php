<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Seriado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

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
font-size: 12px;
}
</style>
            <table style="padding-right:15px;padding-left:10px;width: 100%;color: black;padding-top: 10px">
            <tr >
                <td rowspan="3" ><img src="./img/natividad.jpeg" alt="Logo Clinica" srcset="" style="height: 1.5cm; "></td>
                <td style="text-align:center; color:darkblue;">SERVICIO DE LABORATORIO </td>
            </tr>

            <tr>
                <td style="text-align:center; color:darkblue;">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style="text-align:center; color:darkblue;">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
            <tr>
                <td style="text-align:center;color:red;">Nº Registro CODELAB 000045</td>
                <td style="text-align:center;color:darkblue;">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
        </table>
        <table class="tablex" border="1" style="padding-right:15px;padding-left:10px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center;color:darkblue;font:bold;border:0"><h3>COPROPARASITOLOGICO SERIADO</h3></td>
                <td class="tdx" style="color:red">Form. 009</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue">PACIENTE</td>
                <td class="tdx" style="text-align:center">'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</td>
                <td class="tdx" style="color: darkblue">EDAD</td>
                <td class="tdx" style="text-align:center">'.$row->paciente->age().'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue">REQUERIDO POR</td>
                <td class="tdx" style="text-align:center">'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                <td class="tdx" style="color: darkblue">SEXO</td>
                <td class="tdx" style="text-align:center">'.$row->paciente->sexo.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue">TIPO MUESTRA</td>
                <td class="tdx" style="text-align:center">'.$row->tipomuestra.'</td>
                <td class="tdx" style="color: red">N PACIENTE</td>
                <td class="tdx" style="text-align:center;color:red;">'.$row->paciente->id.'</td>
            </tr>

        </table>
        <br>
        <table class="tablex" border="1" style="padding-right:15px;padding-left:10px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center;font-weight: bold;color: darkblue ">COPROPARASITOLOGICO SERIADO</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="text-align:center; color:blue; width: 20%">1º MUESTRA</td>
                <td class="tdx" rowspan="3">'.$row->muestra1.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Fecha</td>
                <td class="tdx" style="text-align:center">'.$row->fecha1.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Hora</td>
                <td class="tdx" style="text-align:center">'.$row->hora1.'</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="text-align:center; color:blue; width: 20%">2º MUESTRA</td>
                <td class="tdx" rowspan="3">'.$row->muestra2.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Fecha</td>
                <td class="tdx" style="text-align:center">'.$row->fecha2.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Hora</td>
                <td class="tdx" style="text-align:center">'.$row->hora2.'</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="text-align:center; color:blue; width: 20%">3º MUESTRA</td>
                <td class="tdx" rowspan="3">'.$row->muestra3.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Fecha</td>
                <td class="tdx" style="text-align:center">'.$row->fecha3.'</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;">Hora</td>
                <td class="tdx" style="text-align:center">'.$row->hora3.'</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="text-align:center; color:red;">OBSERVACIONES</td>
                <td class="tdx" >'.$row->observaciones.'</td>
            </tr>

            <tr >
                <td class="tdx" colspan="2" style="color: darkblue;font-weight: bold" >RESPONSABLE: '.$row->responsable.'</td>

                <td class="tdx" style="color: darkblue;font-weight: bold">
                    FECHA DE ENTREGA RESULTADOS: <span style="color:black">'.date('Y-m-d').'</span>
                </td>
            </tr>

        </table>

            ';
            $pdf = App::make('dompdf.wrapper');
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
