<?php

namespace App\Http\Controllers;

use App\Models\Labserologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class LabserologiaController extends Controller
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
        $dato=Labserologia::create($request->labserologia+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('lab.pdf');
        //return redirect('/pacientes');
    }
    public function generar($id){
        $row= labserologia::with('paciente')->with('user')->with('doctor')
        ->where('id',$id)
        ->get();
        $row=$row[0];
        $cd1='';
        $cd2='';
        $cd3='';
        $cd13='';
        if(($row->d1<0 || $row->d1>8)&& $row->d1!='')$cd1='color:#ff726f;';
        if(($row->d2<0 || $row->d2>200)&& $row->d2!='')$cd2='color:#ff726f;';
        if(($row->d3<0 || $row->d3>0.8)&& $row->d3!='')$cd3='color:#ff726f;';
        if(($row->d13<0 || $row->d13>4)&& $row->d13!='')$cd13='color:#ff726f;';
        $cadena='
            <style>
            table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
           }
            *{
                padding: 0px;
                margin: 0px;
                font-size: 12px;
                }
            table{
                width:50%;
                margin-left:10px;

            }
            </style>
            <table style="border:0; margin-top:5px;" >
            <tr >
                <td rowspan="3" style="border:0;"><img src="./img/natividad.jpeg" alt="Logo Clinica" srcset="" style="height:1.5cm"></td>
                <td style="color: darkblue; text-align:center; border:0;">SERVICIO DE LABORATORIO </td>
            </tr> 
            <tr >
                <td style="color: darkblue; text-align:center; border:0;">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr >
                <td style="color: darkblue; text-align:center;border:0; ">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr >
                <td style="color: red; text-align:center; border:0;">Nº Registro CODELAB 000045 </td>                
                <td style="color: darkblue; text-align:center; border:0;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="3" style="text-align: center;color:darkblue;font:bold;border:0"><h3>SEROLOGIA</h3></td>
                <td style="color:red">Form. 005</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td style="text-align:center">'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</td>
                <td style="color: darkblue">EDAD</td>
                <td style="text-align:center">'.$row->paciente->age().'</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td style="text-align:center">'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                <td style="color: darkblue">SEXO</td>
                <td style="text-align:center">'.$row->paciente->sexo.'</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td style="text-align:center">'.$row->tipomuestra.'</td>
                <td style="color:red">N PACIENTE</td>
                <td style="text-align:center;color:red">'.$row->paciente->id.'</td>
            </tr>

        </table>
        <table >

            <tr>
                <td colspan="2" style="text-align:center; color:red;"  ></td>
                <td style="text-align:center; color:red;" > Valor Referencial</td>
                <td style="text-align:center; color:red;" >Metodo de Prueba</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Factor Reumatoide(Latex)</td>
                <td style="text-align:center;'.$cd1.'">'.$row->d1.'</td>
                <td style="text-align:center; color:Khaki;" >Hasta 8 UI/ml</td>
                <td style="text-align:center; color:darkblue;">Aglutinacion directa</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Antiestreptolisina</td>
                <td style="text-align:center;'.$cd2.'">'.$row->d2.'</td>
                <td style="text-align:center; color:Khaki;">Hasta 200 UI/l</td>
                <td style="text-align:center; color:darkblue;">Aglutinacion directa</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Proteina C Reactiva</td>
                <td style="text-align:center;'.$cd3.'">'.$row->d3.'</td>
                <td style="text-align:center; color:Khaki;">Hasta 0.8 mg/dl</td>
                <td style="text-align:center; color:darkblue;">Aglutinacion directa</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">RPR</td>
                <td style="text-align:center;">'.$row->d4.'</td>
                <td style="text-align:center; color:Khaki;">No Reactivo</td>
                <td style="text-align:center; color:darkblue;">Floculacion directa </td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Prueba Rapida Sifilis</td>
                <td style="text-align:center;">'.$row->d5.'</td>
                <td style="text-align:center; color:Khaki;">No Reactivo</td>
                <td style="text-align:center; color:darkblue;">Prueba Rapida I-croma</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Prueba Rapida VIH</td>
                <td style="text-align:center;">'.$row->d6.'</td>
                <td style="text-align:center; color:Khaki;">No Reactivo</td>
                <td style="text-align:center; color:darkblue;">Prueba Rapida I-croma</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Hepatitis A</td>
                <td style="text-align:center;">'.$row->d7.'</td>
                <td style="text-align:center; color:Khaki;">NEGATIVO / POSITIVO</td>
                <td style="text-align:center; color:darkblue;">Prueba Rapida I-croma</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Hepatitis B</td>
                <td style="text-align:center;">'.$row->d8.'</td>
                <td style="text-align:center; color:Khaki;">NEGATIVO / POSITIVO</td>
                <td style="text-align:center; color:darkblue;">Prueba Rapida I-croma</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Hepatitis C</td>
                <td style="text-align:center;">'.$row->d9.'</td>
                <td style="text-align:center; color:Khaki;">NEGATIVO / POSITIVO</td>
                <td style="text-align:center; color:darkblue;">Prueba Rapida I-croma</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Helicobacter Pylori en Sangre</td>
                <td style="text-align:center;">'.$row->d10.'</td>
                <td style="text-align:center; color:Khaki;">NEGATIVO / POSITIVO</td>
                <td style="text-align:center; color:darkblue;">Prueba Rapida I-croma</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Helicobacter Pylori en Heces</td>
                <td style="text-align:center;">'.$row->d11.'</td>
                <td style="text-align:center; color:Khaki;">NEGATIVO / POSITIVO</td>
                <td style="text-align:center; color:darkblue;">Prueba Rapida I-croma</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">Troponina I</td>
                <td style="text-align:center;">'.$row->d12.'</td>
                <td style="text-align:center; color:Khaki;">NEGATIVO / POSITIVO</td>
                <td style="text-align:center; color:darkblue;">Prueba Rapida I-croma</td>
            </tr>
            <tr>
                <td style="color:darkred; width: 20%">PSA</td>
                <td style="text-align:center;'.$cd13.'">'.$row->d13.'</td>
                <td style="text-align:center; color:Khaki;">Hasta 4 ng/ml</td>
                <td style="text-align:center; color:darkblue;">Prueba Rapida Semicuantitativo</td>
            </tr>
            <tr>
                <td style="color:red;">OBSERVACIONES</td>
                <td colspan="3" >'.$row->d14.'</td>
            </tr>

            <tr >
                <td colspan="2" rowspan="3" style="color:darkblue;">RESPONSABLE: <span style="color:black">'.$row->responsable.'</span></td>

                <td style="color:darkblue;">FECHA DE TOMA DE MUESTRAS: </td>
                <td >'.$row->fechatoma.'</td>
            </tr>
            <tr>

                <td style="color:darkblue;">HORA DE TOMA DE MUESTRAS: </td>
                <td>'.$row->horatoma.'</td>
            </tr>
            <tr>

                <td style="color:darkblue;">FECHA DE ENTREGA DE MUESTRAS: </td>
                <td>'.date('Y-m-d').'</td>
            </tr>

        </table>

            ';
            $pdf = App::make('dompdf.wrapper');
            //        $customPaper = array(0,0,360,360);
                    $pdf->setPaper('legal','landscape');
                    $pdf->loadHTML($cadena);
                    return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Labserologia  $labserologia
     * @return \Illuminate\Http\Response
     */
    public function show(Labserologia $labserologia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Labserologia  $labserologia
     * @return \Illuminate\Http\Response
     */
    public function edit(Labserologia $labserologia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Labserologia  $labserologia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Labserologia $labserologia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Labserologia  $labserologia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Labserologia $labserologia)
    {
        //
    }
}
