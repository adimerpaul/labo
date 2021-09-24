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
            *{
            padding:0px;
            border:0px;
            margin:0px;
            font-size: 12px;
            }
            </style>
            <table style="width: 100%;color: black;padding-top: 5px;padding-right: 15px;padding-left: 10px">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="./img/natividad2.png"  alt="Logo Clinica" srcset="" style="height: 10px; width:200px;"></td>
                <td style="text-align:center;">SERVICIO DE LABORATORIO</td>
            </tr>
            <tr>
                <td style="text-align:center;">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
            <tr>
                <td style="text-align:center;">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style="text-align:center;">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
        </table>
        <table class="tablex" style="width: 100%;color: black;padding-right: 15px;padding-left: 10px">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center"><h3>ANALISIS DE SECRECION URETRAL</h3></td>
                <td class="tdx">Form. '.$row->id.'</td>
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
        <table class="tablex" style="width: 100%;color: black;padding-right: 15px;padding-left: 10px">
            <tr>
                <td class="tdx" colspan="2" style="text-align: center;color: darkred;font-weight: bold ">EXAMEN EN FRESCO</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >CELULAS EPITELIALES</td>
                <td class="tdx">'.$row->d1.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">LEUCOCITOS</td>
                <td class="tdx" >'.$row->d2.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">HEMATIES</td>
                <td class="tdx" >'.$row->d3.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">CELULAS CLAVE</td>
                <td class="tdx" >'.$row->d4.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">LEVADURAS</td>
                <td class="tdx" >'.$row->d5.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">PARASITOS</td>
                <td class="tdx" >'.$row->d6.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">BACTERIAS</td>
                <td class="tdx" >'.$row->d7.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">KOH</td>
                <td class="tdx" >'.$row->d8.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">PH</td>
                <td class="tdx" >'.$row->d9.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">OBSERVACIONES</td>
                <td class="tdx" >'.$row->d10.'</td>
            </tr>

            <tr colspan="2">
                <td class="tdx" colspan="2" style="text-align:center ;color: darkred;font-weight: bold">TINCION DE GRAM</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">BACILOS GRAM POSITIVO</td>
                <td class="tdx" >'.$row->d11.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">BACILOS GRAM NEGATIVO</td>
                <td class="tdx" >'.$row->d12.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">COCOS GRAM POSITIVO</td>
                <td class="tdx" >'.$row->d13.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">COCOBACILOS GRAM POSITIVO</td>
                <td class="tdx" >'.$row->d14.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">COCOBACILOS GRAM NEGATIVO</td>
                <td class="tdx" >'.$row->d15.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">ESPORAS E HIFAS MICOTICAS</td>
                <td class="tdx" >'.$row->d16.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red">OBSERVACIONES</td>
                <td class="tdx" >'.$row->d17.'</td>
            </tr>


            <tr>
                <td class="tdx" rowspan="2" style="color: blue;font-weight: bold" >RESPONSABLE: '.$row->user->name.'</td>

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
        $pdf->setPaper('letter');
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
