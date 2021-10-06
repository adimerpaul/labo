<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Sanguinia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class SanguiniaController extends Controller
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
//        return $request->requerido;
        $dato=Sanguinia::create($request->sanguinia+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
       // $input='';
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('sanguinea.pdf');
        //return redirect('/pacientes');
    }
    public function generar($id){
        $row=Sanguinia::with('paciente')->with('user')->with('doctor')
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
        $cd30='';
        //1-2
        if(($row->d1<65 || $row->d1>99)&& $row->d1!='')$cd1='background-color:#ff726f;';
        if(($row->d2<0 || $row->d2>135)&& $row->d2!='')$cd2='background-color:#ff726f;'; 
        //1-2
        if(($row->d3<0.6 || $row->d3>1.4)&& $row->d3!='')$cd3='background-color:#ff726f;';
        if(($row->d4<100 || $row->d4>400)&& $row->d4!='')$cd4='background-color:#ff726f;';
        //1-2
        if(($row->d5<15 || $row->d5>45)&& $row->d5!='')$cd5='background-color:#ff726f;';
        if(($row->d6<0 || $row->d6>40)&& $row->d6!='')$cd6='background-color:#ff726f;'; 
                //1-2
        if(($row->d7<7 || $row->d7>18)&& $row->d7!='')$cd7='background-color:#ff726f;';
        if(($row->d8<0 || $row->d8>41)&& $row->d8!='')$cd8='background-color:#ff726f;';          
        //1-2
        if(($row->d9<2.6 || $row->d9>7.2)&& $row->d9!='')$cd9='background-color:#ff726f;'; 
        //1-2
        if(($row->d10<6.6 || $row->d10>8.6)&& $row->d10!='')$cd10='background-color:#ff726f;';
        if(($row->d11<40 || $row->d11>160) && $row->d11!='')$cd11='background-color:#ff726f;'; 
        //1-2
        if(($row->d12<3.5 || $row->d12>5)&& $row->d12!='')$cd12='background-color:#ff726f;';
        if(($row->d13<0 || $row->d13>=200)&& $row->d13!='')$cd13='background-color:#ff726f;'; 
        //1-2
        if(($row->d14<2.8 || $row->d14>3.5)&& $row->d14!='')$cd14='background-color:#ff726f;';
        if(($row->d15<35 || $row->d15>65)&& $row->d15!='')$cd15='background-color:#ff726f;'; 
        //1-2
        if(($row->d16<0 || $row->d16>=120)&& $row->d16!='')$cd16='background-color:#ff726f;';
        if(($row->d17<0 || $row->d17>150)&& $row->d17!='')$cd17='background-color:#ff726f;'; 
        //1-2
        if(($row->d18<10 || $row->d18>60)&& $row->d18!='')$cd18='background-color:#ff726f;';
        //1-2
        if(($row->d19<0 || $row->d19>1.2)&& $row->d19!='')$cd19='background-color:#ff726f;';
        if(($row->d20<135 || $row->d20>155)&& $row->d20!='')$cd20='background-color:#ff726f;'; 
        //1-2
        if(($row->d21<0 || $row->d21>0.3)&& $row->d21!='')$cd21='background-color:#ff726f;';
        if(($row->d22<98 || $row->d22>106)&& $row->d22!='')$cd22='background-color:#ff726f;'; 
                //1-2
        if(($row->d23<0 || $row->d23>0.9)&& $row->d23!='')$cd23='background-color:#ff726f;';
        if(($row->d24<3.4 || $row->d24>5.3)&& $row->d24!='')$cd24='background-color:#ff726f;'; 
        //1-2
        if(($row->d25<230 || $row->d25>480) && $row->d25!='')$cd25='background-color:#ff726f;';
        if(($row->d26<8.5 || $row->d26>10.5) && $row->d26!='')$cd26='background-color:#ff726f;'; 
        //1-2
        if(($row->d27<50 || $row->d27>170)&& $row->d27!='')$cd27='background-color:#ff726f;';
        if(($row->d28<1.7 || $row->d28>2.4)&& $row->d28!='')$cd28='background-color:#ff726f;';     
                //1-2
        if(($row->d29<0 || $row->d29>147)&& $row->d23!='')$cd29='background-color:#ff726f;';
                //1-2
        if(($row->d30<0 || $row->d30>24)&& $row->d30!='')$cd30='background-color:#ff726f;';                                   
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
        <table style="width: 100%;color: black;padding-top:5px;padding-left: 10px;padding-right: 15px">
        <tr >
            <td rowspan="5" ><img src="./img/natividad.jpeg" style="height: 1.5cm;"></td>
            <td style="text-align:center;color:darkblue; ">SERVICIO DE LABORATORIO </td>
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
            <td style="text-align:center;color:red;">Nº Registro CODELAB 000045</td>
        </tr>
    </table>
        <table class="tablex"  style=" width: 100%;padding-left: 10px;padding-right: 15px">
            <tr>
                <td colspan="3" style="text-align: center;border:0;font:bold; color:darkblue"><h3>QUIMICA SANGUINEA</h3></td>
                <td style="color:red">Form. 002</td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkblue">PACIENTE</td>
                <td class="tdx" style="text-align:center">'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</td>
                <td class="tdx"  style="color: darkblue">EDAD</td>
                <td class="tdx" style="text-align:center">'.$row->paciente->age().'</td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkblue">REQUERIDO POR</td>
                <td class="tdx" style="text-align:center">'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                <td class="tdx"  style="color: darkblue">SEXO</td>
                <td class="tdx" style="text-align:center">'.$row->paciente->sexo.'</td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkblue">TIPO MUESTRA</td>
                <td class="tdx" style="text-align:center">'.$row->tipomuestra.'</td>
                <td class="tdx"  style="color: red">N PACIENTE</td>
                <td class="tdx" style="text-align:center;color:red">'.$row->paciente->id.'</td>
            </tr>
        </table>
        <table class="tablex" style="width: 100%;color: black ;padding-left: 10px;padding-right: 15px" >
            <tr style="color:purple;font-weight: bold">
                <th class="thx">PRUEBA</th>
                <th class="thx">VALOR</th>
                <th class="thx">REFERENCIA</th>
                <th class="thx">PRUEBA</th>
                <th class="thx">VALOR</th>
                <th class="thx">VALOR</th>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue;text-align::left;" >Glicemia</td>
                <td class="tdx" style="text-align:center;'.$cd1.'">'.$row->d1.'</td>
                <td class="tdx" style="color:blue">70-105mg/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Fosfatasa alcalina</td>
                <td class="tdx" style="text-align:center;'.$cd2.'">'.$row->d2.'</td>
                <td class="tdx" style="color:blue">adultos hasta 100UI/L</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Creatinina</td>
                <td class="tdx" style="text-align:center;'.$cd3.'">'.$row->d3.'</td>
                <td class="tdx" style="color:blue">0.7-1.5mg/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Fosfatasa alcalina</td>
                <td class="tdx" style="text-align:center;'.$cd4.'">'.$row->d4.'</td>
                <td class="tdx" style="color:blue">niños 100-400UI/L</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Urea</td>
                <td class="tdx" style="text-align:center;'.$cd5.'">'.$row->d5.'</td>
                <td class="tdx" style="color:blue">15-45mg/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Transamisas GOT</td>
                <td class="tdx" style="text-align:center;'.$cd6.'">'.$row->d6.'</td>
                <td class="tdx" style="color:blue">hasta 40UI/L</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">NUS-BUN</td>
                <td class="tdx" style="text-align:center;'.$cd7.'">'.$row->d7.'</td>
                <td class="tdx" style="color:blue">7-18mg/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Transamisas GPT</td>
                <td class="tdx" style="text-align:center;'.$cd8.'">'.$row->d8.'</td>
                <td class="tdx" style="color:blue">hasta 41UI/L</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Acido Urico</td>
                <td class="tdx" style="text-align:center;'.$cd9.'">'.$row->d9.'</td>
                <td class="tdx" style="color:blue">2.6-7.2mg/dl</td>
                <td class="tdx" style=" text-align:center; font-weight:bold;color:purple;">LIPIDOGRAMA</td>
                <td class="tdx"></td>
                <td class="tdx"></td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Proteinas Totales</td>
                <td class="tdx" style="text-align:center;'.$cd10.'">'.$row->d10.'</td>
                <td class="tdx" style="color:blue">6.2-8.5g/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Trigliceridos</td>
                <td class="tdx" style="text-align:center;'.$cd11.'">'.$row->d11.'</td>
                <td class="tdx" style="color:blue">40-160mg/dl</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Albumina</td>
                <td class="tdx" style="text-align:center;'.$cd12.'">'.$row->d12.'</td>
                <td class="tdx" style="color:blue">3.5-5.3g/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Colesterol Total</td>
                <td class="tdx" style="text-align:center;'.$cd13.'">'.$row->d13.'</td>
                <td class="tdx" style="color:blue">menor 200mg/dl</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Globulina</td>
                <td class="tdx" style="text-align:center;'.$cd14.'">'.$row->d14.'</td>
                <td class="tdx" style="color:blue">2.8-3.5g/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">HDL-Col.</td>
                <td class="tdx" style="text-align:center;'.$cd15.'">'.$row->d15.'</td>
                <td class="tdx" style="color:blue">35-65mg/dl</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Amilasa</td>
                <td class="tdx" style="text-align:center;'.$cd16.'">'.$row->d16.'</td>
                <td class="tdx" style="color:blue">menor a 120 UI/L</td>
                <td class="tdx" style=" color: blue; text-align:left;">LDL-Col.</td>
                <td class="tdx" style="text-align:center;'.$cd17.'">'.$row->d17.'</td>
                <td class="tdx" style="color:blue">Hasta 150mg/dl</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Lipasa</td>
                <td class="tdx" style="text-align:center;'.$cd18.'">'.$row->d18.'</td>
                <td class="tdx" style="color:blue">10-150UI/L</td>
                <td class="tdx" style="font-weight:bold;color:purple; text-align:center; " >ELECTROLITOS</td>
                <td class="tdx"></td>
                <td class="tdx"></td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Bilirrubina Total</td>
                <td class="tdx" style="text-align:center;'.$cd19.'">'.$row->d19.'</td>
                <td class="tdx" style="color:blue">hasta 1.2 mg/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Sodio</td>
                <td class="tdx" style="text-align:center;'.$cd20.'">'.$row->d20.'</td>
                <td class="tdx" style="color:blue">135-155 mEq/L</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Bilirrubina Directa</td>
                <td class="tdx" style="text-align:center;'.$cd21.'">'.$row->d21.'</td>
                <td class="tdx" style="color:blue">hasta 0.3 mg/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Cloro</td>
                <td class="tdx" style="text-align:center;'.$cd22.'">'.$row->d22.'</td>
                <td class="tdx" style="color:blue">98-106 mEq/L</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Bilirrubina Inderecta</td>
                <td class="tdx" style="text-align:center;'.$cd23.'">'.$row->d23.'</td>
                <td class="tdx" style="color:blue">hasta 0.9 mg/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Potasio</td>
                <td class="tdx" style="text-align:center;'.$cd24.'">'.$row->d24.'</td>
                <td class="tdx" style="color:blue">3.4-5.3 mEq/L</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">CK-MB</td>
                <td class="tdx" style="text-align:center;'.$cd25.'">'.$row->d25.'</td>
                <td class="tdx" style="color:blue">0-24 UI/L</td>
                <td class="tdx" style=" color: blue; text-align:left;">Calcio</td>
                <td class="tdx" style="text-align:center;'.$cd26.'">'.$row->d26.'</td>
                <td class="tdx" style="color:blue">8.5-10.5mg/dl</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">LDH</td>
                <td class="tdx" style="text-align:center;'.$cd27.'">'.$row->d27.'</td>
                <td class="tdx" style="color:blue">200-480UI/L</td>
                <td class="tdx" style=" color: blue; text-align:left;">Magnesio</td>
                <td class="tdx" style="text-align:center;'.$cd28.'">'.$row->d28.'</td>
                <td class="tdx" style="color:blue">1.7-2.4mg/dl</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: blue; text-align:left;">Hierro</td>
                <td class="tdx" style="text-align:center;'.$cd29.'">'.$row->d29.'</td>
                <td class="tdx" style="color:blue">50-170ug/dl</td>
                <td class="tdx" style=" color: blue; text-align:left;">Fosforo</td>
                <td class="tdx" style="text-align:center;'.$cd30.'">'.$row->d30.'</td>
                <td class="tdx" style="color:blue">2.5-4.5mg/dl</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="font-weight:bold;color:blue; text-align:center;">OBSERVACIONES</td>
                <td class="tdx" colspan="4">'.$row->d31.' </td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" rowspan="3" style="font-weight:bold;color:blue; text-align:center;">RESPONSABLE</td>
                <td class="tdx" colspan="2" rowspan="3">
                    '.$row->user->name.'
                </td>
                <td class="tdx"  style="font-weight:bold;color:blue; text-align:center;">FECHA TOMA DE MUESTRA</td>
                <td class="tdx">'.$row->fechatoma.'</td>
            </tr>
            <tr>
                <td class="tdx"  style="font-weight:bold;color:blue; text-align:center;">HORA DE TOMA DE MUESTRA</td>
                <td class="tdx">'.$row->horatoma.'</td>
            </tr>
            <tr>
                <td class="tdx"  style="font-weight:bold;color:blue; text-align:center;">FECHA DE ENTREGA DE RESULTADO</td>
                <td class="tdx">'.date('Y-m-d').'</td>
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
     * @param  \App\Models\sanguinia  $sanguinia
     * @return \Illuminate\Http\Response
     */
    public function show(sanguinia $sanguinia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sanguinia  $sanguinia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, sanguinia $sanguinia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sanguinia  $sanguinia
     * @return \Illuminate\Http\Response
     */
    public function destroy(sanguinia $sanguinia)
    {
        //
    }
}
