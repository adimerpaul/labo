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
        margin-left: 5px;
        margin-right: 5px;
        width: 50%;
        } 
</style>
            <table style="color: black;border:0">
                <tr style="border:0">
                    <td rowspan="4" style="border:0"><img src="images/natividad.png" alt="Logo Clinica" srcset="" ></td>
                    <td style="color: blue; text-align:center; border:0">SERVICIO DE LABORATORIO </td>
                </tr>
                <tr style="border:0">
                    <td style="color: blue; text-align:center;border:0 ">Telf: 5254721 Fax: 52-83667 </td>                
                </tr>
                <tr style="border:0">
                    <td style="color: blue; text-align:center; border:0">Emergencia las 24 horas del dia. </td>                
                </tr>
                <tr style="border:0">
                    <td style="color: blue; text-align:center; border:0">Bolivar Nº 753 entre Arica e Iquique </td>                
                </tr>
            </table>
    
    
            <table style="color: black">
                <tr>
                    <td colspan="3" style="text-align: center;"><h3></h3></td>
                    <td>Form. '.$row->id.'</td>
                </tr>
                <tr>
                    <td style="color: darkblue">PACIENTE</td>
                    <td><label style="text-align: center">'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</label></td>
                    <td style="color: darkblue">EDAD</td>
                    <td><label tyle="text-align: center">'.$row->paciente->age().'</label></td>
                </tr>
                <tr>
                    <td style="color: darkblue">REQUERIDO POR</td>
                    <td tyle="text-align: center">'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                    <td style="color: darkblue">SEXO</td>
                    <td tyle="text-align: center"> '.$row->paciente->sexo.'</td>
                </tr>
                <tr>
                    <td style="color: darkblue">METODO</td>
                    <td tyle="text-align: center">'.$row->tipomuestra.'</td>
                    <td style="color: darkblue">N PACIENTE</td>
                    <td tyle="text-align: center; color:red">'.$row->paciente->id.'</td>
                </tr>
    
            </table>
            <br>
            <table style="color: black">
                <tr><td colspan="5" style="text-align:center; color:red;">
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
                    <td style="color:red;">DIMEROS D</td>
                    <td >'.$row->d1.'</td>
                    <td style="text-align:center; color:blue;">ng/ml</td>
                    <td style="text-align:center; color:blue;">Plasma Citratado</td>
                    <td style="text-align:center; color:blue;">Hasta 500 ng/ml</td>
                </tr>
    
                <tr>
                    <td rowspan="2" style="color:red; ">FERRITINA</td>
                    <td rowspan="2">'.$row->d2.'</td>
                    <td rowspan="2" style="text-align:center; color:blue;">ng/ml</td>
                    <td rowspan="2" style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:blue;">30-350 ng/ml Varon </td>
                </tr>  
                <tr>
                    <td style="text-align:center; color:blue;">20-250 ng/ml Mujer</td>
    
                </tr>
                <tr>
                    <td style=" color:red;">IL-6</td>
                    <td >'.$row->d3.'</td>
                    <td style="text-align:center; color:blue;">pg/ml</td>
                    <td style="text-align:center; color:blue;">Suero/plasma</td>
                    <td style="text-align:center; color:blue;">7 pg/ml</td>
                </tr>            
                <tr>
                    <td  style="color:red;">PSA CUANTITATIVO</td>
                    <td >'.$row->d4.'</td>
                    <td style="text-align:center; color:blue;">ng/ml</td>
                    <td style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:blue;">Menor a 4 ng/ml</td>
                </tr>            
                <tr>
                    <td style="color:red;">PCR CUANTITATIVO</td>
                    <td >'.$row->d5.'</td>
                    <td style="text-align:center; color:blue;">mg/L</td>
                    <td style="text-align:center; color:blue;">Sangre Entera</td>
                    <td style="text-align:center; color:blue;"> menor a 10 mg/L</td>
                </tr>            
                <tr>
                    <td  style="color:red;">TROPONINA I</td>
                    <td >'.$row->d6.'</td>
                    <td style="text-align:center; color:blue;">ng/ml</td>
                    <td style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:blue;">0.0 - 0.11 ng/ml</td>
                </tr>            
                <tr>
                    <td rowspan="2"  style="color:red;">B - HCG</td>
                    <td rowspan="2">'.$row->d7.'</td>
                    <td rowspan="2" style="text-align:center; color:blue;">mlU/ml</td>
                    <td rowspan="2" style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:blue;">Mujer No Embarazada menor a 10 mlU/ml </td>
                </tr>            
                <tr>
                    <td style="text-align:center; color:blue;">Mujer en postmenopausia menor a 10 mlU/ml</td>
                    
                </tr>
                <tr>
                    <td rowspan="4"  style="color:red;">PROCALCITONINA</td>
                    <td rowspan="4" >'.$row->d8.'</td>
                    <td rowspan="4" style="text-align:center; color:blue;">ng/ml</td>
                    <td rowspan="4"  style="text-align:center; color:blue;">Suero</td>
                    <td style="text-align:center; color:blue;">PCT menor a 0.5 Es posible infeccion Bacteriana Local</td>
                </tr>
                <tr>
                    <td style="text-align:center; color:blue;">PCT 0.5 - 2 Posible Infeccion </td>
                </tr>
                <tr>
                    <td style="text-align:center; color:blue;">PCT 2 - 10 Es muy Probable Infeccion (sepsis) a menos que se conozcan otras causas </td>
                </tr>
                <tr>
                    <td style="text-align:center; color:blue;">PCT > 10 Sepsis Bacteriana severa o shock septico </td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                </tr>
    
                <tr >
                    <td colspan="2" rowspan="2" >RESPONSABLE: '.$row->user->name.'</td>
                    <td colspan="3">
                        FECHA DE TOMA DE MUESTRAS: '.$row->fechatoma.'
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        FECHA DE ENTREGA DE MUESTRAS: '.date('Y-m-d').'
                    </td>
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
