<?php

namespace App\Http\Controllers;

use App\Models\Ensayo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class EnsayoController extends Controller
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
        $dato=Ensayo::create($request->ensayo+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
        //$pdf = App::make('dompdf.wrapper');
       // $pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('ejemplo.pdf');
        //return redirect('/pacientes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ensayo  $ensayo
     * @return \Illuminate\Http\Response
     */
    public function show($paciente_id)
    {
        //

    }

    public function datos(Request $request ){


        return Ensayo::with('persona')->with('doctor')
        ->where('id',1)
        ->get();

    
    }

    public function generar($id){
        $row= Ensayo::with('paciente')->with('user')->with('doctor')
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
        if(($row->d1<0 || $row->d1>500)&& $row->d1!='')$cd1='background-color:#ff726f;';
        if($row->paciente->sexo=='Masculino' && ($row->d2<30 || $row->d2>350)&& $row->d2!='')$cd2='background-color:#ff726f;';
        if($row->paciente->sexo=='Femenino' && ($row->d2<20 || $row->d2>250)&& $row->d2!='')$cd2='background-color:#ff726f;';
        if(($row->d3<0 || $row->d3>7)&& $row->d3!='')$cd3='background-color:#ff726f;';
        if(($row->d4<0 || $row->d4>4)&& $row->d4!='')$cd4='background-color:#ff726f;';
        if(($row->d5<0 || $row->d5>10)&& $row->d5!='')$cd5='background-color:#ff726f;';
        if(($row->d6<0 || $row->d6>0.11)&& $row->d6!='')$cd6='background-color:#ff726f;';
        if(($row->d7<0 || $row->d7>10)&& $row->d7!='')$cd7='background-color:#ff726f;';
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
                    <td style="color:red">Form. 0010</td>
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
                    METODO: INMUNOENSAYO DE FLUORESCENCIA (FIA)
                    </td>
                </tr>
                <tr>
                    <td colspan="2" ></td>
                    <td style="text-align:center; color:darkblue;" > UNIDAD</td>
                    <td style="text-align:center; color:darkblue;" >Tipo de Muestra</td>
                    <td style="text-align:center; color:darkblue;" >Valor Referencial</td>
                </tr>
                <tr>
                    <td style="color:blue;">DIMEROS D</td>
                    <td style="text-align:center; '.$cd1.'">'.$row->d1.'</td>
                    <td style="text-align:center; color:blue;">ng/ml</td>
                    <td style="text-align:center; color:blue;">Plasma Citratado</td>
                    <td style="text-align:center; color:darkblue;">Hasta 500 ng/ml</td>
                </tr>
    
                <tr>
                    <td rowspan="2" style="color:blue; ">FERRITINA</td>
                    <td rowspan="2" style="text-align:center; '.$cd2.'">'.$row->d2.'</td>
                    <td rowspan="2" style="text-align:center; color:blue;">ng/ml</td>
                    <td rowspan="2" style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:darkblue;">30-350 ng/ml Varon </td>
                </tr>  
                <tr>
                    <td style="text-align:center; color:darkblue;">20-250 ng/ml Mujer</td>
    
                </tr>
                <tr>
                    <td style=" color:blue;">IL-6</td>
                    <td style="text-align:center; '.$cd3.'">'.$row->d3.'</td>
                    <td style="text-align:center; color:blue;">pg/ml</td>
                    <td style="text-align:center; color:blue;">Suero/plasma</td>
                    <td style="text-align:center; color:darkblue;">7 pg/ml</td>
                </tr>            
                <tr>
                    <td  style="color:blue;">PSA CUANTITATIVO</td>
                    <td style="text-align:center; '.$cd4.'">'.$row->d4.'</td>
                    <td style="text-align:center; color:blue;">ng/ml</td>
                    <td style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:darkblue;">Menor a 4 ng/ml</td>
                </tr>            
                <tr>
                    <td style="color:blue;">PCR CUANTITATIVO</td>
                    <td style="text-align:center; '.$cd5.'">'.$row->d5.'</td>
                    <td style="text-align:center; color:blue;">mg/L</td>
                    <td style="text-align:center; color:blue;">Sangre Entera</td>
                    <td style="text-align:center; color:darkblue;"> menor a 10 mg/L</td>
                </tr>            
                <tr>
                    <td  style="color:blue;">TROPONINA I</td>
                    <td  style="text-align:center; '.$cd6.'">'.$row->d6.'</td>
                    <td style="text-align:center; color:blue;">ng/ml</td>
                    <td style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:darkblue;">0.0 - 0.11 ng/ml</td>
                </tr>            
                <tr>
                    <td rowspan="2"  style="color:blue;">B - HCG</td>
                    <td rowspan="2" style="text-align:center;'.$cd7.' ">'.$row->d7.'</td>
                    <td rowspan="2" style="text-align:center; color:blue;">mlU/ml</td>
                    <td rowspan="2" style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:darkblue;">Mujer No Embarazada menor a 10 mlU/ml </td>
                </tr>            
                <tr>
                    <td style="text-align:center; color:darkblue;">Mujer en postmenopausia menor a 10 mlU/ml</td>
                    
                </tr>
                <tr>
                    <td rowspan="4"  style="color:blue;">PROCALCITONINA</td>
                    <td rowspan="4" style="text-align:center; ">'.$row->d8.'</td>
                    <td rowspan="4" style="text-align:center; color:blue;">ng/ml</td>
                    <td rowspan="4"  style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:darkblue;">PCT menor a 0.5 Es posible infeccion Bacteriana Local</td>
                </tr>
                <tr>
                    <td style="text-align:center; color:darkblue;">PCT 0.5 - 2 Posible Infeccion </td>
                </tr>
                <tr>
                    <td style="text-align:center; color:darkblue;">PCT 2 - 10 Es muy Probable Infeccion (sepsis) a menos que se conozcan otras causas </td>
                </tr>
                <tr>
                    <td style="text-align:center; color:darkblue;">PCT > 10 Sepsis Bacteriana severa o shock septico </td>
                </tr>
                <tr>
                    <td colspan="6"></td>
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ensayo  $ensayo
     * @return \Illuminate\Http\Response
     */
    public function edit(Ensayo $ensayo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ensayo  $ensayo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ensayo $ensayo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ensayo  $ensayo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ensayo $ensayo)
    {
        //
    }
}
