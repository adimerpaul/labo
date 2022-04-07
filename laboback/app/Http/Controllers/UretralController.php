<?php

namespace App\Http\Controllers;

use App\Models\Uretral;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class UretralController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $dato=Uretral::create($request->uretral+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('Uretral.pdf');
       // return redirect('/pacientes');
    }

    public function generar($id){
        $row= uretral::with('paciente')
            ->with('user')
            ->with('doctor')
        ->where('id',$id)
        ->get();
        $row=$row[0];
        $cadena='
        <style>
            .tablex, .thx, .tdx {
            border: 1px solid black;
            border-collapse: collapse;
            }
            table{
                margin-left:10px;
            }
            *{
            padding:0px;
            border:0px;
            margin:0px;
            font-size: 12px;
            }
            </style>
            <table style="width: 100%;padding-top: 5px;padding-right: 15px;padding-left: 10px">
            <tr >
                <td rowspan="5" ><img src="./img/natividad.jpeg"  alt="Logo Clinica" srcset="" style="height: 1.5cm; "></td>
                <td style="text-align:center; color:darkblue;">SERVICIO DE LABORATORIO</td>
            </tr>

            <tr>
                <td style="text-align:center;color:darkblue;">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style="text-align:center;color:darkblue;">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
            <tr>
                <td style="text-align:center;color:darkblue;">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
            <tr>
                <td style="text-align:center;color:red;">Nº Registro CODELAB 000045 </td>
            </tr>
        </table>
        <table class="tablex" style="width: 100%;color: black;padding-right: 15px;padding-left: 10px">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center; color:darkblue; font:bold;"><h3>ANALISIS DE SECRECION URETRAL</h3></td>
                <td class="tdx" style="color:red;text-align:cener;">Form. 004</td>
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
                <td class="tdx" style="text-align:center; color:blue">'.$row->tipomuestra.'</td>
                <td class="tdx" style="color: red">N PACIENTE</td>
                <td class="tdx" style="text-align:center; color:red;">'.$row->paciente->id.'</td>
            </tr>

        </table>
        <table class="tablex" style="width: 100%;color: black;padding-right: 15px;padding-left: 10px">
            <tr>
                <td class="tdx" colspan="2" style="text-align: center;color: purple;font: bold ">EXAMEN EN FRESCO</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" >CELULAS EPITELIALES</td>
                <td class="tdx" style="text-align:center;">'.$row->d1.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">LEUCOCITOS</td>
                <td class="tdx" style="text-align:center;">'.$row->d2.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">HEMATIES</td>
                <td class="tdx" style="text-align:center;">'.$row->d3.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">CELULAS CLAVE</td>
                <td class="tdx" style="text-align:center;">'.$row->d4.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">LEVADURAS</td>
                <td class="tdx" style="text-align:center;" >'.$row->d5.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">PARASITOS</td>
                <td class="tdx" style="text-align:center;">'.$row->d6.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">BACTERIAS</td>
                <td class="tdx" style="text-align:center;">'.$row->d7.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">KOH</td>
                <td class="tdx" style="text-align:center;">'.$row->d8.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">PH</td>
                <td class="tdx" style="text-align:center;">'.$row->d9.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">OBSERVACIONES</td>
                <td class="tdx" style="text-align:center;">'.$row->d10.'</td>
            </tr>

            <tr colspan="2">
                <td class="tdx" colspan="2" style="text-align:center ;color: purple;font: bold">TINCION DE GRAM</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">BACILOS GRAM POSITIVO</td>
                <td class="tdx" style="text-align:center;">'.$row->d11.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">BACILOS GRAM NEGATIVO</td>
                <td class="tdx" style="text-align:center;">'.$row->d12.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">COCOS GRAM POSITIVO</td>
                <td class="tdx" style="text-align:center;">'.$row->d13.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">COCOBACILOS GRAM POSITIVO</td>
                <td class="tdx" style="text-align:center;">'.$row->d14.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">COCOBACILOS GRAM NEGATIVO</td>
                <td class="tdx" style="text-align:center;">'.$row->d15.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">ESPORAS E HIFAS MICOTICAS</td>
                <td class="tdx" style="text-align:center;">'.$row->d16.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue">OBSERVACIONES</td>
                <td class="tdx" style="text-align:center;">'.$row->d17.'</td>
            </tr>


            <tr>
                <td class="tdx" rowspan="2" style="color: blue;font-weight: bold" >RESPONSABLE: '.$row->responsable.'</td>

                <td class="tdx" style="color: blue;font-weight: bold">
                    FECHA DE TOMA DE MUESTRAS :
                    '.$row->fechatoma.'
                </td>
            </tr>
            <tr>
                <td class="tdx" style="color: blue;font-weight: bold">
                    FECHA DE ENTREGA DE MUESTRAS :
                    '.date('Y-m-d').'
                </td>
            </tr>
        </table>
            ';
        $pdf = App::make('dompdf.wrapper');
//        $customPaper = array(0,0,360,360);
        $pdf->setPaper('legal');
        $pdf->loadHTML($cadena);
        return $pdf->stream();
            }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Uretral  $uretral
     * @return \Illuminate\Http\Response
     */
    public function show(Uretral $uretral)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Uretral  $uretral
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Uretral $uretral)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Uretral  $uretral
     * @return \Illuminate\Http\Response
     */
    public function destroy(Uretral $uretral)
    {
        //
    }
}
