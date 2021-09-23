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
        $dato=Hemograma::create($request->hemograma+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
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
        $cadena='
        <style>
.tablex , th , .tdx  {
  border: 0.2px solid black;
  border-collapse: collapse;
}
table{
}
*{
padding: 0px;
margin: 0px;
border: 0px;
font-size: 13px;
}
</style>

<table style="width: 100%">
<tr>
<td style="width: 50%"></td>
<td>

        <table style="padding-right:10px;padding-left:0px;width: 100%;color: black;margin-top: 5px;" align="right">
            <tr >
                <td rowspan="4" ><img src="./img/natividad2.png" style="height: 10px; width:200px;"></td>
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
        <table style="padding-right:10px;padding-left:0px;width: 100%;color: black;" class="tablex">
            <tr >
                <td class="tdx" colspan="3" style="text-align: center"><h3>HEMOGRAMA COMPLETO</h3></td>
                <td class="tdx" >Form. '.$row->id.'</td>
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
            <tr>
                <td class="tdx"  style="color: darkblue">METODO</td>
                <td class="tdx"  colspan="3" align="center" style="color: darkblue">
                    Contador Hematologico MINDRAY BC 5130
                    Hematocrito (Metodo Manual) Hemoglobina (Clanmetahemoglobina reactivo drabking)
                </td>
            </tr>
        </table>
        <table class="tablex"  style="padding-right:10px;padding-left:0px;width: 100%;color: black;">
            <tr>
                <td class="tdx" colspan="3"></td>
                <td class="tdx" style="color: red;font: bold" align="center">REFERENCIA</td>
                <td class="tdx" colspan="2"></td>
                <td class="tdx" style="color: red;font: bold" align="center">REFERENCIA</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Globulos Rojos</td>
                <td class="tdx">'.$row->d1.'</td>
                <td class="tdx" style="color: darkred" align="center">x10 <sup>12 </sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">Varon 5.1-5.7x10 <sup>12</sup>/L <br> Mujer 4.8-5.4x10 <sup>12</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">Tiempo de cuagulacion</td>
                <td class="tdx">'.$row->d2.'</td>
                <td class="tdx" style="color: darkred" align="center">5-10 min</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Hemoglobina</td>
                <td class="tdx">'.$row->d3.'</td>
                <td class="tdx" style="color: darkred" align="center">g/L</td>
                <td class="tdx" style="color: darkred" align="center">Varon 151-175 g/L <br> Mujer Mujer 141-165 g/L</td>
                <td class="tdx" style="color: darkred" align="center">Tiempo de sangria</td>
                <td class="tdx">'.$row->d4.'</td>
                <td class="tdx" style="color: darkred" align="center">1-3 min</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Hematocrito</td>
                <td class="tdx">'.$row->d5.'</td>
                <td class="tdx" style="color: darkred" align="center">L/L</td>
                <td class="tdx" style="color: darkred" align="center">Varon 0.51-0.57 L/L <br> Mujer 0.46-0.53 L/L</td>
                <td class="tdx" style="color: darkred" align="center">Tiempo de Protrombina</td>
                <td class="tdx">'.$row->d6.'</td>
                <td class="tdx" style="color: darkred" align="center">12-13 seg</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">V.E.S.</td>
                <td class="tdx">'.$row->d7.'</td>
                <td class="tdx" style="color: darkred" align="center">mm.</td>
                <td class="tdx" style="color: darkred" align="center">Varon 15 mm/hora <br> Mujer 20 mm/hora</td>
                <td class="tdx" style="color: darkred" align="center">% Actividad</td>
                <td class="tdx">'.$row->d8.'</td>
                <td class="tdx" style="color: darkred" align="center">95-100%</td>
            </tr>

            <tr>
                <td class="tdx" style="color: darkred" align="center">V.C.M.</td>
                <td class="tdx">'.$row->d9.'</td>
                <td class="tdx" style="color: darkred" align="center">ft.</td>
                <td class="tdx" style="color: darkred" align="center">Varon 83.0-97.0 ft</td>
                <td class="tdx" style="color: darkred" align="center">INR</td>
                <td class="tdx">'.$row->d10.'</td>
                <td class="tdx" style="color: darkred" align="center">0.97-1.04</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Hb.C.M.</td>
                <td class="tdx">'.$row->d11.'</td>
                <td class="tdx" style="color: darkred" align="center" >pg.</td>
                <td class="tdx" style="color: darkred" align="center" >27.0-31.0 pg.</td>
                <td class="tdx" style="color: darkred" align="center">Grupofactor</td>
                <td class="tdx" style="color: darkred" align="center" colspan="2">'.$row->d12.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">C. Hb.C.M.</td>
                <td class="tdx">'.$row->d13.'</td>
                <td class="tdx" style="color: darkred" align="center">%</td>
                <td class="tdx" style="color: darkred" align="center">32-36%</td>
                <td class="tdx" style="color: darkred" align="center">Reticulocitos</td>
                <td class="tdx">'.$row->d14.'</td>
                <td class="tdx" style="color: darkred" align="center">0.5-2%</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Globulos Blancos</td>
                <td class="tdx">'.$row->d15.'</td>
                <td class="tdx" style="color: darkred" align="center">10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center"> 4.5-10.5x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">IPR</td>
                <td class="tdx">'.$row->d16.'</td>
                <td class="tdx"></td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Plaquetas</td>
                <td class="tdx">'.$row->d17.'</td>
                <td class="tdx" style="color: darkred" align="center">x10 <sup>9 </sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">105-400x10 <sup>9</sup> /L</td>
                <td class="tdx" colspan="3"></td>
            </tr>
        </table>
        <table class="tablex" border="1" style="padding-right:10px;padding-left:0px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="5"></td>
                <td class="tdx" colspan="2" style="color: red;font: bold" align="center" >VALOR REFERENCIAL</td>
            </tr>
            <tr>
                <td class="tdx" ></td>
                <td class="tdx" colspan="2" style="color: red;font: bold" align="center">RELATIVA</td>
                <td class="tdx" colspan="2" style="color: red;font: bold" align="center">ABSOLUTA</td>
                <td class="tdx" style="color: red;font: bold" align="center">RELATIVA</td>
                <td class="tdx" style="color: red;font: bold" align="center">ABSOLUTA</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Cayados</td>
                <td class="tdx">'.$row->d18.'</td>
                <td class="tdx" style="color: darkred" align="center">%</td>
                <td class="tdx">'.$row->d19.'</td>
                <td class="tdx" style="color: darkred" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">0-3%</td>
                <td class="tdx" style="color: darkred" align="center">0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Neutrofilos</td>
                <td class="tdx">'.$row->d20.'</td>
                <td class="tdx" style="color: darkred" align="center">%</td>
                <td class="tdx">'.$row->d21.'</td>
                <td class="tdx" style="color: darkred" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">50-70%</td>
                <td class="tdx" style="color: darkred" align="center">2.50-7.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Eosinofilos</td>
                <td class="tdx">'.$row->d22.'</td>
                <td class="tdx" style="color: darkred" align="center">%</td>
                <td class="tdx">'.$row->d23.'</td>
                <td class="tdx" style="color: darkred" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">0-3%</td>
                <td class="tdx" style="color: darkred" align="center">0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Basofilos</td>
                <td class="tdx">'.$row->d24.'</td>
                <td class="tdx" style="color: darkred" align="center">%</td>
                <td class="tdx">'.$row->d25.'</td>
                <td class="tdx" style="color: darkred" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">0-1%</td>
                <td class="tdx" style="color: darkred" align="center">0.00-0.15x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Linfocitos</td>
                <td class="tdx">'.$row->d26.'</td>
                <td class="tdx" style="color: darkred" align="center">%</td>
                <td class="tdx">'.$row->d27.'</td>
                <td class="tdx" style="color: darkred" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">25-40%</td>
                <td class="tdx" style="color: darkred" align="center">1.25-4.200x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Monocitos</td>
                <td class="tdx">'.$row->d28.'</td>
                <td class="tdx" style="color: darkred" align="center">%</td>
                <td class="tdx">'.$row->d29.'</td>
                <td class="tdx" style="color: darkred" align="center">x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">4-8%</td>
                <td class="tdx" style="color: darkred" align="center">2.00-8.40x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">BLASTOS</td>
                <td class="tdx">'.$row->d30.'</td>
                <td class="tdx" style="color: darkred" align="center">%</td>
                <td class="tdx"></td>
                <td class="tdx"></td>
                <td class="tdx"></td>
                <td class="tdx"></td>
            </tr>
        </table>
        <table class="tablex" style="padding-right:10px;padding-left:0px;width: 100%;color: black">
            <tr>
                <td class="tdx" style="color: red;font: bold"align="center" colspan="2">MORFOLOGIA DE FRONTIS DE SANGRE PERIFERICA</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Serie Rojas:</td>
                <td class="tdx">'.$row->d31.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Serie Blancas:</td>
                <td class="tdx">'.$row->d32.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Serie Plaquetarias:</td>
                <td class="tdx">'.$row->d33.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: blue" align="center">FECHA DE TOMA DE MUESTRA:</td>
                <td class="tdx">'.$row->fechatoma.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: blue" align="center">Responsable:</td>
                <td class="tdx">'.$row->user->name.'</td>
            </tr>
        </table>

</td>
</tr>
</table>


';

        $pdf = App::make('dompdf.wrapper');
//        $customPaper = array(0,0,360,360);
        $pdf->setPaper('letter','landscape');
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
