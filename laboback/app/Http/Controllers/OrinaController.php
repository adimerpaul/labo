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
        ->get()[0];
        $cd1='';
        $cd2='';
        $cd3='';
        $cd4='';
        $cd5='';
        $cd6='';
        $cd7='';
        $cd8='';
        $cd9='';
        $cd10='';
        $cd11='';
        $cd12='';
        $cd13='';
        $cd14='';
        $cd15='';
        $cd16='';
        $cd17='';
        $cd18='';
        $cd19='';
        $cd20='';
        $cd21='';
        $cd22='';
        $cd23='';
        $cd24='';
        $cd25='';
        $cd26='';
        $cd27='';
        $cd28='';
        $cd29='';
        $cd30='';
        $cd31='';
        $cd32='';
        $cd33='';
        $cd34='';
        $cd35='';
        $cd36='';
        $cd37='';
        if($row->d1!='AMARILLO' ) $cd1='color:#ff726f;';
        if($row->d2!='NEGATIVO' ) $cd2='color:#ff726f;';
        if($row->d3!='SUI-GENERIS' ) $cd3='color:#ff726f;';
        if($row->d4!='NEGATIVO' ) $cd4='color:#ff726f;';
        if($row->d5!='LIPIDO O LIG OPAL' ) $cd5='color:#ff726f;';
        if($row->d6!='NEGATIVO' ) $cd6='color:#ff726f;';
        if($row->d7!='BLANCO FUGAZ' ) $cd7='color:#ff726f;';
        if($row->d8!='NEGATIVO' ) $cd8='color:#ff726f;';
        if($row->d9!='NULO O ESCASO' ) $cd9='color:#ff726f;';
        if($row->d10!='NEGATIVO' ) $cd10='color:#ff726f;';
        if($row->d12!='NORMAL' ) $cd12='color:#ff726f;';
        if($row->d13!='LIG. ACIDA' ) $cd13='color:#ff726f;';
        if($row->d14!='NEGATIVO' ) $cd14='color:#ff726f;';
        if(($row->d11<1010 || $row->d11>1030)&& $row->d11!='')$cd11='color:#ff726f;';
        if($row->d15!='N.S.O.') $cd15='color:#ff726f;';
        if($row->d16!='N.S.O.') $cd16='color:#ff726f;';
        if($row->d17!='N.S.O.') $cd17='color:#ff726f;';
        if($row->d18!='N.S.O.') $cd18='color:#ff726f;';
        if($row->d19!='N.S.O.') $cd19='color:#ff726f;';
        if($row->d20!='N.S.O.') $cd20='color:#ff726f;';
        if($row->d21!='N.S.O.') $cd21='color:#ff726f;';
        if($row->d22!='N.S.O.') $cd22='color:#ff726f;';
        if($row->d23!='N.S.O.') $cd23='color:#ff726f;';
        if($row->d24!='N.S.O.') $cd24='color:#ff726f;';
        if($row->d25!='ESCASO') $cd25='color:#ff726f;';
        if($row->d26!='N.S.O.') $cd26='color:#ff726f;';
        if($row->d27!='N.S.O.') $cd27='color:#ff726f;';
        if($row->d28!='N.S.O.') $cd28='color:#ff726f;';
        if($row->d29!='N.S.O.') $cd29='color:#ff726f;';
        if($row->d31!='N.S.O.') $cd31='color:#ff726f;';
        if($row->d33!='N.S.O.') $cd33='color:#ff726f;';
        if($row->d35!='N.S.O.') $cd35='color:#ff726f;';



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
                margin-rigth:10px;

            }
