<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Orina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;


class OrinaController extends Controller
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

        $dato=Orina::create($request->orina+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('orina.pdf');
        //return redirect('/pacientes');
    }

    public function generar($id){
        $row= Orina::with('paciente')->with('user')->with('doctor')
        ->where('id',$id)
        ->get();
        $row=$row[0];
        $cadena='
        <style>
table , th , td  {
  border: 1px solid black;
  border-collapse: collapse;
}
*{
    padding: 0px;
    margin: 0px;
    border: 0px;
    font-size: 10px;
    }
    table{
        margin-left: 5px;
        margin-right: 5px;
        width: 50%;
        }
</style>
        <table style="width: 100%;border:0; margin-top: 5px;">
        <tr >
            <td rowspan="3" style="border:0; "><img src="./img/natividad.jpeg" alt="Logo Clinica" srcset="" style="height: 1.5cm; "></td>
            <td style="color: blue; text-align:center;border:0; ">SERVICIO DE LABORATORIO </td>
        </tr>
        <tr>
            <td style="color: blue; text-align:center;border:0; ">Telf: 5254721 Fax: 52-83667 </td>
        </tr>
        <tr>
            <td style="color: blue; text-align:center;border:0; ">Emergencia las 24 horas del dia. </td>
        </tr>
        <tr>
            <td style="border:0; color:red;">Nº Registro CODELAB 000045</td>
            <td style="color: blue; text-align:center;border:0;  ">Bolivar Nº 753 entre Arica e Iquique </td>
        </tr>
    </table>
    <table  style=" width: 100%;">
        <tr>
            <td colspan="3" style="text-align: center; border:0; font:bold; color: darkblue;">EXAMEN GENERAL DE ORINA</td>
            <td style="color:darkblue; text-align:right">Form. 003</td>
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

            <td style="color: red">N PACIENTE</td>
            <td style="text-align:center;color:red">'.$row->paciente->id.'</td>
        </tr>
    </table>
    <table style="border:1,solid; width: 100%;">
        <tr><b>
            <td style="color:darkblue; text-align:center; font:bold; ">EX. FISICO</td>
            <td style="color:darkblue; text-align:center; font:bold; ">VALOR</td>
            <td style="color:darkblue; text-align:center; font:bold; ">REFERENCIA</td>
            <td style="color:darkblue; text-align:center; font:bold; ">EX. QUIMICO</td>
            <td style="color:darkblue; text-align:center; font:bold; ">VALOR</td>
            <td style="color:darkblue; text-align:center; font:bold; ">REFERENCIA</td></b>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Color</td>
            <td style="text-align:center; ">'.$row->d1.'</td>
            <td style="text-align:center; color:blue;">Amarillo</td>
            <td style="color:blue; text-align:left; ">Proteinas</td>
            <td style="text-align:center; ">'.$row->d2.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Olor</td>
            <td style="text-align:center; ">'.$row->d3.'</td>
            <td style="text-align:center; color:blue;">Sui-generis</td>
            <td style="color:blue; text-align:left; ">Glucosa</td>
            <td style="text-align:center; ">'.$row->d4.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Aspecto</td>
            <td style="text-align:center; ">'.$row->d5.'</td>
            <td style="text-align:center; color:blue;">Limpido o lig opal</td>
            <td style="color:blue; text-align:left; ">C. cetonicos</td>
            <td style="text-align:center; ">'.$row->d6.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Espuma</td>
            <td style="text-align:center; ">'.$row->d7.'</td>
            <td style="text-align:center; color:blue;">Blanco fugaz</td>
            <td style="color:blue; text-align:left; ">Bilirrubina</td>
            <td style="text-align:center; ">'.$row->d8.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Deposito</td>
            <td style="text-align:center; ">'.$row->d9.'</td>
            <td style="text-align:center; color:blue;">Nulo o escacaso</td>
            <td style="color:blue; text-align:left; ">Hemoglobina</td>
            <td style="text-align:center; ">'.$row->d10.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Densidad</td>
            <td style="text-align:center; ">'.$row->d11.'</td>
            <td style="text-align:center; color:blue;">1.010-1.030</td>
            <td style="color:blue; text-align:left; ">Urobilina</td>
            <td style="text-align:center; ">'.$row->d12.'</td>
            <td style="text-align:center; color:blue;">Normal</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Reaccion</td>
            <td style="text-align:center; "> '.$row->d13.'</td>
            <td style="text-align:center; color:blue;">Lig. acida</td>
            <td style="color:blue; text-align:left; ">Nitrinos</td>
            <td style="text-align:center; ">'.$row->d14.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center;color:darkblue; font:bold;">SEDIMENTO: EXAMEN MICROSCOPICO</td>
        </tr>
        <tr>
            <td style="color:darkblue; font:bold; text-align:center; ">CELULAS</td>
            <td style="color:darkblue; font:bold; text-align:center; ">VALOR</td>
            <td style="color:darkblue; font:bold; text-align:center;">REFERENCIA</td>
            <td style="color:darkblue; font:bold; text-align:center;">CILINDROS</td>
            <td style="color:darkblue; font:bold; text-align:center; ">VALOR</td>
            <td style="color:darkblue; font:bold; text-align:center;">REFERENCIA</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Celulas epiteliales</td>
            <td>'.$row->d15.'</td>
            <td style="color:blue; text-align:center; ">Hasta 2/c.</td>
            <td style="color:blue; text-align:left; ">Hialino</td>
            <td>'.$row->d16.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Celulas de transicion</td>
            <td>'.$row->d17.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
            <td style="color:blue; text-align:left; ">Granuloso</td>
            <td>'.$row->d18.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Celulas clave</td>
            <td>'.$row->d19.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
            <td style="color:blue; text-align:left; ">Epiteliales</td>
            <td>'.$row->d20.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Leucocitos</td>
            <td>'.$row->d21.'</td>
            <td style="color:blue; text-align:center; ">Hasta 5/c</td>
            <td style="color:blue; text-align:left; ">Eritrocitario</td>
            <td>'.$row->d22.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Eritrocitos</td>
            <td>'.$row->d23.'</td>
            <td style="color:blue; text-align:center; ">Hasta 3/c</td>
            <td style="color:blue; text-align:left; ">Leucositario</td>
            <td>'.$row->d24.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Bacterias</td>
            <td>'.$row->d25.'</td>
            <td style="color:blue; text-align:center; ">Escaso</td>
            <td style="color:blue; text-align:left; ">Cereos</td>
            <td>'.$row->d26.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:darkblue; text-align:left; font:bold;">CRISTALES</td>
            <td colspan="2"></td>
            <td style="color:blue; text-align:left; ">Mixtos</td>
            <td>'.$row->d27.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Uratos amorfos</td>
            <td>'.$row->d28.'</td>
            <td style="color:blue; text-align:center; ">Escasos</td>
            <td colspan="3" style="color:darkblue; text-align:center; font:bold;">OTROS</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Fosfato amorfo</td>
            <td>'.$row->d29.'</td>
            <td style="color:blue; text-align:center; ">Escasos</td>
            <td style="color:blue; text-align:left; ">Filamento mucoso</td>
            <td colspan="2">'.$row->d30.'</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Oxalato de calcio</td>
            <td>'.$row->d31.'</td>
            <td style="color:blue; text-align:center; ">Escasos</td>
            <td style="color:blue; text-align:left; ">Piocitos</td>
            <td colspan="2">'.$row->d32.'</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Fosfato de calcio</td>
            <td>'.$row->d33.'</td>
            <td style="color:blue; text-align:center; ">Escasos</td>
            <td style="color:blue; text-align:left; ">Levaduras</td>
            <td colspan="2">'.$row->d34.'</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Acido Urico</td>
            <td>'.$row->d35.'</td>
            <td style="color:blue; text-align:center; ">Escasos</td>
            <td style="color:blue; text-align:left; ">Esporas micoticas</td>
            <td colspan="2">'.$row->d36.'</td>
        </tr>
        <tr>
            <td style="color:darkblue; text-align:left; font:bold;">OBSERVACIONES</td>
            <td colspan="5">'.$row->d37.'</td>
        </tr>
        <tr>
            <td rowspan="4" style="color:darkblue; text-align:left; font:bold;">RESPONSABLE</td>
            <td colspan="2" rowspan="4">'.$row->user->name.'</td>
        </tr>
        <tr>
            <td colspan="2" style="color:blue; text-align:left; ">FECHA DE TOMA DE MUESTRAS</td>
            <td style="text-align:center;">'.$row->fechatoma.'</td>
        </tr>
        <tr>
            <td colspan="2" style="color:blue; text-align:left; ">FECHA DE ENTREGA DE MUESTRAS</td>
            <td style="text-align:center;">'.date("Y-m-d").'</td>
        </tr>
        <tr>
            <td colspan="2" style="color:blue; text-align:left; ">HORA TOMA DE MUESTRA</td>
            <td style="text-align:center;">'.date('H:i:s').'</td>
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
     * @param  \App\Models\Orina  $orina
     * @return \Illuminate\Http\Response
     */
    public function show(Orina $orina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orina  $orina
     * @return \Illuminate\Http\Response
     */
    public function edit(Orina $orina)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orina  $orina
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orina $orina)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orina  $orina
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orina $orina)
    {
        //
    }
}
