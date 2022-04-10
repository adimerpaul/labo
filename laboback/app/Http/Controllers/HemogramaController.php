<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Hemograma;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class HemogramaController extends Controller
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
//        echo "Hola soy sotre de homograma";
//        exit;
//        $datos=$request->all();
//        $datos->user_id=Auth::user()->id;
////        array_push($datos,array('user_id'=>1));
//        return $request->requerido;
        //return $request;
        $dato=Hemograma::create($request->hemograma + ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadHTML($this->generar($dato->id));
//        return $pdf->download('Hemograma.pdf');
        //return redirect('/pacientes');
    }

    public function generar($id){
//        return $id;
        $row= Hemograma::with('paciente')->with('user')->with('doctor')
        ->where('id',$id)
        ->get();
        $row=$row[0];
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
        //1-2
        if($row->paciente->sexo=='Masculino' && $row->d1!='' && ($row->d1<5.1 || $row->d1>5.7))$cd1='color:red;';
        if($row->paciente->sexo=='Femenino' && $row->d1!='' && ($row->d1<4.8 || $row->d1>5.4))$cd1='color:red;';
        if(($row->d2<5 || $row->d2>10) && $row->d2!='') $cd2='color:red;';
//3-4
        if($row->paciente->sexo=='Masculino' && $row->d3!='' && ($row->d3<151 || $row->d3>175))$cd3='color:red;';
        if($row->paciente->sexo=='Femenino' && $row->d3!='' && ($row->d3<141 || $row->d3>165))$cd3='color:red;';
        if(($row->d4<1 || $row->d4>3) && $row->d4!='') $cd4='color:red;';
//5-6
        if($row->paciente->sexo=='Masculino' && $row->d5!='' && ($row->d5<0.51 || $row->d5>0.57))$cd5='color:red;';
        if($row->paciente->sexo=='Femenino' && $row->d5!='' && ($row->d5<0.46 || $row->d5>0.53))$cd5='color:red;';
        if(($row->d6<12 || $row->d6>13) && $row->d6!='') $cd6='color:red;';
//7-8
        if($row->paciente->sexo=='Masculino' && $row->d7!='' && ($row->d7<0 || $row->d7>15))$cd7='color:red;';
        if($row->paciente->sexo=='Femenino' && $row->d7!='' && ($row->d7<0 || $row->d7>20))$cd7='color:red;';
        if(($row->d8 <95 || $row->d8>100) && $row->d8!='') $cd8='color:red;';
//9-10
        if(($row->d9<83 || $row->d9>97) && $row->d9!='')$cd9='color:red;';
        if(($row->d10<0.97 || $row->d10>1.04) && $row->d10!='') $cd10='color:red;';
        //11-12
        if(($row->d11<27 || $row->d11>31) && $row->d11!='')$cd11='color:red;';
        //13-14
        if(($row->d13<32 || $row->d13>36) && $row->d13!='')$cd13='color:red;';
        if(($row->d14<0.5 || $row->d14>2) && $row->d14!='') $cd14='color:red;';
        //15-16
        if(($row->d15<4.5 || $row->d15>10.5) && $row->d15!='')$cd15='color:red;';
        //17
        if(($row->d17<150 || $row->d17>400) && $row->d17!='')$cd17='color:red;';
        //18-19
        if(($row->d18<0 || $row->d18>3) && $row->d18!='')$cd18='color:red;';
        if(($row->d19<0 || $row->d19>0.35) && $row->d19!='')$cd19='color:red;';
                //20-21
        if(($row->d20<50 || $row->d20>70) && $row->d20!='')$cd20='color:red;';
        if(($row->d21<2.5 || $row->d21>7.35) && $row->d21!='')$cd21='color:red;';
        //22-23
        if(($row->d22<0 || $row->d22>3) && $row->d22!='')$cd22='color:red;';
        if(($row->d23<0 || $row->d23>0.35) && $row->d23!='')$cd23='color:red;';
        //24-25
        if(($row->d24<0 || $row->d24>1) && $row->d24!='')$cd24='color:red;';
        if(($row->d25<0 || $row->d25>0.15) && $row->d25!='')$cd25='color:red;';
        //26-27
        if(($row->d26<25 || $row->d26>40) && $row->d26!='')$cd26='color:red;';
        if(($row->d27<1.25 || $row->d27>4.2) && $row->d27!='')$cd27='color:red;';
        //28-29
        if(($row->d28<4 || $row->d28>8) && $row->d28!='')$cd28='color:red;';
        if(($row->d29<2 || $row->d29>8.4) && $row->d29!='')$cd29='color:red;';

        $cadena='
        <style>
.tablex , th , .tdx  {
  border: 0.2px solid black;
  border-collapse: collapse;
}
table{
    width: 50%
}
*{
padding: 0px;
margin: 0px;
border: 0px;
font-size: 13px;
}
.tnavy{
    color:navy;
    font: bold;
    text-align:center;
}
.tazul{
    color:darkblue;

}
</style>

<table style="width: 100%">
<tr>
<td style="width: 50%"></td>
<td>

        <table style="padding-right:10px;padding-left:0px;width: 100%;margin-top: 5px;" align="right">
            <tr>
                <td rowspan="3" ><img src="./img/natividad.jpeg" style="height: 1.5cm; "></td>
                <td style="text-align:center; color:darkblue;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="text-align:center; color:darkblue;">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
            <tr>
                <td style="text-align:center; color:darkblue;">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
            <tr>
            <td style="text-align:left; color:red;">N Regsitro CODEDLAB 000045 </td>
            <td style="text-align:center; color:darkblue;">Emergencia las 24 horas del dia. </td>

            </tr>
        </table>
        <table style="padding-right:10px;padding-left:0px;width: 100%;" class="tablex">
            <tr >
                <td class="tdx tnavy" colspan="3" ><h3>HEMOGRAMA COMPLETO</h3></td>
                <td class="tdx" style="color:red">Form. 001</td>
            </tr>
            <tr>
                <td class="tdx"  style="color:darkblue;">PACIENTE</td>
                <td class="tdx" style="text-align:center" >'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</td>
                <td class="tdx"  style="color:darkblue;">EDAD</td>
                <td class="tdx" style="text-align:center">'.$row->paciente->age().'</td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkblue">REQUERIDO POR</td>
                <td class="tdx" style="text-align:center" >'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                <td class="tdx"  style="color: darkblue">SEXO</td>
                <td class="tdx" style="text-align:center">'.$row->paciente->sexo.'</td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkblue">TIPO MUESTRA</td>
                <td class="tdx" style="text-align:center">'.$row->tipomuestra.'</td>
                <td class="tdx"  style="color: red">N PACIENTE</td>
                <td class="tdx" style="text-align:center; color:red">'.$row->paciente->id.'</td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkblue">METODO</td>
                <td class="tdx"  colspan="3" align="center" style="color: darkblue">
                    Contador Hematologico MINDRAY BC 5130
                </td>
            </tr>
        </table>
        <table class="tablex"  style="padding-right:10px;padding-left:0px;width: 100%;color: black;">
            <tr>
                <td class="tdx" style="width:10%"></td>
                <td class="tdx" style="width:10%"></td>
                <td class="tdx" style="width:10%"></td>
                <td class="tdx" style="color: NAVY;font: bold" align="center">REF</td>
                <td class="tdx" style="width:10%"></td>
                <td class="tdx" style="width:10%"></td>
                <td class="tdx" style="color: NAVY;font: bold" align="center">REF</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Globulos Rojos</td>
                <td class="tdx" style="text-align:center;'.$cd1.'">'.$row->d1.'</td>
                <td class="tdx" style="color: darkblue;" align="center">x10 <sup>12 </sup>/L</td>
                <td class="tdx" style="color: darkblue; font-size: 10px;" align="center">Varon 5.1-5.7x10 <sup>12</sup>/L <br> Mujer 4.8-5.4x10 <sup>12</sup>/L</td>
                <td class="tdx" style="color: darkblue;" align="center">Tiempo de cuagulacion</td>
                <td class="tdx" style="text-align:center;'.$cd2.'">'.$row->d2.'</td>
                <td class="tdx" style="color: darkblue" align="center">5-10 min</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Hemoglobina</td>
                <td class="tdx" style="text-align:center;'.$cd3.'">'.$row->d3.'</td>
                <td class="tdx" style="color: darkblue" align="center">g/L</td>
                <td class="tdx" style="color: darkblue; font-size: 10px;" align="center">Varon 151-175 g/L <br> Mujer Mujer 141-165 g/L</td>
                <td class="tdx" style="color: darkblue" align="center">Tiempo de sangria</td>
                <td class="tdx" style="text-align:center;'.$cd4.'">'.$row->d4.'</td>
                <td class="tdx" style="color: darkblue" align="center">1-3 min</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Hematocrito</td>
                <td class="tdx" style="text-align:center;'.$cd5.'">'.$row->d5.'</td>
                <td class="tdx" style="color: darkblue" align="center">L/L</td>
                <td class="tdx" style="color: darkblue; font-size: 10px;" align="center">Varon 0.51-0.57 L/L <br> Mujer 0.46-0.53 L/L</td>
                <td class="tdx" style="color: darkblue" align="center">Tiempo de Protrombina</td>
                <td class="tdx" style="text-align:center;'.$cd6.'">'.$row->d6.'</td>
                <td class="tdx" style="color: darkblue" align="center">12-13 seg</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">V.E.S.</td>
                <td class="tdx" style="text-align:center;'.$cd8.'">'.$row->d7.'</td>
                <td class="tdx" style="color: darkblue" align="center">mm.</td>
                <td class="tdx" style="color: darkblue; font-size: 10px;" align="center">Varon 15 mm/hora <br> Mujer 20 mm/hora</td>
                <td class="tdx" style="color: darkblue" align="center">% Actividad</td>
                <td class="tdx" style="text-align:center;'.$cd8.'">'.$row->d8.'</td>
                <td class="tdx" style="color: darkblue" align="center">95-100%</td>
            </tr>

            <tr>
                <td class="tdx" style="color: darkblue" align="center">V.C.M.</td>
                <td class="tdx" style="text-align:center;'.$cd9.'">'.$row->d9.'</td>
                <td class="tdx" style="color: darkblue" align="center">ft.</td>
                <td class="tdx" style="color: darkblue" align="center">Varon 83.0-97.0 ft</td>
                <td class="tdx" style="color: darkblue" align="center">INR</td>
                <td class="tdx" style="text-align:center;'.$cd10.'">'.$row->d10.'</td>
                <td class="tdx" style="color: darkblue" align="center">0.97-1.04</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Hb.C.M.</td>
                <td class="tdx" style="text-align:center;'.$cd11.'">'.$row->d11.'</td>
                <td class="tdx" style="color: darkblue" align="center" >pg.</td>
                <td class="tdx" style="color: darkblue" align="center" >27.0-31.0 pg.</td>
                <td class="tdx" style="color: darkblue" align="center">Grupofactor</td>
                <td class="tdx" style="text-align:center" colspan="2">'.$row->d12.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">C. Hb.C.M.</td>
                <td class="tdx" style="text-align:center;'.$cd13.'">'.$row->d13.'</td>
                <td class="tdx" style="color: darkblue" align="center">%</td>
                <td class="tdx" style="color: darkblue" align="center">32-36%</td>
                <td class="tdx" style="color: darkblue" align="center">Reticulocitos</td>
                <td class="tdx" style="text-align:center;'.$cd14.'">'.$row->d14.'</td>
                <td class="tdx" style="color: darkblue" align="center">0.5-2%</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Globulos Blancos</td>
                <td class="tdx" style="text-align:center;'.$cd15.'">'.$row->d15.'</td>
                <td class="tdx" style="color: darkblue" align="center">10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkblue" align="center"> 4.5-10.5x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkblue" align="center">IPR</td>
                <td class="tdx" style="text-align:center;'.$cd16.'">'.$row->d16.'</td>
                <td class="tdx"></td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Plaquetas</td>
                <td class="tdx" style="text-align:center;'.$cd17.'">'.$row->d17.'</td>
                <td class="tdx" style="color: darkblue" align="center">x10 <sup>9 </sup>/L</td>
                <td class="tdx" style="color: darkblue" align="center">105-400x10 <sup>9</sup> /L</td>
                <td class="tdx" colspan="3"></td>
            </tr>
        </table>
        <table class="tablex" border="1" style="padding-right:10px;padding-left:0px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="5"></td>
                <td class="tdx" colspan="2" style="color: navy;font: bold" align="center" >VALOR REFERENCIAL</td>
            </tr>
            <tr>
                <td class="tdx" ></td>
                <td class="tdx" colspan="2" style="color: navy;font: bold" align="center">RELATIVA</td>
                <td class="tdx" colspan="2" style="color: navy;font: bold" align="center">ABSOLUTA</td>
                <td class="tdx" style="color: navy;font: bold" align="center">RELATIVA</td>
                <td class="tdx" style="color: navy;font: bold" align="center">ABSOLUTA</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Cayados</td>
                <td class="tdx" style="text-align:center;'.$cd18.'">'.$row->d18.'</td>
                <td class="tdx" style="color: darkblue" align="center">%</td>
                <td class="tdx" style="text-align:center;'.$cd19.'">'.$row->d19.'</td>
                <td class="tdx" style="color: darkblue" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkblue" align="center">0-3%</td>
                <td class="tdx" style="color: darkblue" align="center">0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Neutrofilos</td>
                <td class="tdx" style="text-align:center;'.$cd20.'">'.$row->d20.'</td>
                <td class="tdx" style="color: darkblue" align="center">%</td>
                <td class="tdx" style="text-align:center;'.$cd21.'">'.$row->d21.'</td>
                <td class="tdx" style="color: darkblue" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkblue" align="center">50-70%</td>
                <td class="tdx" style="color: darkblue" align="center">2.50-7.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Eosinofilos</td>
                <td class="tdx" style="text-align:center;'.$cd22.'">'.$row->d22.'</td>
                <td class="tdx" style="color: darkblue" align="center">%</td>
                <td class="tdx" style="text-align:center;'.$cd23.'">'.$row->d23.'</td>
                <td class="tdx" style="color: darkblue" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkblue" align="center">0-3%</td>
                <td class="tdx" style="color: darkblue" align="center">0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Basofilos</td>
                <td class="tdx" style="text-align:center;'.$cd24.'">'.$row->d24.'</td>
                <td class="tdx" style="color: darkblue" align="center">%</td>
                <td class="tdx" style="text-align:center;'.$cd25.'">'.$row->d25.'</td>
                <td class="tdx" style="color: darkblue" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkblue" align="center">0-1%</td>
                <td class="tdx" style="color: darkblue" align="center">0.00-0.15x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Linfocitos</td>
                <td class="tdx" style="text-align:center;'.$cd26.'">'.$row->d26.'</td>
                <td class="tdx" style="color: darkblue" align="center">%</td>
                <td class="tdx" style="text-align:center;'.$cd27.'">'.$row->d27.'</td>
                <td class="tdx" style="color: darkblue" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkblue" align="center">25-40%</td>
                <td class="tdx" style="color: darkblue" align="center">1.25-4.200x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">Monocitos</td>
                <td class="tdx" style="text-align:center;'.$cd28.'">'.$row->d28.'</td>
                <td class="tdx" style="color: darkblue" align="center">%</td>
                <td class="tdx" style="text-align:center;'.$cd29.'">'.$row->d29.'</td>
                <td class="tdx" style="color: darkblue" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkblue" align="center">4-8%</td>
                <td class="tdx" style="color: darkblue" align="center">2.00-8.40x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue" align="center">BLASTOS</td>
                <td class="tdx" style="text-align:center;">'.$row->d30.'</td>
                <td class="tdx" style="color: darkblue" align="center">%</td>
                <td class="tdx"></td>
                <td class="tdx"></td>
                <td class="tdx"></td>
                <td class="tdx"></td>
            </tr>
        </table>
        <table class="tablex" style="padding-right:10px;padding-left:0px;width: 100%;color: black">
            <tr>
                <td class="tdx" style="color: navy;font: bold"align="center" colspan="4">MORFOLOGIA DE FROTIS DE SANGRE PERIFERICA</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue; width:20%" align="center; " >Serie Rojas:</td>
                <td class="tdx" colspan=3 >'.$row->d31.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue; width:20%" align="center">Serie Blancas:</td>
                <td class="tdx" colspan=3>'.$row->d32.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue; width:20%" align="center">Serie Plaquetarias:</td>
                <td class="tdx" colspan=3>'.$row->d33.'</td>
            </tr>

            <tr>
                <td class="tdx" style="color: darkblue;" align="center" rowspan="3"01 colspan=2>Responsable: '.$row->responsable.'</td>
                <td class="tdx" style="color: darkblue; align="center">FECHA DE TOMA DE MUESTRA:</td>
                <td class="tdx">'.$row->fechatoma.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue; align="center">HORA DE TOMA MUESTRA:</td>
                <td class="tdx">'.$row->horatoma.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkblue; align="center">FECHA DE ENTREGA RESULTADO:</td>
                <td class="tdx">'.date('Y-m-d').'</td>
            </tr>

        </table>

</td>
</tr>
</table>


';

        $pdf = App::make('dompdf.wrapper');
//        $customPaper = array(0,0,360,360);
        $pdf->setPaper('legal','landscape');
        $pdf->loadHTML($cadena);
        return $pdf->stream();

//        PDF::loadHTML($cadena)->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile.pdf');
//        $pdf = PDF::loadView('pdf.invoice', $cadena);
//        return $pdf->download('invoice.pdf');
//        return $pdf->download('invoice.pdf');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hemograma  $hemograma
     * @return \Illuminate\Http\Response
     */
    public function show(Hemograma $hemograma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hemograma  $hemograma
     * @return \Illuminate\Http\Response
     */
    public function edit(Hemograma $hemograma)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hemograma  $hemograma
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hemograma $hemograma)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hemograma  $hemograma
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hemograma $hemograma)
    {
        //
    }
}
