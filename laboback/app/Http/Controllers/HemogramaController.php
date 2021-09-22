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
  border: 1px solid black;
  border-collapse: collapse;
}
table{
margin-left: 5px;
margin-right: 5px
}
*{
padding: 0px;
margin: 0px;
border: 0px;
font-size: 10px;
}
</style>
        <table style="width: 50%;color: black;margin-top: 5px">
            <tr >
                <td rowspan="4" ><img src="./img/natividad" style="height: 10px; width:200px;"></td>
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
        <table style="width: 50%;color: black;" class="tablex">
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
                <td class="tdx" >'.$row->doctor->nombre.'</td>
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

        <table class="tablex"  style="width: 50%;color: black;">
            <tr>
                <td class="tdx" colspan="3"></td>
                <td class="tdx" style="color: darkred" align="center">REFERENCIA</td>
                <td class="tdx" colspan="2"></td>
                <td class="tdx" style="color: darkred" align="center">REFERENCIA</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Globulos Rojos</td>
                <td class="tdx">'.$row->d1.'</td>
                <td class="tdx">x10 <sup>12 </sup>/L</td>
                <td class="tdx">Varon 5.1-5.7x10 <sup>12</sup>/L <br> Mujer 4.8-5.4x10 <sup>12</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">Tiempo de cuagulacion</td>
                <td class="tdx">'.$row->d2.'</td>
                <td class="tdx">5-10 min</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Hemoglobina</td>
                <td class="tdx">'.$row->d3.'</td>
                <td class="tdx">g/L</td>
                <td class="tdx">Varon 151-175 g/L <br> Mujer Mujer 141-165 g/L</td>
                <td class="tdx" style="color: darkred" align="center">Tiempo de sangria</td>
                <td class="tdx">'.$row->d4.'</td>
                <td class="tdx">1-3 min</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Hematocrito</td>
                <td class="tdx">'.$row->d5.'</td>
                <td class="tdx">L/L</td>
                <td class="tdx">Varon 0.51-0.57 L/L <br> Mujer 0.46-0.53 L/L</td>
                <td class="tdx" style="color: darkred" align="center">Tiempo de Protrombina</td>
                <td class="tdx">'.$row->d6.'</td>
                <td class="tdx">12-13 seg</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">V.E.S.</td>
                <td class="tdx">'.$row->d7.'</td>
                <td class="tdx">mm.</td>
                <td class="tdx">Varon 15 mm/hora <br> Mujer 20 mm/hora</td>
                <td class="tdx" style="color: darkred" align="center">% Actividad</td>
                <td class="tdx">'.$row->d8.'</td>
                <td class="tdx">95-100%</td>
            </tr>

            <tr>
                <td class="tdx" style="color: darkred" align="center">V.C.M.</td>
                <td class="tdx">'.$row->d9.'</td>
                <td class="tdx">ft.</td>
                <td class="tdx">Varon 83.0-97.0 ft</td>
                <td class="tdx" style="color: darkred" align="center">INR</td>
                <td class="tdx">'.$row->d10.'</td>
                <td class="tdx">0.97-1.04</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Hb.C.M.</td>
                <td class="tdx">'.$row->d11.'</td>
                <td class="tdx">pg.</td>
                <td class="tdx">27.0-31.0 pg.</td>
                <td class="tdx" style="color: darkred" align="center">Grupofactor</td>
                <td class="tdx" colspan="2">'.$row->d12.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">C. Hb.C.M.</td>
                <td class="tdx">'.$row->d13.'</td>
                <td class="tdx">%</td>
                <td class="tdx">32-36%</td>
                <td class="tdx" style="color: darkred" align="center">Reticulocitos</td>
                <td class="tdx">'.$row->d14.'</td>
                <td class="tdx">0.5-2%</td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Globulos Blancos</td>
                <td class="tdx">'.$row->d15.'</td>
                <td class="tdx">10 <sup>9</sup>/L</td>
                <td class="tdx"> 4.5-10.5x10 <sup>9</sup>/L</td>
                <td class="tdx" style="color: darkred" align="center">IPR</td>
                <td class="tdx">'.$row->d16.'</td>
                <td class="tdx"></td>
            </tr>
            <tr>
                <td class="tdx" style="color: darkred" align="center">Plaquetas</td>
                <td class="tdx">'.$row->d17.'</td>
                <td class="tdx">x10 <sup>9 </sup>/L</td>
                <td class="tdx">105-400x10 <sup>9</sup> /L</td>
                <td class="tdx" colspan="3"></td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="5"></td>
                <td colspan="2">VALOR REFERENCIAL</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;"></td>
                <td colspan="2">RELATIVA</td>
                <td colspan="2">ABSOLUTA</td>
                <td>RELATIVA</td>
                <td>ABSOLUTA</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Cayados</td>
                <td>'.$row->d18.'</td>
                <td>%</td>
                <td>'.$row->d19.'</td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-3%</td>
                <td>0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Neutrofilos</td>
                <td>'.$row->d20.'</td>
                <td>%</td>
                <td>'.$row->d21.'</td>
                <td>x10 <sup>9</sup>/L</td>
                <td>50-70%</td>
                <td>2.50-7.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Eosinofilos</td>
                <td>'.$row->d22.'</td>
                <td>%</td>
                <td>'.$row->d23.'</td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-3%</td>
                <td>0.00-0.35x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Basofilos</td>
                <td>'.$row->d24.'</td>
                <td>%</td>
                <td>'.$row->d25.'</td>
                <td>x10 <sup>9</sup>/L</td>
                <td>0-1%</td>
                <td>0.00-0.15x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Linfocitos</td>
                <td>'.$row->d26.'</td>
                <td>%</td>
                <td>'.$row->d27.'</td>
                <td>x10 <sup>9</sup>/L</td>
                <td>25-40%</td>
                <td>1.25-4.200x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Monocitos</td>
                <td>'.$row->d28.'</td>
                <td>%</td>
                <td>'.$row->d29.'</td>
                <td>x10 <sup>9</sup>/L</td>
                <td>4-8%</td>
                <td>2.00-8.40x10 <sup>9</sup>/L</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">BLASTOS</td>
                <td>'.$row->d30.'</td>
                <td>%</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table border="1" style="width: 100%;color: black">
            <tr>
                <td colspan="2">MORFOLOGIA DE FRONTIS DE SANGRE PERIFERICA</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Serie Rojas:</td>
                <td>'.$row->d31.'</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Serie Blancas:</td>
                <td>'.$row->d32.'</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Serie Plaquetarias:</td>
                <td>'.$row->d33.'</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">FECHA DE TOMA DE MUESTRA:</td>
                <td>'.$row->fechatoma.'</td>
            </tr>
            <tr>
                <td style="color:white; background-color:red;">Responsable:</td>
                <td>'.$row->user->name.'</td>
            </tr>
        </table>';

        $pdf = App::make('dompdf.wrapper');
        $customPaper = array(0,0,360,360);
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
