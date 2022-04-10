<?php

namespace App\Http\Controllers;

use App\Models\Serologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class SerologiaController extends Controller
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
        $dato=Serologia::create($request->serologia+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('seroloiga.pdf');
        //return redirect('/pacientes');
    }

    public function generar($id){
        $row= Serologia::with('paciente')
            ->with('user')
            ->with('doctor')
        ->where('id',$id)
        ->get();
        $row=$row[0];
        $cdlgm1='';
        $cdlgm2='';
        $cdop1='';
        $cdop2='';
        $cd1='';
        $cd2='';
        if(floatval($row->lgm) < 0.9) $cd1='color:#ff726f;';
        if(floatval($row->lgm) >= 0.9 && floatval($row->lgm) < 1.1)
        {$cd1='color:#ff726f;';
            $cdlgm2=floatval($row->lgm).'';
            $cdop2=$row->d1;
        }
        else
        {
            $cdlgm1=floatval($row->lgm).'';
            $cdop1=$row->d1;
        }
        if($row->lgg < 1.1) $cd2='color:#ff726f;';

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
font-size: 11px;
}
</style>
            <table style="padding-left:10px;padding-right:15px;width: 100%;color: black;padding-top: 5px">
            <tr >
                <td rowspan="3" ><img src="./img/natividad.jpeg" alt="Logo Clinica" style="height: 1.5cm; "></td>
                <td style=" text-align:center; color:darkblue">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style=" text-align:center; color:darkblue">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
            <tr>
                <td style=" text-align:center; color:darkblue">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style=" text-align:center;color:red">Nº Registro CODELAB 000045</td>
                <td style=" text-align:center; color:darkblue">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
        </table>
        <table class="tablex" border="1" style="padding-left:10px;padding-right:15px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center;color:darkblue;font:bold"><h3>SEROLOGIA</h3></td>
                <td class="tdx" style="color:red">Form. 005</td>
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
                <td class="tdx" style="text-align:center;color:red">'.$row->paciente->id.'</td>
            </tr>

        </table>
        <br>
        <table class="tablex" style="padding-left:10px;padding-right:15px;width: 100%;">
            <tr>
                <td class="tdx" colspan="4" style="text-align: center ;color: red;font-weight: bold">PRUEBA ANTICUERPOS CUANTITATIVOS ANTI SARS COV-2 lg M / lg G</td>
            </tr>
            <tr>
                <td class="tdx" colspan="3" style="text-align: center ;color: blue;font-weight: bold">METODO: INMUNOENSAYO DE FLUORECENCIA (FIA)</td>
                <td class="tdx"  style="text-align: center ;color: red;font-weight: bold">Valor de Referencia</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;" >lgM</td>
                <td class="tdx" style="text-align:center">'.$cdlgm1.'</td>
                <td class="tdx" style="text-align:center;'.$cd1.'">'.$cdop1.'</td>
                <td class="tdx"  style="text-align:center;color:blue"> menor a 0.9 NEGATIVO PARA lgG/lgM </td>
            </tr>
            <tr>
                <td class="tdx"></td>
                <td class="tdx" style="text-align:center">'.$cdlgm2.'</td>
                <td class="tdx" style="text-align:center;'.$cd1.'">'.$cdop2.'</td>
                <td class="tdx" style="text-align:center;color:blue"> 0.9 menor igual & mayor 1.1 INDETERMINADO </td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;" >lgG</td>
                <td class="tdx" style="text-align:center">'.$row->lgg.'</td>
                <td class="tdx" style="text-align:center;'.$cd2.'">'.$row->d2.'</td>
                <td class="tdx" style="text-align:center;color:blue"> mayor igual 1.1 POSITIVO lgG/lgM </td>
            </tr>

            </tr>
            <tr>
                <td class="tdx" colspan="4" style="text-align: center;color: darkred;font-weight: bold">INTERPRETACION DE RESULTADOS</td>
            </tr>
            <tr style="text-align:center; color:red">
                <td class="tdx">lg M</td>
                <td class="tdx">lg G</td>
                <td class="tdx">INTERPRETACION</td>
                <td class="tdx">COMENTARIO</td>
            </tr>
            <tr style="text-align:center">
                <td class="tdx"><img src="./img/resta.JPEG" alt="negativo"  style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx"><img src="./img/resta.JPEG" alt="negativo"  style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx">Ausencia de la Enfermedad o Falso Negativo</td>
                <td class="tdx">Paciente en fase de evolucion de la enfermedad mayor de 21 dias</td>
            </tr>
            <tr style="text-align:center">
                <td class="tdx"><img src="./img/suma.JPEG" alt="positivo"  style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx"><img src="./img/resta.JPEG" alt="negativo"  style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx">Inicio Temprano de ka Enfermedad Infeccion Aguda de la Enfermedad</td>
                <td class="tdx">Se debe repetir dentro de 5 a 7 dias la prueba</td>
            </tr>
            <tr style="text-align:center">
                <td class="tdx"><img src="./img/suma.JPEG" alt="positivo" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx"><img src="./img/suma.JPEG" alt="positivo" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx">Fase activa de la Enfermedad</td>
                <td class="tdx">Correlacionar con clinica del paciente y realizar examenes complementarios (Rayos X, Tomografia)</td>
            </tr>
            <tr style="text-align:center">
                <td class="tdx"><img src="./img/resta.JPEG" alt="negativo" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx"><img src="./img/suma.JPEG" alt="positivo" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx">Inmunidad Fase final de la Infeccion Infeccion pasada y curada</td>
                <td class="tdx">Paciente en fase de evolucion de la enfermedad mayor a 21 dias</td>
            </tr>
            <tr>
                <td class="tdx" colspan="4">
                    <span style="color:red">NOTA: </span>
                    Las pruebas rapidas para COVID-19 NO SON CONFIRMATORIAS, en caso de salir positivo alguno de los anticuerpo
                    , se recimienda una segunda toma de muestra con la Tecnica de HISOPADO NASOFARINGEO para RT - PCR y asi confirmar su DIAGNOSTICO
                </td>
            </tr>
            <tr>
                <td class="tdx" colspan="4">
                    Todas las personas producimos anticuerpos a diferente velocidad dependiendo del agente patogeno y nuestra genetica que es lo que
                    determina la funcionalidad de nuestro Sistema Inmunologico
                </td>
            </tr>
            <tr>
                <td class="tdx" colspan="4">
                    Los Resultados deben ser interpretados en funcion de la Clinica del paciente y dias de evolucion de la enfermedad
                </td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkred;font-weight: bold;text-align:center;">OBSERVACIONES</td>
                <td class="tdx" colspan="3" >'.$row->d3.'</td>
            </tr>

            <tr >
                <td class="tdx" colspan="3" rowspan="3" style="color: blue;font-weight: bold" >RESPONSABLE: '.$row->responsable.'</td>

                <td class="tdx" colspan="2" style="color: darkblue;font-weight: bold">
                    FECHA DE TOMA DE MUESTRAS: <span style="color:black">'.$row->fechatoma.'</span>
                </td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="color: darkblue;font-weight: bold">
                    HORA DE TOMA DE MUESTRAS:
                    <span style="color:black">'.$row->horatoma.'</span>
                </td>
            </tr>
            <tr>
            <td class="tdx" colspan="2" style="color: darkblue;font-weight: bold">
                FECHA DE ENTREGA DE MUESTRAS
                <span style="color:black">'.date('Y-m-d').'</span>
            </td>
        </tr>
        </table>
        
        ';
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('legal');
        $pdf->loadHTML($cadena);
        return $pdf->stream();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Serologia  $serologia
     * @return \Illuminate\Http\Response
     */
    public function show(Serologia $serologia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Serologia  $serologia
     * @return \Illuminate\Http\Response
     */
    public function edit(Serologia $serologia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Serologia  $serologia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Serologia $serologia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Serologia  $serologia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Serologia $serologia)
    {
        //
    }
}
