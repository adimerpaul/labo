<?php

namespace App\Http\Controllers;

use App\Models\hece;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
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
*{
margin: 0px;
padding: 0px;
border: 0px;
font-size: 12px;
}
</style>
        <table style="width: 100%;color: black;padding-top: 5px">
        <tr >
            <td rowspan="4" style="height: 2cm"><img src="./images/natividad2.png" alt="Logo Clinica" srcset="" style="height:10px; width:200px;"></td>
            <td style="text-align:center;">SERVICIO DE LABORATORIO </td>
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
    <table class="tablex" border="1" style="width: 100%;color: black;padding-left: 10px;padding-right: 15px">
        <tr>
            <td class="tdx" colspan="3" style="text-align: center"><h3>ANALISIS DE HECES</h3></td>
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
    <table class="tablex" border="1" style="width: 100%;color: black;padding-left: 10px;padding-right: 15px">
        <tr >
            <td class="tdx" colspan="2" style="text-align: center ">EXAMEN EN FRESCO</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">ASPECTO DE LA MUESTRA</td>
            <td class="tdx" >'.$row->d1.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">COLOR</td>
            <td class="tdx" >'.$row->d2.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">CELULAS EPITELIALES</td>
            <td class="tdx" >'.$row->d3.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">LEUCOCITOS</td>
            <td class="tdx" >'.$row->d4.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">HEMATIES</td>
            <td class="tdx" >'.$row->d5.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">ALMIDON</td>
            <td class="tdx" >'.$row->d6.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">LEVADURAS</td>
            <td class="tdx" >'.$row->d7.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">GRASAS</td>
            <td class="tdx" >'.$row->d8.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">PARASITOS</td>
            <td class="tdx" >'.$row->d9.'</td>
        </tr>

        <tr>
            <td class="tdx" rowspan="2" style="color:red">MOCO FECAL :
            <span style="color:black">'.$row->d10.'</span>             </td>
            <td class="tdx"> Polimorfonucleares: <span style="color:black">'.$row->d11.'</span></td>

        </tr>
        <tr>
            <td class="tdx">Mononucleares: <span style="color:black">'.$row->d12.'</span></td>

        </tr>
        <tr>
            <td class="tdx" style="color:red">OTROS</td>
            <td class="tdx" >'.$row->d13.'</td>
        </tr>

        <tr colspan="2">
            <td class="tdx" colspan="2" style="text-align:center">TINCION DE GRAM</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">BACILOS GRAM POSITIVOS</td>
            <td class="tdx" >'.$row->d14.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">BACILOS GRAM NEGATIVOS</td>
            <td class="tdx" >'.$row->d15.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">COCOS GRAM POSITIVOS</td>
            <td class="tdx" >'.$row->d16.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">COCOS GRAM NEGATIVOS</td>
            <td class="tdx" >'.$row->d17.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">COCOBACILOS GRAM</td>
            <td class="tdx" >'.$row->d18.'</td>
        </tr>
        <tr>
            <td class="tdx" style="color:red">ESPORAS MICOTICAS</td>
            <td class="tdx" >'.$row->d19.'</td>
        </tr>

        <tr>
            <td class="tdx" style="color:red">OTROS</td>
            <td class="tdx" >'.$row->d20.'</td>
        </tr>

        <tr>
            <td class="tdx" rowspan="2" >RESPONSABLE: '.$row->user->name.'</td>

            <td class="tdx">
                FECHA DE TOMA DE MUESTRAS: '.$row->fechatoma.'

            </td>
        </tr>
        <tr>
            <td class="tdx">
                FECHA DE ENTREGA DE MUESTRAS: '.$row->fechaentrega.'

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
