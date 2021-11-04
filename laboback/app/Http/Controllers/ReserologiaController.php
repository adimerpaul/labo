<?php

namespace App\Http\Controllers;

use App\Models\Reserologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class ReserologiaController extends Controller
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
        $dato=Reserologia::create($request->reserologia+ ['user_id' => Auth::user()->id,'paciente_id'=>$request->paciente['id'],'doctor_id'=>$request->doctor]);
        return $dato;
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($this->generar($dato->id));
        //return $pdf->download('Resultado.pdf');
        //return redirect('/pacientes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reserologia  $reserologia
     * @return \Illuminate\Http\Response
     */

    public function generar($id){
        $row= Reserologia::with('paciente')->with('user')->with('doctor')
        ->where('id',$id)
        ->get();
        $row=$row[0];
        $cadena='
        <style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  witdh:50%;
}
*{
    padding: 0px;
    margin: 0px;
    font-size: 12px;
    }
table{
    width:50%;
    margin-left:10px;

}
</style>
        <table style="color: black; margin-top:5px; border:0;">
            <tr style=" border:0;">
                <td rowspan="3" style="border:0;"><img src="./img/natividad.jpeg" alt="Logo Clinica" srcset="" style="height:1.5cm"></td>
                <td style="color: darkblue; text-align:center; border:0;">SERVICIO DE LABORATORIO </td>
            </tr>
            <tr>
                <td style="color: darkblue; text-align:center;border:0; ">Telf: 5254721 Fax: 52-83667 </td>                
            </tr>
            <tr>
                <td style="color: darkblue; text-align:center; border:0;">Emergencia las 24 horas del dia. </td>                
            </tr>
            <tr>
                <td style="color: red; text-align:center; border:0;">Nº Registro CODELAB 000045 </td>                
                <td style="color: darkblue; text-align:center; border:0;">Bolivar Nº 753 entre Arica e Iquique </td>                
            </tr>
        </table>
        <table border="1" style="color: black">
            <tr>
                <td colspan="3" style="border:0; text-align: center; color:darkblue;font:bold"><h3><b> SEROLOGIA</b></h3></td>
                <td style="color:red">Form. 005</td>
            </tr>
            <tr>
                <td style="color: darkblue">PACIENTE</td>
                <td style="text-align:center">'.$row->paciente->nombre.' '.$row->paciente->paterno.' '.$row->paciente->materno.'</td>
                <td style="color: darkblue">EDAD</td>
                <td style="text-align:center">'.$row->paciente->age().'</td>
            </tr>
            <tr>
                <td style="color: darkblue">REQUERIDO POR</td>
                <td style="text-align:center">'.$row->doctor->nombre.' '.$row->doctor->paterno.' '.$row->doctor->materno.'</td>
                <td style="color: darkblue">SEXO</td>
                <td style="text-align:center">'.$row->paciente->sexo.'</td>
            </tr>
            <tr>
                <td style="color: darkblue">TIPO MUESTRA</td>
                <td style="text-align:center">'.$row->tipomuestra.'</td>
                <td style="color: red">N PACIENTE</td>
                <td style="text-align:center; color:red">'.$row->paciente->id.'</td>
            </tr>

        </table>
        <br>
        <table border="1" style="color: black">
            <tr>
                <td colspan="4" style="text-align: center; color:red;font:bold;">PRUEBA RAPIDA ANTIGENOS SARS COV 2</td>
            </tr>

            <tr>
                <td colspan="4" style="text-align: center;color:blue;font:bold; ">METODO: INMUNOGRAMATOGRAFIA CUALITATIVA</td>
            </tr>

            <tr>
                <td style="border:0; width: 20%"></td>
                <td style="text-align:center; color:red; style="width: 20%">ANTIGENO <br> SARS COV2</td>
                <td style="text-align:center">'.$row->d1.'</td>
                <td  style="border:0; width: 20%"></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:center; color:darkred"><h5>FUNDAMENTO E INTERPRETACION DE LOS RESULTADOS</h5></td>
            </tr>
            <tr>
                <td colspan="4">La prueba rapida de Antigeno Nasal puede revelar una Infeccion activa de SARS-COV-2.<br>
                El test proporciona un resultado de "<span style="color:red">POSITIVO</span>" o "<span style="color:red">NEGATIVO</span>"<br>
                Los antigenos son parte de la estructura del virus, como las proteinas de espiga.<br><br>
                La muestra de Hisopado nasofaringeo pasa por una linea que detecta antigenos y cambia de color, durante la prueba el anticuerpo monocianal de raton anti-SARS-CoV-2 en la muestra
                interactua con el Anticuerpo monocianal lgG anti-COVID-19 conjugando con particulas de color que forman un complejo de particulas de antigeno-anticuerpo. Este complejo migra en la 
                menbrana por accion capilar hasta la linea de prueba, donde aera capturado por el anticuerpo por el anticuerpo monocianal anti-SARS-CoV-2 de raton. Una linea de prueba coloreada 
                seria visible en la ventana de resultados si los antigenos del SARS-CoV-2 estan presentes en la muestra. <br><br>
                Funciona mejor en la etapa inicial con una <span style="color:red">CARGA VIRAL ALTA</span> y sintomalogia hasta los 10 dias.<br>
                Los resultados <span style="color:red">Negativos</span> indica ausencia de Antigenos detectables de SARS-CoV-2 y cuando la carga viral o la cantidad de antigeno presente se encuentra debajo del limite de deteccion.<br><br>
                Los resultados <span style="color:red">Psitivos</span> no diferencian entre SARS-COV y SARS-COV-2
            
                </td>
            </tr>
            <tr>
                <td colspan="4">
                <span style="color:red">NOTA:</span> Las pruebas rapidas para COVID-19 NO SON CONFIRMATORIAS<br>
                Los Resultados deben ser interpretados en funcion de la Clinica del paciente y dias de evolucion de la enfermedad
                </td>

            </tr>
            <tr>
                <td style="color:red">OBSERVACIONES</td>
                <td colspan="3" >'.$row->d2.'</td>
            </tr>

            <tr >
                <td colspan="2" rowspan="3" style="color:darkblue">RESPONSABLE: '.$row->user->name.'</td>
                <td style="color:darkblue"> FECHA DE TOMA DE MUESTRAS: </td>
                <td >'.$row->fechatoma.'</td>
            </tr>
            <tr >
                <td style="color:darkblue"> HORA DE TOMA DE MUESTRAS: </td>
                <td >'.$row->horatoma.'</td>
            </tr>
            <tr>
                <td style="color:darkblue">FECHA DE ENTREGA DE MUESTRAS: </td>
                <td >'.date('Y-m-d').'</td>
            </tr>

        </table>


        ';
        $pdf = App::make('dompdf.wrapper');
        //        $customPaper = array(0,0,360,360);
        $pdf->setPaper('letter','landscape');

                $pdf->loadHTML($cadena);
                return $pdf->stream();
    }

    public function show(Reserologia $reserologia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reserologia  $reserologia
     * @return \Illuminate\Http\Response
     */
    public function edit(Reserologia $reserologia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reserologia  $reserologia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reserologia $reserologia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reserologia  $reserologia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reserologia $reserologia)
    {
        //
    }
}
