<?php

namespace App\Http\Controllers;

use App\Models\Hece;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class HeceController extends Controller
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
        $dato=Hece::create($request->hece+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;

        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('ejemplo.pdf');
        //return redirect('/pacientes');
    }
    public function generar($id){
        $row= hece::with('paciente')
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
</style>
        <table style="width: 100%;color: black;padding-top: 5px">
        <tr >
            <td rowspan="5" ><img src="./img/natividad.jpeg" alt="Logo Clinica" srcset="" style="height:1.5cm; "></td>
            <td style="text-align:center;color:darkblue;">SERVICIO DE LABORATORIO </td>
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
            <td style="text-align:center;color:red">Nº Registro CODELAB 000045 </td>
        </tr>

    </table>
    <table class="tablex" style="width: 100%;color: black;padding-left: 10px;padding-right: 15px">
        <tr>
            <td class="tdx" colspan="3" style="text-align: center;color:darkblue;font:bold;border:0;"><h3>ANALISIS DE HECES</h3></td>
            <td class="tdx" style="color:red;text-align:center">Form. 010</td>
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
    <table class="tablex" border="1" style="width: 100%;color: black;padding-left: 10px;padding-right: 15px">
        <tr >
            <td class="tdx" colspan="3" style="text-align: center;font:bold;color:darkblue ">EXAMEN EN FRESCO</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red" colspan="2">ASPECTO DE LA MUESTRA</td>
            <td class="tdx" style="text-align:center">'.$row->d1.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">COLOR</td>
            <td class="tdx" style="text-align:center">'.$row->d2.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">CELULAS EPITELIALES</td>
            <td class="tdx" style="text-align:center">'.$row->d3.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">LEUCOCITOS</td>
            <td class="tdx" style="text-align:center">'.$row->d4.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">HEMATIES</td>
            <td class="tdx" style="text-align:center">'.$row->d5.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">ALMIDON</td>
            <td class="tdx" style="text-align:center">'.$row->d6.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">LEVADURAS</td>
            <td class="tdx" style="text-align:center" >'.$row->d7.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">GRASAS</td>
            <td class="tdx" style="text-align:center">'.$row->d8.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">PARASITOS</td>
            <td class="tdx" style="text-align:center">'.$row->d9.'</td>
        </tr>

        <tr>
            <td class="tdx" rowspan="2" style="color:red:width:20%">MOCO FECAL :</td>
            <td rowspan="2"><span style="color:black; width:20%">'.$row->d10.'</span>             </td>
            <td class="tdx"> Polimorfonucleares: <span style="color:black; text-align:center">'.$row->d11.'</span></td>

        </tr>
        <tr>
            <td class="tdx">Mononucleares: <span style="color:black;text-align:center">'.$row->d12.'</span></td>

        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">OTROS</td>
            <td class="tdx" style="text-align:center">'.$row->d13.'</td>
        </tr>

        <tr colspan="2">
            <td class="tdx" colspan="3"  style="text-align: center;font:bold;color:darkblue ">TINCION DE GRAM</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">BACILOS GRAM POSITIVOS</td>
            <td class="tdx" style="text-align:center">'.$row->d14.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">BACILOS GRAM NEGATIVOS</td>
            <td class="tdx" >'.$row->d15.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">COCOS GRAM POSITIVOS</td>
            <td class="tdx" style="text-align:center">'.$row->d16.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">COCOS GRAM NEGATIVOS</td>
            <td class="tdx" style="text-align:center">'.$row->d17.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">COCOBACILOS GRAM</td>
            <td class="tdx" style="text-align:center" >'.$row->d18.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red"  colspan="2">ESPORAS MICOTICAS</td>
            <td class="tdx" style="text-align:center">'.$row->d19.'</td>
        </tr>

        <tr>
            <td class="tdx" style="color:red" colspan="2">OTROS</td>
            <td class="tdx" style="text-align:center">'.$row->d20.'</td>
        </tr>

        <tr>
            <td class="tdx" rowspan="2"  colspan="2" style="color:darkblue">RESPONSABLE: '.$row->responsable.'</td>

            <td class="tdx" style="color:darkblue">
                FECHA DE TOMA DE MUESTRAS: '.$row->fechatoma.'

            </td>
        </tr>
        <tr>
            <td class="tdx" style="color:darkblue">
                FECHA DE ENTREGA DE MUESTRAS: '.date('Y-m-d').'

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
     * @param  \App\Models\hece  $hece
     * @return \Illuminate\Http\Response
     */
    public function show(hece $hece)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\hece  $hece
     * @return \Illuminate\Http\Response
     */
    public function edit(hece $hece)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\hece  $hece
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, hece $hece)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\hece  $hece
     * @return \Illuminate\Http\Response
     */
    public function destroy(hece $hece)
    {
        //
    }
}
