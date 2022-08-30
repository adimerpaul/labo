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
        $res1=DB::SELECT("SELECT count(*) total FROM laboratorios l WHERE fechatoma>='$request->ini' and fechatoma<='$request->fin' and tipo_id=1 and l.d1!='' GROUP by tipo_id;");
        if(sizeof($res1)>0)
            $sum1=$res1[0]->total;
        else
            $sum1=0;
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

    }
}
