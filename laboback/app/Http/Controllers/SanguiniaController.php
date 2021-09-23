<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Hemograma;
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
            <td rowspan="4" ><img src="./img/natividad2.png" style="height: 10px; width:200px;"></td>
            <td style="text-align:center; height:0.5cm;">SERVICIO DE LABORATORIO </td>
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
        <table class="tablex"  style="border=1; width: 100%;color: black;padding-left: 10px;padding-right: 15px">
            <tr>
                <td colspan="3" style="text-align: center"><h3>QUIMICA SANGUINEA</h3></td>
                <td>Form. '.$row->id.'</td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkblue">PACIENTE</td>
                <td class="tdx" >'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</td>
                <td class="tdx"  style="color: darkblue">EDAD</td>
                <td class="tdx" >'.$row->paciente->age().'</td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkblue">REQUERIDO POR</td>
                <td class="tdx" >'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                <td class="tdx"  style="color: darkblue">SEXO</td>
                <td class="tdx" >'.$row->paciente->sexo.'</td>
            </tr>
            <tr>
                <td class="tdx"  style="color: darkblue">TIPO MUESTRA</td>
                <td class="tdx" >'.$row->tipomuestra.'</td>
                <td class="tdx"  style="color: darkblue">N PACIENTE</td>
                <td class="tdx" >'.$row->paciente->id.'</td>
            </tr>
        </table>
        <table class="tablex" style="width: 100%;color: black ;padding-left: 10px;padding-right: 15px" >
            <tr style="color:purple;font-weight: bold">
                <th class="thx">PRUEBA</th>
                <th class="thx">VALOR</th>
                <th class="thx">REFERENCIA</th>
                <th class="thx">METODO</th>
                <th class="thx">PRUEBA</th>
                <th class="thx">VALOR</th>
                <th class="thx">VALOR</th>
                <th class="thx">METODO</th>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred;text-align:center;" >Glicemia</td>
                <td class="tdx">'.$row->d1.'</td>
                <td class="tdx">70-105mg/dl</td>
                <td class="tdx">Glucosa Oxidasa</td>
                <td class="tdx" style=" color: darkred; text-align:center;">Fosfatasa alcalina</td>
                <td class="tdx">'.$row->d2.'</td>
                <td class="tdx">adultos hasta 100UI/L</td>
                <td class="tdx">Cinetico</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Creatinina</td>
                <td class="tdx">'.$row->d3.'</td>
                <td class="tdx">0.7-1.5mg/dl</td>
                <td class="tdx">Picrato Alcalino</td>
                <td class="tdx" style=" color: darkred; text-align:center;">Fosfatasa alcalina</td>
                <td class="tdx">'.$row->d4.'</td>
                <td class="tdx">niños 100-400UI/L</td>
                <td class="tdx">Cinetico</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Urea</td>
                <td class="tdx">'.$row->d5.'</td>
                <td class="tdx">15-45mg/dl</td>
                <td class="tdx">Enzimatico UV</td>
                <td class="tdx" style=" color: darkred; text-align:center;">Transamisas GOT</td>
                <td class="tdx">'.$row->d6.'</td>
                <td class="tdx">hasta 40UI/L</td>
                <td class="tdx">Cinetico</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">NUS-BUN</td>
                <td class="tdx">'.$row->d7.'</td>
                <td class="tdx">7-18mg/dl</td>
                <td class="tdx">Cinetico UV</td>
                <td class="tdx" style=" color: darkred; text-align:center;">Transamisas GPT</td>
                <td class="tdx">'.$row->d8.'</td>
                <td class="tdx">hasta 41UI/L</td>
                <td class="tdx">Cinetico</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Acido Urico</td>
                <td class="tdx">'.$row->d9.'</td>
                <td class="tdx">2.6-7.2mg/dl</td>
                <td class="tdx">Uricasa/Peroxidasa</td>
                <td class="tdx" style=" text-align:center; font-weight:bold;color:purple;">LIPIDOGRAMA</td>
                <td class="tdx"></td>
                <td class="tdx"></td>
                <td class="tdx"></td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Proteinas Totales</td>
                <td class="tdx">'.$row->d10.'</td>
                <td class="tdx">6.2-8.5g/dl</td>
                <td class="tdx">Biuret</td>
                <td class="tdx" style=" color: darkred; text-align:center;">Trigliceridos</td>
                <td class="tdx">'.$row->d11.'</td>
                <td class="tdx">40-160mg/dl</td>
                <td class="tdx">GPO-PAP</td>
            </tr>
            <tr>
                <td class="tdx"" style=" color: darkred; text-align:center;">Albumina</td>
                <td class="tdx">'.$row->d12.'</td>
                <td class="tdx">3.5-5.3g/dl</td>
                <td class="tdx">Verde Bromocresol</td>
                <td class="tdx"" style=" color: darkred; text-align:center;">Colesterol Total</td>
                <td class="tdx">'.$row->d13.'</td>
                <td class="tdx">menor 200mg/dl</td>
                <td class="tdx">CHOD-PAP</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Globulina</td>
                <td class="tdx">'.$row->d14.'</td>
                <td class="tdx">2.8-3.5g/dl</td>
                <td class="tdx"></td>
                <td class="tdx" style=" color: darkred; text-align:center;">HDL-Col.</td>
                <td class="tdx">'.$row->d15.'</td>
                <td class="tdx">35-65mg/dl</td>
                <td class="tdx">CHOD-PAP</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Amilasa</td>
                <td class="tdx">'.$row->d16.'</td>
                <td class="tdx">menor a 120 UI/L</td>
                <td class="tdx">Enzimatico a Amilasa</td>
                <td class="tdx" style=" color: darkred; text-align:center;">LDL-Col.</td>
                <td class="tdx">'.$row->d17.'</td>
                <td class="tdx">Hasta 150mg/dl</td>
                <td class="tdx">CHOD-PAP</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Lipasa</td>
                <td class="tdx">'.$row->d18.'</td>
                <td class="tdx">10-150UI/L</td>
                <td class="tdx">Enzimatica Colorimetrica</td>
                <td class="tdx" style="font-weight:bold;color:purple; text-align:center; " >ELECTROLITOS</td>
                <td class="tdx"></td>
                <td class="tdx"></td>
                <td class="tdx"></td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Bilirrubina Total</td>
                <td class="tdx">'.$row->d19.'</td>
                <td class="tdx">hasta 1.2 mg/dl</td>
                <td class="tdx"" rowspan="3">Acido Sulfanilico con Diaazo</td>
                <td class="tdx" style=" color: darkred; text-align:center;">Sodio</td>
                <td class="tdx">'.$row->d20.'</td>
                <td class="tdx">135-155 mEq/L</td>
                <td class="tdx"" rowspan="4">Automatizado CORNLEY AFT-500</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Bilirrubina Directa</td>
                <td class="tdx">'.$row->d21.'</td>
                <td class="tdx">hasta 0.3 mg/dl</td>
                <td class="tdx"" style=" color: darkred; text-align:center;">Cloro</td>
                <td class="tdx" '.$row->d22.'></td>
                <td class="tdx">98-106 mEq/L</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Bilirrubina Inderecta</td>
                <td class="tdx">'.$row->d23.'</td>
                <td class="tdx">hasta 0.9 mg/dl</td>
                <td class="tdx"" style=" color: darkred; text-align:center;">Potasio</td>
                <td class="tdx">'.$row->d24.'</td>
                <td class="tdx">3.4-5.3 mEq/L</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">CK-MB</td>
                <td class="tdx">'.$row->d25.'</td>
                <td class="tdx">0-24 UI/L</td>
                <td class="tdx">Enzimatico </td>
                <td class="tdx"" style=" color: darkred; text-align:center;">Calcio</td>
                <td class="tdx">'.$row->d26.'</td>
                <td class="tdx">8.5-10.5mg/dl</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">LDH</td>
                <td class="tdx">'.$row->d27.'</td>
                <td class="tdx">200-480UI/L</td>
                <td class="tdx">Piruvato Lactato</td>
                <td class="tdx" style=" color: darkred; text-align:center;">Magnesio</td>
                <td class="tdx">'.$row->d28.'</td>
                <td class="tdx">1.7-2.4mg/dl</td>
                <td class="tdx">Colorimetrico calmagita</td>
            </tr>
            <tr>
                <td class="tdx" style=" color: darkred; text-align:center;">Hierro</td>
                <td class="tdx">'.$row->d29.'</td>
                <td class="tdx">50-170ug/dl</td>
                <td class="tdx">Goodwing Modificado</td>
                <td class="tdx" style=" color: darkred; text-align:center;">Fosforo</td>
                <td class="tdx">'.$row->d30.'</td>
                <td class="tdx">2.5-4.5mg/dl</td>
                <td class="tdx">Fosfomolibdato UV</td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" style="font-weight:bold;color:blue; text-align:center;">OBSERVACIONES</td>
                <td class="tdx" colspan="6">'.$row->d31.' </td>
            </tr>
            <tr>
                <td class="tdx" colspan="2" rowspan="2" style="font-weight:bold;color:blue; text-align:center;">RESPONSABLE</td>
                <td class="tdx" colspan="2" rowspan="2">
                    '.$row->user->name.'
                </td>
                <td class="tdx" colspan="3" style="font-weight:bold;color:blue; text-align:center;">FECHA TOMA DE MUESTRA</td>
                <td class="tdx">'.$row->fechatoma.'</td>
            </tr>
            <tr>
                <td class="tdx" colspan="3" style="font-weight:bold;color:blue; text-align:center;">FECHA DE ENTREGA DE RESULTADO</td>
                <td class="tdx">'.$row->fechatoma.'</td>
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
