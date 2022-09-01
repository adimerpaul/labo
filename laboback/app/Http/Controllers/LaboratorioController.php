<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaboratorioController extends Controller
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
        //return $request;
//        return Laboratorio::create($request->all());
        $nombreimagen=null;
        if ($request->hasFile('imagen')) {
            $file=$request->file('imagen');
            $nombreimagen = date('Ymd').''.time().".".$file->getClientOriginalExtension();
            $file->move(\public_path('imagenes'), $nombreimagen);
        }

        $labo=new Laboratorio();
        $labo->tipomuestra=$request->tipomuestra==null?'':$request->tipomuestra;
        $labo->fechatoma=$request->fechatoma==null?'':$request->fechatoma;
        $labo->horatoma=$request->horatoma==null?'':$request->horatoma;
        $labo->d1=$request->d1==null?'':$request->d1;
        $labo->d2=$request->d2==null?'':$request->d2;
        $labo->d3=$request->d3==null?'':$request->d3;
        $labo->d4=$request->d4==null?'':$request->d4;
        $labo->d5=$request->d5==null?'':$request->d5;
        $labo->d6=$request->d6==null?'':$request->d6;
        $labo->d7=$request->d7==null?'':$request->d7;
        $labo->d8=$request->d8==null?'':$request->d8;
        $labo->d9=$request->d9==null?'':$request->d9;
        $labo->d10=$request->d10==null?'':$request->d10;
        $labo->d11=$request->d11==null?'':$request->d11;
        $labo->d12=$request->d12==null?'':$request->d12;
        $labo->d13=$request->d13==null?'':$request->d13;
        $labo->d14=$request->d14==null?'':$request->d14;
        $labo->d15=$request->d15==null?'':$request->d15;
        $labo->d16=$request->d16==null?'':$request->d16;
        $labo->d17=$request->d17==null?'':$request->d17;
        $labo->d18=$request->d18==null?'':$request->d18;
        $labo->d19=$request->d19==null?'':$request->d19;
        $labo->d20=$request->d20==null?'':$request->d20;
        $labo->d21=$request->d21==null?'':$request->d21;
        $labo->d22=$request->d22==null?'':$request->d22;
        $labo->d23=$request->d23==null?'':$request->d23;
        $labo->d24=$request->d24==null?'':$request->d24;
        $labo->d25=$request->d25==null?'':$request->d25;
        $labo->d26=$request->d26==null?'':$request->d26;
        $labo->d27=$request->d27==null?'':$request->d27;
        $labo->d28=$request->d28==null?'':$request->d28;
        $labo->d29=$request->d29==null?'':$request->d29;
        $labo->d30=$request->d30==null?'':$request->d30;
        $labo->d31=$request->d31==null?'':$request->d31;
        $labo->d32=$request->d32==null?'':$request->d32;
        $labo->d33=$request->d33==null?'':$request->d33;
        $labo->d34=$request->d34==null?'':$request->d34;
        $labo->d35=$request->d35==null?'':$request->d35;
        $labo->d36=$request->d36==null?'':$request->d36;
        $labo->d37=$request->d37==null?'':$request->d37;
        $labo->d38=$request->d38==null?'':$request->d38;
        $labo->d39=$request->d39==null?'':$request->d39;
        $labo->d40=$request->d40==null?'':$request->d40;
        $labo->d41=$request->d41==null?'':$request->d41;
        $labo->d42=$request->d42==null?'':$request->d42;
        $labo->d43=$request->d43==null?'':$request->d43;
        $labo->d44=$request->d44==null?'':$request->d44;
        $labo->d45=$request->d45==null?'':$request->d45;
        $labo->solicitud=$request->solicitud==null?'':$request->solicitud;
        $labo->imagen=$nombreimagen;
        $labo->responsable=$request->responsable==null?'':$request->responsable;
        $labo->tipo_id=$request->tipo_id==null?'':$request->tipo_id;
        $labo->paciente_id=$request->paciente_id==null?'':$request->paciente_id;
        $labo->user_id=$request->user_id==null?'':$request->user_id;
        $labo->doctor_id=$request->doctor_id==null?'':$request->doctor_id;
        $labo->save();
    }

    public function updateimagen(Request $request){
        $nombreimagen=null;
        if ($request->hasFile('imagen')) {
            $file=$request->file('imagen');
            $nombreimagen = date('Ymd').''.time().".".$file->getClientOriginalExtension();
            $file->move(\public_path('imagenes'), $nombreimagen);
        }

        $labo=Laboratorio::find($request->id);
        $labo->imagen=$nombreimagen;
        $labo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function show(Laboratorio $laboratorio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function edit(Laboratorio $laboratorio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Laboratorio $laboratorio)
    {
        //

        $labo=Laboratorio::find($request->id);
        $labo->tipomuestra=$request->tipomuestra==null?'':strtoupper($request->tipomuestra);
        $labo->fechatoma=$request->fechatoma==null?'':$request->fechatoma;
        $labo->horatoma=$request->horatoma==null?'':$request->horatoma;
        $labo->d1=$request->d1==null?'':$request->d1;
        $labo->d2=$request->d2==null?'':$request->d2;
        $labo->d3=$request->d3==null?'':$request->d3;
        $labo->d4=$request->d4==null?'':$request->d4;
        $labo->d5=$request->d5==null?'':$request->d5;
        $labo->d6=$request->d6==null?'':$request->d6;
        $labo->d7=$request->d7==null?'':$request->d7;
        $labo->d8=$request->d8==null?'':$request->d8;
        $labo->d9=$request->d9==null?'':$request->d9;
        $labo->d10=$request->d10==null?'':$request->d10;
        $labo->d11=$request->d11==null?'':$request->d11;
        $labo->d12=$request->d12==null?'':$request->d12;
        $labo->d13=$request->d13==null?'':$request->d13;
        $labo->d14=$request->d14==null?'':$request->d14;
        $labo->d15=$request->d15==null?'':$request->d15;
        $labo->d16=$request->d16==null?'':$request->d16;
        $labo->d17=$request->d17==null?'':$request->d17;
        $labo->d18=$request->d18==null?'':$request->d18;
        $labo->d19=$request->d19==null?'':$request->d19;
        $labo->d20=$request->d20==null?'':$request->d20;
        $labo->d21=$request->d21==null?'':$request->d21;
        $labo->d22=$request->d22==null?'':$request->d22;
        $labo->d23=$request->d23==null?'':$request->d23;
        $labo->d24=$request->d24==null?'':$request->d24;
        $labo->d25=$request->d25==null?'':$request->d25;
        $labo->d26=$request->d26==null?'':$request->d26;
        $labo->d27=$request->d27==null?'':$request->d27;
        $labo->d28=$request->d28==null?'':$request->d28;
        $labo->d29=$request->d29==null?'':$request->d29;
        $labo->d30=$request->d30==null?'':$request->d30;
        $labo->d31=$request->d31==null?'':$request->d31;
        $labo->d32=$request->d32==null?'':$request->d32;
        $labo->d33=$request->d33==null?'':$request->d33;
        $labo->d34=$request->d34==null?'':$request->d34;
        $labo->d35=$request->d35==null?'':$request->d35;
        $labo->d36=$request->d36==null?'':$request->d36;
        $labo->d37=$request->d37==null?'':$request->d37;
        $labo->d38=$request->d38==null?'':$request->d38;
        $labo->d39=$request->d39==null?'':$request->d39;
        $labo->d40=$request->d40==null?'':$request->d40;
        $labo->d41=$request->d41==null?'':$request->d41;
        $labo->d42=$request->d42==null?'':$request->d42;
        $labo->d43=$request->d43==null?'':$request->d43;
        $labo->d44=$request->d44==null?'':$request->d44;
        $labo->d45=$request->d45==null?'':$request->d45;
        $labo->solicitud=$request->solicitud==null?'':$request->solicitud;
        $labo->responsable=$request->responsable==null?'':$request->responsable;
        $labo->user_id=$request->user_id==null?'':$request->user_id;
        $labo->doctor_id=$request->doctor_id==null?'':$request->doctor_id;
        $labo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $laboratorio=Laboratorio::find($id);
        $laboratorio->delete();
    }

    public function base64(Request $request){
        if ($request->imagen=='' || $request->imagen==null){
            return '';
        }
        $path = 'imagenes/'.$request->imagen;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;

    }

    public function listmuestra(){
        return DB::SELECT("SELECT tipomuestra from laboratorios group by tipomuestra;");
    }

    public function repelemento(Request $request){
        $glicemia=0;
        $glicosilada=0;
        $colesterol=0;
        $trigliceridos=0;
        $hdlcol=0;
        $ldlcol=0;
        $vldl=0;
        $creatinina=0;
        $proteniuria=0;
        //glicemia
        $res1=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and CAST(d1 as double)>160 and d1!='' GROUP by tipo_id;");
        if(sizeof($res1)>0)
            $glicemia+=$res1[0]->total;

        $res2=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=21 and CAST(d1 as double)>160 and d1!='' GROUP by tipo_id;");
        if(sizeof($res2)>0)
            $glicemia+=$res2[0]->total;
        //glicosilada
        $res3=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=17 and CAST(d1 as double)>6.5 and d1!='' GROUP by tipo_id;");
        if(sizeof($res3)>0)
            $glicosilada+=$res3[0]->total;
//coleterol
        $res4=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and CAST(d13 as double)>200  and d13!='' GROUP by tipo_id;");
        if(sizeof($res4)>0)
            $colesterol+=$res4[0]->total;
        $res5=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=22 and CAST(d6 as double)>200 and d6!='' GROUP by tipo_id;");
        if(sizeof($res5)>0)
            $colesterol+=$res5[0]->total;
//trigliceridos
        $res6=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and CAST(d11 as double)>200 and d11!='' GROUP by tipo_id;");
        if(sizeof($res6)>0)
            $trigliceridos+=$res6[0]->total;
        $res7=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=22 and CAST(d5 as double)>200 and d5!='' GROUP by tipo_id;");
        if(sizeof($res7)>0)
            $trigliceridos+=$res7[0]->total;
//hdlcol
        $res8=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and CAST(d15 as double)<35 and d15!='' GROUP by tipo_id;");
        if(sizeof($res8)>0)
            $hdlcol+=$res8[0]->total;
        $res9=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=22 and CAST(d7 as double)<35 and d7!='' GROUP by tipo_id;");
        if(sizeof($res9)>0)
            $hdlcol+=$res9[0]->total;
//ldlcol
        $res10=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and CAST(d17 as double)>150 and d17!='' GROUP by tipo_id;");
        if(sizeof($res10)>0)
            $ldlcol+=$res10[0]->total;
        $res11=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=22 and CAST(d8 as double)>150 and d8!='' GROUP by tipo_id;");
        if(sizeof($res11)>0)
            $ldlcol+=$res11[0]->total;
//vldl
        $res12=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=22 and CAST(d9 as double)>30 and d9!='' GROUP by tipo_id;");
        if(sizeof($res12)>0)
            $vldl+=$res12[0]->total;
//creatinina
        $res13=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and CAST(d3 as double)>2 and d3!='' GROUP by tipo_id;");
        if(sizeof($res13)>0)
            $creatinina+=$res13[0]->total;
        $res14=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=21 and CAST(d2 as double)>2 and d2!='' GROUP by tipo_id;");
        if(sizeof($res14)>0)
            $creatinina+=$res14[0]->total;
//proteniuria
        $res15=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=23 and CAST(d6 as double)>141 and d6!='' GROUP by tipo_id;");
        if(sizeof($res15)>0)
            $proteniuria+=$res15[0]->total;

        return json_encode( [
        ['nombre'=>'Glicemia mayor de 160','cantidad'=>$glicemia,],
        ['nombre'=>'Hb Glicosilada mayr a 6.5 ','cantidad'=>$glicosilada,],
        ['nombre'=>'Colesterol total mayor a 200','cantidad'=>$colesterol,],
        ['nombre'=>'Trigliceridos mayor a 200','cantidad'=>$trigliceridos,],
        ['nombre'=>'HDL col menor a 35','cantidad'=>$hdlcol,],
        ['nombre'=>'LDL col mayor a 150','cantidad'=>$ldlcol,],
        ['nombre'=>'Vldl mayor a 30','cantidad'=>$vldl,],
        ['nombre'=>'Creatinina mayor a 2','cantidad'=>$creatinina,],
        ['nombre'=>'Proteniuria mayor a 141','cantidad'=>$proteniuria]]);


    }
    public function resumenlab(Request $request){
        return Laboratorio::with('tipo')->with('doctor')->with('paciente')->whereDate('fechatoma','>=',$request->ini)->whereDate('fechatoma','<=',$request->fin)->get();
    }

    public function reporteformularios(Request $request){
        // HEMOGRAMA   hemotocrito
        $res1=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=1 and l.d5!='' GROUP by tipo_id;");
        if(sizeof($res1)>0)
            $sum1=$res1[0]->total;
        else
            $sum1=0;
       // tiempo cuagulacion  + t sangria
        $res2=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=1 and l.d2!='' GROUP by tipo_id;");
        if(sizeof($res2)>0)
            $sum2=$res2[0]->total;
        else    
            $sum2=0;
        $res3=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=1 and l.d4!='' GROUP by tipo_id;");
        if(sizeof($res3)>0)
            $sum3=$res3[0]->total;
        else    
            $sum3=0;

        // VES
        $res4=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=1 and l.d7!='' GROUP by tipo_id;");
        if(sizeof($res4)>0)
            $sum4=$res4[0]->total;
        else
            $sum4=0;

                    // t protombina
        $res5=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=1 and l.d6!='' GROUP by tipo_id;");
        if(sizeof($res5)>0)
            $sum5=$res5[0]->total;
        else
            $sum5=0;
         // grupo sanguineo
        $res6=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=1 and l.d12!='' GROUP by tipo_id;");
        if(sizeof($res6)>0)
            $sum6=$res6[0]->total;
        else
            $sum6=0;

        // reticulitos
        $res7=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=1 and l.d14!='' GROUP by tipo_id;");
        if(sizeof($res7)>0)
            $sum7=$res7[0]->total;
        else
            $sum7=0; 
            
        // plaquetas
        $res8=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=1 and l.d17!='' GROUP by tipo_id;");
        if(sizeof($res8)>0)
            $sum8=$res8[0]->total;
        else
            $sum8=0;

        //quimica    glicemia
        $res9=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d1!='' GROUP by tipo_id;");
        if(sizeof($res9)>0)
            $sum9=$res9[0]->total;
        else
            $sum9=0;
        // Amilasa pancreatica	quimica	d16
        $res10=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d16!='' GROUP by tipo_id;");
        if(sizeof($res10)>0)
            $sum10=$res10[0]->total;
        else
            $sum10=0;   

        // Creatinina 	quimica	d3
        $res11=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d3!='' GROUP by tipo_id;");
        if(sizeof($res11)>0)
            $sum11=$res11[0]->total;
        else
            $sum11=0;   
        // Proteina totales	quimica	d10
        $res12=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d12!='' GROUP by tipo_id;");
        if(sizeof($res12)>0)
            $sum12=$res12[0]->total;
        else
            $sum12=0;   
        // urea	quimica	d5
        $res13=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d5!='' GROUP by tipo_id;");
        if(sizeof($res13)>0)
            $sum13=$res13[0]->total;
        else
            $sum13=0;   
        // albumina	quimica	d12
        $res14=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d12!='' GROUP by tipo_id;");
        if(sizeof($res14)>0)
            $sum14=$res14[0]->total;
        else
            $sum14=0;   
        // nus bun	quimica	d7
        $res15=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d7!='' GROUP by tipo_id;");
        if(sizeof($res15)>0)
            $sum15=$res15[0]->total;
        else
            $sum15=0;   
        // globulina	quimica	d14
        $res16=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d14!='' GROUP by tipo_id;");
        if(sizeof($res16)>0)
            $sum16=$res16[0]->total;
        else
            $sum16=0;   
        // acido urico	quimica	d9
        $res17=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d9!='' GROUP by tipo_id;");
        if(sizeof($res17)>0)
            $sum17=$res17[0]->total;
        else
            $sum17=0;   
        // cloro	quimica	d22
        $res18=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d22!='' GROUP by tipo_id;");
        if(sizeof($res18)>0)
            $sum18=$res18[0]->total;
        else
            $sum18=0;   
        // transaminasas GOT	quimica	d6
        $res19=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d6!='' GROUP by tipo_id;");
        if(sizeof($res19)>0)
            $sum19=$res19[0]->total;
        else
            $sum19=0;   
        // sodio	quimica	d20
        $res20=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d20!='' GROUP by tipo_id;");
        if(sizeof($res20)>0)
            $sum20=$res20[0]->total;
        else
            $sum20=0;   
        // transaminasas GPT	quimica	d8
        $res21=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d8!='' GROUP by tipo_id;");
        if(sizeof($res21)>0)
            $sum21=$res21[0]->total;
        else
            $sum21=0;   
        // potasio	quimica	d24
        $res22=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d24!='' GROUP by tipo_id;");
        if(sizeof($res22)>0)
            $sum22=$res22[0]->total;
        else
            $sum22=0;   
        // bilirrubina total 	quimica	d19
        $res23=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d19!='' GROUP by tipo_id;");
        if(sizeof($res23)>0)
            $sum23=$res23[0]->total;
        else
            $sum23=0;   
        // magnecio	quimica	d28
        $res24=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d28!='' GROUP by tipo_id;");
        if(sizeof($res24)>0)
            $sum24=$res24[0]->total;
        else
            $sum24=0;   
        // ck-mb	quimica	d25
        $res25=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d25!='' GROUP by tipo_id;");
        if(sizeof($res25)>0)
            $sum25=$res25[0]->total;
        else
            $sum25=0;   
        // fosforo	quimica	d30
        $res26=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d30!='' GROUP by tipo_id;");
        if(sizeof($res26)>0)
            $sum26=$res26[0]->total;
        else
            $sum26=0;   
        
        // hierro serico	quimica	d29
        $res27=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d29!='' GROUP by tipo_id;");
        if(sizeof($res27)>0)
            $sum27=$res27[0]->total;
        else
            $sum27=0;   
        // LDH	quimica	d27
        $res28=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d27!='' GROUP by tipo_id;");
        if(sizeof($res28)>0)
            $sum28=$res28[0]->total;
        else
            $sum28=0;   
        // colesterol total	quimica	d13
        $res29=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d13!='' GROUP by tipo_id;");
        if(sizeof($res29)>0)
            $sum29=$res29[0]->total;
        else
            $sum29=0;   
        // trigliceridos	quimica	d11
        $res30=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d11!='' GROUP by tipo_id;");
        if(sizeof($res30)>0)
            $sum30=$res30[0]->total;
        else
            $sum30=0;   
        // hdl colesterol	quimica	d15
        $res31=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d15!='' GROUP by tipo_id;");
        if(sizeof($res31)>0)
            $sum31=$res31[0]->total;
        else
            $sum31=0;   
        // ldl colesterol	quimica	d17
        $res32=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d17!='' GROUP by tipo_id;");
        if(sizeof($res32)>0)
            $sum32=$res32[0]->total;
        else
            $sum32=0;   
        // lipasa	quimica	d18
        $res33=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=2 and l.d18!='' GROUP by tipo_id;");
        if(sizeof($res33)>0)
            $sum33=$res33[0]->total;
        else
            $sum33=0; 

        // FIJACION D HIERRO 
        $res34=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=24 GROUP by tipo_id;");
        if(sizeof($res34)>0)
            $sum34=$res34[0]->total;
        else
            $sum34=0; 

        // FGASOM ARTERIAL
        $res35=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=18 GROUP by tipo_id;");
        if(sizeof($res35)>0)
            $sum35=$res35[0]->total;
        else
            $sum35=0;

        // lcohelemia
        $res36=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=14 GROUP by tipo_id;");
        if(sizeof($res36)>0)
            $sum36=$res36[0]->total;
        else
            $sum36=0;

        //from inmunoensayop  ferritina	inmunoensayo	d2
        $res37=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=12 and l.d2!='' GROUP by tipo_id;");
        if(sizeof($res37)>0)
            $sum37=$res37[0]->total;
        else
            $sum37=0; 
        // il6	inmunoensayo	d3
        $res38=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=12 and l.d3!='' GROUP by tipo_id;");
        if(sizeof($res38)>0)
            $sum38=$res38[0]->total;
        else
            $sum38=0; 
        // hemoglobina glico
        $res39=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=17  GROUP by tipo_id;");
        if(sizeof($res39)>0)
            $sum39=$res39[0]->total;
        else
            $sum39=0; 

        // exammen de grl orina
        $res40=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=3 GROUP by tipo_id;");
        if(sizeof($res40)>0)
            $sum40=$res40[0]->total;
        else
            $sum40=0; 

// miscelaeno de orina
// proteinuria 24hrs	miscelaneo	d6
        $res41=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=23 and l.d6!='' GROUP by tipo_id;");
        if(sizeof($res41)>0)
            $sum41=$res41[0]->total;
        else
            $sum41=0; 
// proteinasa al azar en orian	miscelaneo	d5
        $res42=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=23 and l.d5!='' GROUP by tipo_id;");
        if(sizeof($res42)>0)
            $sum42=$res42[0]->total;
        else
            $sum42=0; 
// creatinina en orina	miscelaneo	d3
        $res43=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=23 and l.d3!='' GROUP by tipo_id;");
        if(sizeof($res43)>0)
            $sum43=$res43[0]->total;
        else
            $sum43=0; 
// clearence p depuraxiion de crestian	miscelaneo	d4
        $res44=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=23 and l.d4!='' GROUP by tipo_id;");
        if(sizeof($res44)>0)
            $sum44=$res44[0]->total;
        else
            $sum44=0; 

                    // test de embarazo
        $res45=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=13  GROUP by tipo_id;");
        if(sizeof($res45)>0)
            $sum45=$res45[0]->total;
        else
            $sum45=0;

                    //factor reumatoide	lab serologia	d1
        $res46=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d1!='' GROUP by tipo_id;");
        if(sizeof($res46)>0)
            $sum46=$res46[0]->total;
        else
            $sum46=0; 
            // proteina c reactiva pcr	lab serologia	d3
        $res47=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d3!='' GROUP by tipo_id;");
        if(sizeof($res47)>0)
            $sum47=$res47[0]->total;
        else
            $sum47=0; 
        // psa en taco	lab serologia	d13
        $res48=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d13!='' GROUP by tipo_id;");
        if(sizeof($res48)>0)
            $sum48=$res48[0]->total;
        else
            $sum48=0; 

        // b HCG 	inmunoensayo	d7
        $res49=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=12 and l.d7!='' GROUP by tipo_id;");
        if(sizeof($res49)>0)
            $sum49=$res49[0]->total;
        else
            $sum49=0; 
        // PSA cuanti	inmunoensayo	d4
        $res50=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=12 and l.d4!='' GROUP by tipo_id;");
        if(sizeof($res50)>0)
            $sum50=$res50[0]->total;
        else
            $sum50=0; 
        // Troponinna 	inmunoensayo	d6
        $res51=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=12 and l.d6!='' GROUP by tipo_id;");
        if(sizeof($res51)>0)
            $sum51=$res51[0]->total;
        else
            $sum51=0; 
        // PCR sencicle 	inmunoensayo	d5
        $res52=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=12 and l.d5!='' GROUP by tipo_id;");
        if(sizeof($res52)>0)
            $sum52=$res52[0]->total;
        else
            $sum52=0;

        // analsis de secerecion uretral
        $res53=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=4  GROUP by tipo_id;");
        if(sizeof($res53)>0)
            $sum53=$res53[0]->total;
        else
            $sum53=0; 

                    // analsiis de secercion vaginal
        $res54=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=5  GROUP by tipo_id;");
        if(sizeof($res54)>0)
            $sum54=$res54[0]->total;
        else
            $sum54=0; 

        // analsiis de heces
        $res55=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=6  GROUP by tipo_id;");
        if(sizeof($res55)>0)
            $sum55=$res55[0]->total;
        else
            $sum55=0; 

        //serolo bactero
        // Antiestreptolisina asto	lab serolo	d2
        $res56=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d2!='' GROUP by tipo_id;");
        if(sizeof($res56)>0)
            $sum56=$res56[0]->total;
        else
            $sum56=0;   
        // Prueba rapida para sifilis	lab serolo	d5
        $res57=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d5!='' GROUP by tipo_id;");
        if(sizeof($res57)>0)
            $sum57=$res57[0]->total;
        else
            $sum57=0; 
        // RPR 	lab serolo	d4
        $res58=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d4!='' GROUP by tipo_id;");
        if(sizeof($res58)>0)
            $sum58=$res58[0]->total;
        else
            $sum58=0; 
        // test helicobater pylore en sangre	lab serolo	d10
        $res59=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d10!='' GROUP by tipo_id;");
        if(sizeof($res59)>0)
            $sum59=$res59[0]->total;
        else
            $sum59=0; 
        // test helicobater pylore en heces	lab serolo	d11
        $res60=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d11!='' GROUP by tipo_id;");
        if(sizeof($res60)>0)
            $sum60=$res60[0]->total;
        else
            $sum60=0; 

        //reaccciond e lwidal
        $res61=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=15  GROUP by tipo_id;");
        if(sizeof($res61)>0)
            $sum61=$res61[0]->total;
        else
            $sum61=0; 
    
                    //toxoplasmosis
        $res62=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=20  GROUP by tipo_id;");
        if(sizeof($res62)>0)
            $sum62=$res62[0]->total;
        else
            $sum62=0; 

        // proclcitonina	inmunoensayo	d8
        $res63=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=12 and l.d8!='' GROUP by tipo_id;");
        if(sizeof($res63)>0)
            $sum63=$res63[0]->total;
        else
            $sum63=0; 

        //parasitologia
        //coproparasiltoci    simple
        $res64=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=7  GROUP by tipo_id;");
        if(sizeof($res64)>0)
            $sum64=$res64[0]->total;
        else
            $sum64=0;
                    //coproparasiltoci   seriado 
        $res65=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=8  GROUP by tipo_id;");
        if(sizeof($res65)>0)
            $sum65=$res65[0]->total;
        else
            $sum65=0;   
        //     test de benedict	simple	d17
        $res66=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=7 and l.d17!='' GROUP by tipo_id;");
        if(sizeof($res66)>0)
            $sum66=$res66[0]->total;
        else
            $sum66=0; 
        // sangre oculta   en heces	simple	d16
        $res67=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=7 and l.d16!='' GROUP by tipo_id;");
        if(sizeof($res67)>0)
            $sum67=$res67[0]->total;
        else
            $sum67=0; 
        
        //virologia
        //prueba codiv
        $res68=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=9 GROUP by tipo_id;");
        if(sizeof($res68)>0)
            $sum68=$res68[0]->total;
        else
            $sum68=0; 
        //     hepatitis a	lab serolo	d7
        $res69=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d7!='' GROUP by tipo_id;");
        if(sizeof($res69)>0)
            $sum69=$res69[0]->total;
        else
            $sum69=0; 
        // hepatitis b	lab serolo	d8
        $res70=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d8!='' GROUP by tipo_id;");
        if(sizeof($res70)>0)
            $sum70=$res70[0]->total;
        else
            $sum70=0; 
        // hepatiits c	lab serolo	d9
        $res71=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=10 and l.d9!='' GROUP by tipo_id;");
        if(sizeof($res71)>0)
            $sum71=$res71[0]->total;
        else
            $sum71=0; 
        //hepatittis b c
        $res72=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=19 GROUP by tipo_id;");
        if(sizeof($res72)>0)
            $sum72=$res72[0]->total;
        else
            $sum72=0; 

        //hemodialisis myb	hemodialisi
        $res73=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=21 GROUP by tipo_id;");
        if(sizeof($res73)>0)
            $sum73=$res73[0]->total;
        else
            $sum73=0; 
            //hemodialisi tri	hemotri
        $res74=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=22 GROUP by tipo_id;");
        if(sizeof($res74)>0)
            $sum74=$res74[0]->total;
        else
            $sum74=0; 

        return json_encode( [
            ['nombre'=>'HEMOGRAMA COMPLETO','cantidad'=>0,'tipo'=>'titulo'],
            ['nombre'=>'Hematocrito ','cantidad'=>$sum1,'tipo'=>''],
            ['nombre'=>'T de Cuagulacion + T sangria','cantidad'=>$sum2+$sum3,'tipo'=>''],
            ['nombre'=>'VES','cantidad'=>$sum4,'tipo'=>''],
            ['nombre'=>'T Protrombina','cantidad'=>$sum5,'tipo'=>''],
            ['nombre'=>'Grupo Sanguineo Rh','cantidad'=>$sum6,'tipo'=>''],
            ['nombre'=>'Reticulocitos','cantidad'=>$sum7,'tipo'=>''],
            ['nombre'=>'Plaquetas','cantidad'=>$sum8,'tipo'=>''],

            ['nombre'=>'ANALSIS CLINICO','cantidad'=>0,'tipo'=>'titulo'],
            ['nombre'=>'Glisemia','cantidad'=>$sum9,'tipo'=>''],
            ['nombre'=>'Amilasa Pancreatica','cantidad'=>$sum10,'tipo'=>''],
            ['nombre'=>'Creatinina','cantidad'=>$sum11,'tipo'=>''],
            ['nombre'=>'Proteinas Totales','cantidad'=>$sum12,'tipo'=>''],
            ['nombre'=>'Urea','cantidad'=>$sum13,'tipo'=>''],
            ['nombre'=>'Albumina','cantidad'=>$sum14,'tipo'=>''],
            ['nombre'=>'NUS BUN','cantidad'=>$sum15,'tipo'=>''],
            ['nombre'=>'Globulina','cantidad'=>$sum16,'tipo'=>''],
            ['nombre'=>'Acido Urico','cantidad'=>$sum17,'tipo'=>''],
            ['nombre'=>'Cloro','cantidad'=>$sum18,'tipo'=>''],
            ['nombre'=>'Traminasas GOT','cantidad'=>$sum19,'tipo'=>''],
            ['nombre'=>'Sodio','cantidad'=>$sum20,'tipo'=>''],
            ['nombre'=>'Traminasas GPT','cantidad'=>$sum21,'tipo'=>''],
            ['nombre'=>'Potasio','cantidad'=>$sum22,'tipo'=>''],
            ['nombre'=>'Bilirrubina Total','cantidad'=>$sum23,'tipo'=>''],
            ['nombre'=>'Magnesio','cantidad'=>$sum24,'tipo'=>''],
            ['nombre'=>'CK-MB','cantidad'=>$sum25,'tipo'=>''],
            ['nombre'=>'Fosforo','cantidad'=>$sum26,'tipo'=>''],
            ['nombre'=>'Hierro','cantidad'=>$sum27,'tipo'=>''],
            ['nombre'=>'LDH','cantidad'=>$sum28,'tipo'=>''],
            ['nombre'=>'Colesterol Total','cantidad'=>$sum29,'tipo'=>''],
            ['nombre'=>'Trigliceridos','cantidad'=>$sum30,'tipo'=>''],
            ['nombre'=>'HDL Colesterol','cantidad'=>$sum31,'tipo'=>''],
            ['nombre'=>'LDL Colesterol','cantidad'=>$sum32,'tipo'=>''],
            ['nombre'=>'Lipasa','cantidad'=>$sum33,'tipo'=>''],
            
            ['nombre'=>'Capacidad de Fijacion de Hierro','cantidad'=>$sum34,'tipo'=>''],
            ['nombre'=>'Gasometria Arterial','cantidad'=>$sum35,'tipo'=>''],
            ['nombre'=>'Alcoholemia','cantidad'=>$sum36,'tipo'=>''],
            ['nombre'=>'Ferritina','cantidad'=>$sum37,'tipo'=>''],
            ['nombre'=>'IL 6','cantidad'=>$sum38,'tipo'=>''],
            ['nombre'=>'Hemoglobina Glicosilada','cantidad'=>$sum39,'tipo'=>''],

            ['nombre'=>'UROANALISIS','cantidad'=>0,'tipo'=>'titulo'],
            ['nombre'=>'Examen Gral de Orina','cantidad'=>$sum40,'tipo'=>''],
            ['nombre'=>'Proteinuria de 24Hrs','cantidad'=>$sum41,'tipo'=>''],
            ['nombre'=>'Proteinas al azar en Orina','cantidad'=>$sum42,'tipo'=>''],
            ['nombre'=>'Creatinina en Orina','cantidad'=>$sum43,'tipo'=>''],
            ['nombre'=>'Clearence o Depuracion de Creatinina','cantidad'=>$sum44,'tipo'=>''],

            ['nombre'=>'SEROLOGIA','cantidad'=>0,'tipo'=>'titulo'],
            ['nombre'=>'Test de Embarazo','cantidad'=>$sum45,'tipo'=>''],
            ['nombre'=>'Factor Reummatoide','cantidad'=>$sum46,'tipo'=>''],
            ['nombre'=>'Proteina C Reactiva PCR','cantidad'=>$sum47,'tipo'=>''],
            ['nombre'=>'PSA en Taco','cantidad'=>$sum48,'tipo'=>''],
            ['nombre'=>'B HCG Cuantitativo','cantidad'=>$sum49,'tipo'=>''],
            ['nombre'=>'PSA Cuantitativo','cantidad'=>$sum50,'tipo'=>''],
            ['nombre'=>'Troponinna cuantitativa','cantidad'=>$sum51,'tipo'=>''],
            ['nombre'=>'PCR Sensible Cuantitativo','cantidad'=>$sum52,'tipo'=>''],

            ['nombre'=>'BACTEROLOGIA','cantidad'=>0,'tipo'=>'titulo'],
            ['nombre'=>'Analisis de Secrecion Uretral','cantidad'=>$sum53,'tipo'=>''],
            ['nombre'=>'Analisis de Secrecion Vaginal','cantidad'=>$sum54,'tipo'=>''],
            ['nombre'=>'Analisis de Heces','cantidad'=>$sum55,'tipo'=>''],

            ['nombre'=>'SEROLOGIA BACTERIOLOGIA','cantidad'=>0,'tipo'=>'titulo'],
            ['nombre'=>'Antiestreptolisina','cantidad'=>$sum56,'tipo'=>''],
            ['nombre'=>'Prueba rapida para Sifilis','cantidad'=>$sum57,'tipo'=>''],
            ['nombre'=>'RPR','cantidad'=>$sum58,'tipo'=>''],
            ['nombre'=>'Test Helicobacer Pylori en Sangre','cantidad'=>$sum59,'tipo'=>''],
            ['nombre'=>'Test Helicobacer Pylori en Heces','cantidad'=>$sum60,'tipo'=>''],
            ['nombre'=>'Reaccion de Widal','cantidad'=>$sum61,'tipo'=>''],
            ['nombre'=>'Toxoplasmosis','cantidad'=>$sum62,'tipo'=>''],
            ['nombre'=>'Procalcitonina','cantidad'=>$sum63,'tipo'=>''],

            ['nombre'=>'PARASITOLOGIA','cantidad'=>0,'tipo'=>'titulo'],
            ['nombre'=>'Coproparasitologico','cantidad'=>$sum64,'tipo'=>''],
            ['nombre'=>'Coproparasitologico Seriado','cantidad'=>$sum65,'tipo'=>''],
            ['nombre'=>'Test de Benedict','cantidad'=>$sum66,'tipo'=>''],
            ['nombre'=>'Sangre Oculta en Heces','cantidad'=>$sum67,'tipo'=>''],

            ['nombre'=>'VIROLOGIA','cantidad'=>0,'tipo'=>'titulo'],
            ['nombre'=>'Prueba covid 19','cantidad'=>$sum68,'tipo'=>''],
            ['nombre'=>'Hepatitis A','cantidad'=>$sum69,'tipo'=>''],
            ['nombre'=>'Hepatitis B','cantidad'=>$sum70,'tipo'=>''],
            ['nombre'=>'Hepatitis C','cantidad'=>$sum71,'tipo'=>''],
            ['nombre'=>'Hepatitis B cuantitativo','cantidad'=>$sum72,'tipo'=>''],

            ['nombre'=>'HEMODIALISIS','cantidad'=>0,'tipo'=>'titulo'],
            ['nombre'=>'Hemodialisis M y B','cantidad'=>$sum73,'tipo'=>''],
            ['nombre'=>'Hemodialisis tri','cantidad'=>$sum74,'tipo'=>''],
        ]);
    }

    public function totalsolicitud(Request $request){
        return DB::SELECT("SELECT d.nombre,d.paterno,d.materno,
        (select count(DISTINCT(l.solicitud)) from laboratorios l where l.doctor_id=d.id and l.solicitud!='' and l.fechatoma>='$request->ini' and l.fechatoma<='$request->fin') as num 
        from doctors d");
    }
}
