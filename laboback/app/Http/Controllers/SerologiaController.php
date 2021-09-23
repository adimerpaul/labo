<?php

namespace App\Http\Controllers;

use App\Models\Serologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use Illuminate\Support\Facades\App;
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
        $row= serologia::with('paciente')
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
font-size: 11px;
}
</style>
            <table style="padding-left:10px;padding-right:15px;width: 100%;color: black;padding-top: 5px">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="images/natividad.png" alt="Logo Clinica" srcset="" style="height: 10px; width:20px;"></td>
                <td style=" text-align:center;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style=" text-align:center;">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
            <tr>
                <td style=" text-align:center;">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style=" text-align:center;">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
        </table>
        <table class="tablex" border="1" style="padding-left:10px;padding-right:15px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center"><h3>SEROLOGIA</h3></td>
                <td class="tdx">Form. 5</td>
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
        <table class="tablex" border="1" style="padding-left:10px;padding-right:15px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="4" style="text-align: center ;color: darkred;font-weight: bold">PRUEBA ANTICUERPOS CUANTITATIVOS ANTI SARS COV-2 lg M / lg G</td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;" >lgM</td>
                <td class="tdx">'.$row->lgm.'</td>
                <td class="tdx">'.$row->d1.'</td>
                <td class="tdx"> menor a 0.9 NEGATIVO PARA lgG/lgM</td>
            </tr>
            <tr>
                <td class="tdx"></td>
                <td class="tdx"></td>
                <td class="tdx"></td>
                <td class="tdx"> 0.9 menor igual & mayor 1.1 INDETERMINADO </td>
            </tr>
            <tr>
                <td class="tdx" style="text-align:center; color:red;" >lgG</td>
                <td class="tdx">'.$row->lgg.'</td>
                <td class="tdx">'.$row->d2.'</td>
                <td class="tdx" style="width: 20%"> mayor igual 1.1 POSITIVO lgG/lgM</td>
            </tr>

            </tr>
            <tr>
                <td class="tdx" colspan="4" style="text-align: center;color: darkred;font-weight: bold">INTERPRETACION DE RESULTADOS</td>
            </tr>
            <tr style="text-align:center; color:red">
                <td class="tdx">lg M</td>
                <td class="tdx">lg G</td>
                <td class="tdx">INTERPRETACION</td>
                <td class="tdx">COMENTARION</td>
            </tr>
            <tr style="text-align:center">
                <td class="tdx"><img src="./img/resta.png" alt="negativo" srcset="" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx"><img src="./img/resta.png" alt="negativo" srcset="" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx">Ausencia de la Enfermedad o Falso Negativo</td>
                <td class="tdx">Paciente en fase de evolucion de la enfermedad mayor de 21 dias</td>
            </tr>
            <tr style="text-align:center">
                <td class="tdx"><img src="./img/suma.png" alt="positivo" srcset="" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx"><img src="./img/resta.png" alt="negativo" srcset="" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx">Inicio Temprano de ka Enfermedad Infeccion Aguda de la Enfermedad</td>
                <td class="tdx">Se debe repetir dentro de 5 a 7 dias la prueba</td>
            </tr>
            <tr style="text-align:center">
                <td class="tdx"><img src="./img/suma.png" alt="positivo" srcset="" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx"><img src="./img/suma.png" alt="positivo" srcset="" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx">Fase activa de la Enfermedad</td>
                <td class="tdx">Correlacionar con clinica del paciente y realizar examenes complementarios (Rayos X, Tomografia)</td>
            </tr>
            <tr style="text-align:center">
                <td class="tdx"><img src="./img/resta.png" alt="negativo" srcset="" style="height: 0.5cm; width:0.5cm;"></td>
                <td class="tdx"><img src="./img/suma.png" alt="positivo" srcset="" style="height: 0.5cm; width:0.5cm;"></td>
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
                <td class="tdx" class="text-left text-red" style="color: darkred;font-weight: bold">OBSERVACIONES</td>
                <td class="tdx" colspan="3" >'.$row->d3.'</td>
            </tr>

            <tr >
                <td class="tdx" colspan="2" rowspan="2" style="color: blue;font-weight: bold" >RESPONSABLE: '.$row->user->name.'</td>

                <td class="tdx" colspan="2" style="color: blue;font-weight: bold">
                    FECHA DE TOMA DE MUESTRAS: '.$row->fechatoma.'
                </td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="color: blue;font-weight: bold">
                    FECHA DE ENTREGA DE MUESTRAS
                    '.date('Y-m-d').'
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
