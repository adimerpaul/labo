<?php

namespace App\Http\Controllers;

use App\Models\Embarazo;
use Illuminate\Http\Request;


class EmbarazoController extends Controller
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
    public function datos(Request $request ){
        return Embarazo::with('persona')->with('doctor')
        ->where('id',1)
        ->get();    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $dato=Embarazo::create($request->embarazo+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Embarazo  $embarazo
     * @return \Illuminate\Http\Response
     */
    public function show(Embarazo $embarazo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Embarazo  $embarazo
     * @return \Illuminate\Http\Response
     */
    public function edit(Embarazo $embarazo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Embarazo  $embarazo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Embarazo $embarazo)
    {
        //
    }
    public function generar($id){
        $row= Ensayo::with('paciente')->with('user')->with('doctor')
        ->where('id',$id)
        ->get();
        $row=$row[0];
        $cadena='
        <style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}

*{
    margin: 0px;
    padding: 0px;
    border: 0px;
    font-size: 12px;
    }
    table{
        margin-left: 10px;
        width: 50%;
        } 
</style>
            <table style="color: black;border:0">
                <tr style="border:0">
                    <td rowspan="3" style="border:0"><img src="./img/natividad.jpeg" alt="Logo Clinica" srcset="" style="height:1.5cm"></td>
                    <td style="color: darkblue; text-align:center; border:0">SERVICIO DE LABORATORIO </td>
                </tr>
                <tr style="border:0">
                    <td style="color: darkblue; text-align:center;border:0 ">Telf: 5254721 Fax: 52-83667 </td>                
                </tr>
                <tr style="border:0">
                    <td style="color: darkblue; text-align:center; border:0">Emergencia las 24 horas del dia. </td>                
                </tr>
                <tr style="border:0">
                    <td style="color: red; text-align:center; border:0">Nº Registro CODELAB 000045 </td>                
                    <td style="color: darkblue; text-align:center; border:0">Bolivar Nº 753 entre Arica e Iquique </td>                
                </tr>
            </table>
    
    
            <table style="color: black">
                <tr>
                    <td colspan="3" style="text-align: center;"></td>
                    <td style="color:red">Form. 008</td>
                </tr>
                <tr>
                    <td style="color: darkblue">PACIENTE</td>
                    <td style="text-align: center">'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</td>
                    <td style="color: darkblue">EDAD</td>
                    <td style="text-align: center">'.$row->paciente->age().'</td>
                </tr>
                <tr>
                    <td style="color: darkblue">REQUERIDO POR</td>
                    <td style="text-align: center">'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                    <td style="color: darkblue">SEXO</td>
                    <td style="text-align: center"> '.$row->paciente->sexo.'</td>
                </tr>
                <tr>
                    <td style="color: darkblue">METODO</td>
                    <td style="text-align: center">'.$row->tipomuestra.'</td>
                    <td style="color: red">N PACIENTE</td>
                    <td style="text-align: center; color:red">'.$row->paciente->id.'</td>
                </tr>
    
            </table>
            <br>
            <table>
                <tr><td colspan="5" style="text-align:center; color:darkred;font:bold">
                    METODO: PRUEBA RAPIDA INMUNOCROMATOGRAFIA
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" style="text-align:center; color:darkblue;" > TEST EMBARAZO EN SANGRE</td>
                    <td colspan="2" style="text-align:center; ">'.$row->d1.'</td>
                </tr>
                <tr>
                    <td style="color:blue;"></td>
                    <td colspan="2" style="text-align:center; color:darkblue;" > F.U.M.</td>
                    <td colspan="2" style="text-align:center;">'.$row->fum.'</td>
                </tr>
    

                <tr>
                <td style="color:blue;"></td>
                <td colspan="1" style="text-align:center; color:darkblue;" > OBSERVACIONES:</td>
                <td colspan="3" style="text-align:center;">'.$row->obs.'</td>
                </tr>
          
    
                <tr >
                    <td colspan="2" rowspan="3" style="color:darkblue">RESPONSABLE: '.$row->responsable.'</td>
                    <td colspan="2">           FECHA DE TOMA DE MUESTRAS:              </td>
                    <td >'.$row->fechatoma.'</td>
                </tr>
                <tr>
                <td colspan="2">
                    HORA DE TOMA DE MUESTRAS: 
                </td>
                <td > '.$row->horatoma.'</td>
            </tr> 
                <tr>
                    <td colspan="2">
                        FECHA DE ENTREGA DE MUESTRAS: 
                    </td>
                    <td > '.date('Y-m-d').'</td>
                </tr>          
            </table>';
            $pdf = App::make('dompdf.wrapper');
            //        $customPaper = array(0,0,360,360);
                    $pdf->setPaper('letter','landscape');
                    $pdf->loadHTML($cadena);
                    return $pdf->stream();
    

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Embarazo  $embarazo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Embarazo $embarazo)
    {
        //
    }
}
