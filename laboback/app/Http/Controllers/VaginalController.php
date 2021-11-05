<?php

namespace App\Http\Controllers;

use App\Models\Vaginal;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class VaginalController extends Controller
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
        $dato=Vaginal::create($request->vaginal+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('Uretral.pdf');
        //return redirect('/pacientes');
    }

    public function generar($id){
        $row= vaginal::with('paciente')
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
table{
    margin-left:10px;
}
*{
margin: 0px;
padding: 0px;
border: 0px;
font-size: 12px;
}
table{
    margin-left: 5px;
    margin-right: 5px;
    width: 50%;
    } 
            </style>
            <table style="width: 100%;color: black;padding-top: 5px">
            <tr >
                <td rowspan="3" ><img src="./img/natividad.jpeg" alt="Logo Clinica" srcset="" style="height: 1.5cm; "></td>
                <td style="text-align:center;color:darkblue">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="text-align:center;color:darkblue">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style="text-align:center;color:darkblue">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
            <tr>
                <td style="text-align:center;color:red">Nº Registro CODELAB 000045</td>
                <td style="text-align:center;color:darkblue">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
        </table>
        <table class="tablex" style="width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="3" style="text-align:center; border:0; color:darkblue;font:bold"><h3>ANALISIS DE SECRECION VAGINAL</h3></td>
                <td class="tdx" style="color:red;text-align:center;">Form. 004</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue">PACIENTE</td>
                <td class="tdx" style="text-align:center;">'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</td>
                <td class="tdx" style="color: darkblue">EDAD</td>
                <td class="tdx" style="text-align:center;">'.$row->paciente->age().'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue">REQUERIDO POR</td>
                <td class="tdx" style="text-align:center;">'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                <td class="tdx" style="color: darkblue">SEXO</td>
                <td class="tdx" style="text-align:center;">'.$row->paciente->sexo.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue">TIPO MUESTRA</td>
                <td class="tdx" style="text-align:center;color:blue">'.$row->tipomuestra.'</td>
                <td class="tdx" style="color: red">N PACIENTE</td>
                <td class="tdx" style="text-align:center;color:red">'.$row->paciente->id.'</td>
            </tr>

        </table>
        <table class="tablex" border="1" style="width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="2" style="text-align: center;color:purple;font:bold ">EXAMEN EN FRESCO</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">CELULAS EPITELIALES</td>
                <td class="tdx" style="text-align:center">'.$row->d1.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">LEUCOCITOS</td>
                <td class="tdx" style="text-align:center">'.$row->d2.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">HEMATIES</td>
                <td class="tdx" style="text-align:center">'.$row->d3.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">CELULAS CLAVE</td>
                <td class="tdx" style="text-align:center">'.$row->d4.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">LEVADURAS</td>
                <td class="tdx" style="text-align:center">'.$row->d5.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">PARASITOS</td>
                <td class="tdx" style="text-align:center">'.$row->d6.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">BACTERIAS</td>
                <td class="tdx" style="text-align:center">'.$row->d7.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">KOH</td>
                <td class="tdx" style="text-align:center">'.$row->d8.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">PH</td>
                <td class="tdx" '.$row->d9.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">OBSERVACIONES</td>
                <td class="tdx" style="text-align:center">'.$row->d10.'</td>
            </tr>

            <tr colspan="2">
                <td class="tdx" colspan="2" style="text-align:center;color:purple;font:bold">TINCION DE GRAM</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">BACILOS GRAM POSITIVO</td>
                <td class="tdx" style="text-align:center">'.$row->d11.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">BACILOS GRAM NEGATIVO</td>
                <td class="tdx" style="text-align:center">'.$row->d12.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">COCOS GRAM POSITIVO</td>
                <td class="tdx" style="text-align:center">'.$row->d13.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">COCOBACILOS GRAM POSITIVO</td>
                <td class="tdx" style="text-align:center">'.$row->d14.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">COCOBACILOS GRAM NEGATIVO</td>
                <td class="tdx" style="text-align:center">'.$row->d15.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">ESPORAS E HIFAS MICOTICAS</td>
                <td class="tdx" style="text-align:center">'.$row->d16.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">OBSERVACIONES</td>
                <td class="tdx" style="text-align:center">'.$row->d17.'</td>
            </tr>

            <tr>
                <td class="tdx" rowspan="2" style="color:darkblue">RESPONSABLE: '.$row->responsable.'</td>

                <td class="tdx" style="color:blue">
                    FECHA DE TOMA DE MUESTRAS :
                    '.$row->fechatoma.'
                </td>
            </tr>
            <tr>
                <td style="color:blue">
                    FECHA DE ENTREGA DE MUESTRAS:
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
     * @param  \App\Models\vaginal  $vaginal
     * @return \Illuminate\Http\Response
     */
    public function show(vaginal $vaginal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\vaginal  $vaginal
     * @return \Illuminate\Http\Response
     */
    public function edit(vaginal $vaginal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\vaginal  $vaginal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, vaginal $vaginal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\vaginal  $vaginal
     * @return \Illuminate\Http\Response
     */
    public function destroy(vaginal $vaginal)
    {
        //
    }
}