</style>
<table style="width: 100%; border:0;">
<tr style="border:0;">
<td style="width: 50%; border:0;"></td>
<td style="border:0;">
    <table style="padding-right:10px;padding-left:0px;width: 100%;margin-top: 5px;border:0">
        <tr >
            <td rowspan="3" style="border:0; "><img src="./img/natividad.jpeg" alt="Logo Clinica" style="height: 1.5cm; "></td>
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
    <table style="padding-right:10px;padding-left:0px;width: 100%;">
        <tr>
            <td colspan="3" style="text-align: center; border:0; font:bold; color: darkblue;">EXAMEN GENERAL DE ORINA</td>
            <td style="color:darkblue; text-align:center;">Form. 003</td>
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
    <table style="padding-right:10px;padding-left:0px;width: 100%;">
        <tr>
            <td style="color:darkblue; text-align:center; font:bold; ">EX. FISICO</td>
            <td style="color:darkblue; text-align:center; font:bold; ">VALOR</td>
            <td style="color:darkblue; text-align:center; font:bold; ">REFERENCIA</td>
            <td style="color:darkblue; text-align:center; font:bold; ">EX. QUIMICO</td>
            <td style="color:darkblue; text-align:center; font:bold; ">VALOR</td>
            <td style="color:darkblue; text-align:center; font:bold; ">REFERENCIA</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Color</td>
            <td style="text-align:center; '.$cd1.'">'.$row->d1.'</td>
            <td style="text-align:center; color:blue;">Amarillo</td>
            <td style="color:blue; text-align:left; ">Proteinas</td>
            <td style="text-align:center; '.$cd2.'">'.$row->d2.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Olor</td>
            <td style="text-align:center; '.$cd3.'">'.$row->d3.'</td>
            <td style="text-align:center; color:blue;">Sui-generis</td>
            <td style="color:blue; text-align:left; ">Glucosa</td>
            <td style="text-align:center; '.$cd4.'">'.$row->d4.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Aspecto</td>
            <td style="text-align:center;'.$cd5.' ">'.$row->d5.'</td>
            <td style="text-align:center; color:blue;">Limpido o lig opal</td>
            <td style="color:blue; text-align:left; ">C. cetonicos</td>
            <td style="text-align:center;'.$cd6.' ">'.$row->d6.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Espuma</td>
            <td style="text-align:center;'.$cd7.' ">'.$row->d7.'</td>
            <td style="text-align:center; color:blue;">Blanco fugaz</td>
            <td style="color:blue; text-align:left; ">Bilirrubina</td>
            <td style="text-align:center; '.$cd8.'">'.$row->d8.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Deposito</td>
            <td style="text-align:center; '.$cd9.'">'.$row->d9.'</td>
            <td style="text-align:center; color:blue;">Nulo o escaso</td>
            <td style="color:blue; text-align:left; ">Hemoglobina</td>
            <td style="text-align:center; '.$cd10.'">'.$row->d10.'</td>
            <td style="text-align:center; color:blue;">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Densidad</td>
            <td style="text-align:center;'.$cd11.' ">'.$row->d11.'</td>
            <td style="text-align:center; color:blue;">1.010-1.030</td>
            <td style="color:blue; text-align:left; ">Urobilina</td>
            <td style="text-align:center; '.$cd12.'">'.$row->d12.'</td>
            <td style="text-align:center; color:blue;">Normal</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Reaccion</td>
            <td style="text-align:center; '.$cd13.'"> '.$row->d13.'</td>
            <td style="text-align:center; color:blue;">Lig. acida</td>
            <td style="color:blue; text-align:left; ">Nitrinos</td>
            <td style="text-align:center;'.$cd14.' ">'.$row->d14.'</td>
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
            <td style="text-align:center; '.$cd15.'">'.$row->d15.'</td>
            <td style="color:blue; text-align:center; ">Hasta 2/c.</td>
            <td style="color:blue; text-align:left; ">Hialino</td>
            <td style="text-align:center; '.$cd16.'">'.$row->d16.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Celulas de transicion</td>
            <td style="text-align:center; '.$cd17.'">'.$row->d17.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
            <td style="color:blue; text-align:left; ">Granuloso</td>
            <td style="text-align:center; '.$cd18.'">'.$row->d18.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Celulas clave</td>
            <td style="text-align:center; '.$cd19.'">'.$row->d19.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
            <td style="color:blue; text-align:left; ">Epiteliales</td>
            <td style="text-align:center; '.$cd20.'">'.$row->d20.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Leucocitos</td>
            <td style="text-align:center; '.$cd21.'">'.$row->d21.'</td>
            <td style="color:blue; text-align:center; ">Hasta 5/c</td>
            <td style="color:blue; text-align:left; ">Eritrocitario</td>
            <td style="text-align:center; '.$cd22.'">'.$row->d22.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Eritrocitos</td>
            <td style="text-align:center; '.$cd23.'">'.$row->d23.'</td>
            <td style="color:blue; text-align:center; ">Hasta 3/c</td>
            <td style="color:blue; text-align:left; ">Leucositario</td>
            <td style="text-align:center; '.$cd24.'">'.$row->d24.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Bacterias</td>
            <td style="text-align:center; '.$cd25.'">'.$row->d25.'</td>
            <td style="color:blue; text-align:center; ">Escaso</td>
            <td style="color:blue; text-align:left; ">Cereos</td>
            <td style="text-align:center; '.$cd26.'">'.$row->d26.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:darkblue; text-align:left; font:bold;">CRISTALES</td>
            <td colspan="2"></td>
            <td style="color:blue; text-align:left; ">Mixtos</td>
            <td style="text-align:center; '.$cd27.'">'.$row->d27.'</td>
            <td style="color:blue; text-align:center; ">Negativo</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Uratos amorfos</td>
            <td style="text-align:center; '.$cd28.'">'.$row->d28.'</td>
            <td style="color:blue; text-align:center; ">Escaso</td>
            <td colspan="3" style="color:darkblue; text-align:center; font:bold;">OTROS</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Fosfato amorfo</td>
            <td style="text-align:center; '.$cd29.'">'.$row->d29.'</td>
            <td style="color:blue; text-align:center; ">Escaso</td>
            <td style="color:blue; text-align:left; ">Filamento mucoso</td>
            <td style="text-align:center; " colspan="2">'.$row->d30.'</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Oxalato de calcio</td>
            <td style="text-align:center; '.$cd31.'">'.$row->d31.'</td>
            <td style="color:blue; text-align:center; ">Escaso</td>
            <td style="color:blue; text-align:left; ">Piocitos</td>
            <td colspan="2" style="text-align:center; "> '.$row->d32.'</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Fosfato de calcio</td>
            <td style="text-align:center;'.$cd33.' ">'.$row->d33.'</td>
            <td style="color:blue; text-align:center; ">Escaso</td>
            <td style="color:blue; text-align:left; ">Levaduras</td>
            <td colspan="2" style="text-align:center; ">'.$row->d34.'</td>
        </tr>
        <tr>
            <td style="color:blue; text-align:left; ">Acido Urico</td>
            <td style="text-align:center; '.$cd35.'">'.$row->d35.'</td>
            <td style="color:blue; text-align:center; ">Escaso</td>
            <td style="color:blue; text-align:left; ">Esporas micoticas</td>
            <td colspan="2" style="text-align:center; ">'.$row->d36.'</td>
        </tr>
        <tr>
            <td style="color:darkblue; text-align:left; font:bold;">OBSERVACIONES</td>
            <td colspan="5" >'.$row->d37.'</td>
        </tr>
        <tr>
            <td rowspan="4" style="color:darkblue; text-align:left; font:bold;">RESPONSABLE</td>
            <td colspan="2" rowspan="4">'.$row->responsable.'</td>
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

        </td>        </tr>
        </table>
        ';
        //return $cadena;
        $pdf = App::make('dompdf.wrapper');
        //        $customPaper = array(0,0,360,360);
                $pdf->setPaper('legal','landscape');
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
