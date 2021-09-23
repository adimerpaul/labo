<?php

namespace App\Http\Controllers;

use App\Models\simple;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
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
*{
margin: 0px;
padding: 0px;
border: 0px;
font-size: 12px;
}
</style>
            <table style="padding-left:10px;padding-right:15px;width: 100%;color: black;padding-top: 5px">
            <tr >
                <td rowspan="4" style="height: 2cm"><img src="./images/natividad2.png" alt="Logo Clinica" srcset="" style="height: 10px; width:20px;"></td>
                <td style=" text-align:center;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style=" text-align:center;">Telf: 5254721 Fax: 52-83667 </td>
            </tr>
            <tr>
                <td style=" text-align:center;">Emergencia las 24 horas del dia. </td>
            </tr>
            <tr>
                <td style=" text-align:center;">Bolivar Nº 753 entre Arica e Iquique </td>
            </tr>
        </table>
        <table class="tablex" style="padding-left:10px;padding-right:15px;width: 100%;color: black">
            <tr>
                <td class="tdx" colspan="3" style="text-align: center"><h3>COPRAPARASITOLOGICO SIMPLE</h3></td>
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
        <table class="tablex" border="1" style="padding-left:10px;padding-right:15px;width: 100%;color: black">
            <tr >
                <td class="tdx" colspan="2" style="text-align: center ;color: darkred;font-weight: bold">COPRAPARASITOLOGICO SIMPLE</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >ASPECTO DE LA MUESTRA</td>
                <td class="tdx" >'.$row->d1.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >COLOR</td>
                <td class="tdx" >'.$row->d2.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >CELULAS EPITELIALES</td>
                <td class="tdx" >'.$row->d3.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >LEUCOCITOS</td>
                <td class="tdx" >'.$row->d4.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >HEMATIES</td>
                <td class="tdx" >'.$row->d5.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >GRASAS</td>
                <td class="tdx" >'.$row->d6.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >LEVADURAS</td>
                <td class="tdx" >'.$row->d7.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >ESPORAS MICOTICAS</td>
                <td class="tdx" >'.$row->d8.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >ALMIDON</td>
                <td class="tdx" >'.$row->d9.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >PARASITOS</td>
                <td class="tdx" >'.$row->d10.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >PIOCITOS</td>
                <td class="tdx" >'.$row->d11.'</td>
            </tr>

            <tr>
                <td class="tdx" rowspan="2" style="color:red" >MOCO FECAL:
                <span style="color:black">'.$row->d12.'</span>               </td>
                <td class="tdx"> Polimorfonucleares: <span style="color:black">'.$row->d13.'</span></td>

            </tr>
            <tr>
                <td class="tdx">Mononucleares: <span style="color:black">'.$row->d14.'</span> </td>

            </tr>
            <tr>
                <td class="tdx" style="color:red" >OBSERVACIONES</td>
                <td class="tdx" >'.$row->d15.'</td>
            </tr>

            <tr colspan="2">
                <td class="tdx" colspan="2" style="text-align:center;color: darkred;font-weight: bold">OTROS</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >SANGRE OCULTA EN HECES</td>
                <td class="tdx" >'.$row->d16.'</td>
            </tr>
            <tr>
                <td class="tdx" style="color:red" >TEST DE BENEDICT</td>
                <td class="tdx" >'.$row->d17.'</td>
            </tr>

            <tr>
                <td class="tdx" style="color:red" >OBSERVACIONES</td>
                <td class="tdx" >'.$row->d18.'</td>
            </tr>

            <tr>
                <td class="tdx" rowspan="2" style=";color: blue;font-weight: bold" >RESPONSABLE: '.$row->user->name.'</td>

                <td class="tdx" style=";color: blue;font-weight: bold">
                    FECHA DE TOMA DE MUESTRAS : '.$row->fechatoma.'

                </td>
            </tr>
            <tr>

                <td class="tdx" style=";color: blue;font-weight: bold">
                    FECHA DE ENTREGA DE MUESTRAS : '.$row->fechatoma.'

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
