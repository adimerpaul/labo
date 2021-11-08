<?php

namespace App\Http\Controllers;

use App\Models\Simple;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class SimpleController extends Controller
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
        $dato=Simple::create($request->simple+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('Simple.pdf');
        //return redirect('/pacientes');
    }
    public function generar($id){
        $row= simple::with('paciente')
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
            <table style="padding-left:10px;padding-right:15px;width: 100%;color: black;padding-top: 5px">
            <tr >
                <td rowspan="3" ><img src="./img/natividad.jpeg" alt="Logo Clinica" srcset="" style="height:1.5cm; "></td>
                <td style=" text-align:center;color:darkblue">SERVICIO DE LABORATORIO </td>
            </tr>
 
            <tr>
                <td style=" text-align:center;color:darkblue">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style=" text-align:center;color:darkblue">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
            <tr>
            <td style=" text-align:center;color:red;"> Nº Registro CODELAB 000045</td>
            <td style=" text-align:center;color:darkblue">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
        </table>
        <table class="tablex" style="padding-left:10px;padding-right:15px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center;color: darkblue;font:bold"><h3>COPRAPARASITOLOGICO SIMPLE</h3></td>
                <td class="tdx" style="color:red;">Form. 009</td>
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
                <td class="tdx" style="text-align:center;color:red">'.$row->paciente->id.'</td>
            </tr>

        </table>
        <table class="tablex" style="padding-left:10px;padding-right:15px;width: 100%;">
            <tr >
                <td class="tdx" colspan="3" style="text-align: center ;color: darkblue;font-weight: bold">COPRAPARASITOLOGICO SIMPLE</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue;" colspan="2">ASPECTO DE LA MUESTRA</td>
                <td class="tdx" style="text-align:center">'.$row->d1.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">COLOR</td>
                <td class="tdx" style="text-align:center">'.$row->d2.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">CELULAS EPITELIALES</td>
                <td class="tdx" style="text-align:center">'.$row->d3.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">LEUCOCITOS</td>
                <td class="tdx" style="text-align:center">'.$row->d4.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">HEMATIES</td>
                <td class="tdx" style="text-align:center">'.$row->d5.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">GRASAS</td>
                <td class="tdx" style="text-align:center">'.$row->d6.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">LEVADURAS</td>
                <td class="tdx" style="text-align:center">'.$row->d7.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">ESPORAS MICOTICAS</td>
                <td class="tdx" style="text-align:center">'.$row->d8.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">ALMIDON</td>
                <td class="tdx" style="text-align:center">'.$row->d9.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">PARASITOS</td>
                <td class="tdx" style="text-align:center">'.$row->d10.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">PIOCITOS</td>
                <td class="tdx" style="text-align:center">'.$row->d11.'</td>
            </tr>

            <tr>
                <td class="tdx" rowspan="2" style="color:blue;width:20%" >MOCO FECAL:</td>
                <td class="tdx" rowspan="2"  style="width:20%"><span style="color:black;text-align:center;">'.$row->d12.'</span>               </td>
                <td class="tdx"> Polimorfonucleares: <span style="color:black">'.$row->d13.'</span></td>

            </tr>
            <tr>
                <td class="tdx">Mononucleares: <span style="color:black">'.$row->d14.'</span> </td>

            </tr>
            <tr>
                <td class="tdx" style="color:red" colspan="2">OBSERVACIONES</td>
                <td class="tdx" style="text-align:center">'.$row->d15.'</td>
            </tr>

            <tr colspan="3">
                <td class="tdx" colspan="3" style="text-align:center;color: darkblue;font-weight: bold">OTROS</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">SANGRE OCULTA EN HECES</td>
                <td class="tdx" style="text-align:center">'.$row->d16.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:blue" colspan="2">TEST DE BENEDICT</td>
                <td class="tdx" style="text-align:center">'.$row->d17.'</td>
            </tr>

            <tr>
                <td class="tdx" style="color:blue" colspan="2">OBSERVACIONES</td>
                <td class="tdx"style="text-align:center" >'.$row->d18.'</td>
            </tr>

            <tr>
                <td class="tdx" rowspan="3" colspan="2" style=";color: darkblue;font-weight: bold" >RESPONSABLE: '.$row->responsable.'</td>

                <td class="tdx" style=";color: blue;font-weight: bold">
                    FECHA DE TOMA DE MUESTRAS :<span style="color:black"> '.$row->fechatoma.'</span>

                </td>
            </tr>
            <tr>

                <td class="tdx" style=";color: blue;font-weight: bold">
                    HORA DE TOMA DE MUESTRAS : <span style="color:black">'.$row->horatoma.'</span>

                </td>
            </tr>
            <tr>

            <td class="tdx" style="color: blue;font-weight: bold">
                FECHA DE ENTREGA DE MUESTRAS : <span style="color:black">'.date('Y-m-d').'</span>   

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
     * @param  \App\Models\simple  $simple
     * @return \Illuminate\Http\Response
     */
    public function show(simple $simple)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\simple  $simple
     * @return \Illuminate\Http\Response
     */
    public function edit(simple $simple)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\simple  $simple
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, simple $simple)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\simple  $simple
     * @return \Illuminate\Http\Response
     */
    public function destroy(simple $simple)
    {
        //
    }
}
