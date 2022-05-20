<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Doctor::all();
//        return view('doctor',['doctors'=>$doctor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listdoctor(){
        return Doctor::where('activo',1)->orderBy('nombre','asc')->get();
    }

    public function store(Request $request)
    {
        //
        $doctor=new Doctor;
        $doctor->ci=$request->ci;
        $doctor->nombre=strtoupper($request->nombre);
        $doctor->paterno=strtoupper($request->paterno);
        $doctor->materno=strtoupper($request->materno);
        $doctor->especialidad=strtoupper($request->especialidad);
        $doctor->matricula=$request->matricula;
        $doctor->celular=$request->celular;
        return $doctor->save();
//        return redirect('doctor');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        //
        $doctor=Doctor::find($request->id);
        $doctor->ci=$request->ci;
        $doctor->nombre=strtoupper($request->nombre);
        $doctor->paterno=strtoupper($request->paterno);
        $doctor->materno=strtoupper($request->materno);
        $doctor->especialidad=strtoupper($request->especialidad);
        $doctor->matricula=$request->matricula;
        $doctor->celular=$request->celular;
        return $doctor->save();
//        return redirect('doctor');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $doctor=Doctor::find($id);
        $doctor->delete();
//        return redirect('doctor');
    }
    public function doctoractivo(Request $request)
    {
        $doctor=Doctor::find($request->id);
        if ($doctor->activo==1)
            $doctor->activo=0;
        else
            $doctor->activo=1;
        $doctor->save();
//        return redirect('/doctor');
    }
}
