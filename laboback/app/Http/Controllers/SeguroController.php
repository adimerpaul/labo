<?php

namespace App\Http\Controllers;

use App\Models\Seguro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeguroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Seguro::with('paciente')->get();
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
     * @param  \App\Http\Requests\StoreSeguroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $seguro=new Seguro;
        $seguro->nombre=strtoupper($request->nombre);
        $seguro->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seguro  $seguro
     * @return \Illuminate\Http\Response
     */
    public function show(Seguro $seguro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seguro  $seguro
     * @return \Illuminate\Http\Response
     */
    public function edit(Seguro $seguro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSeguroRequest  $request
     * @param  \App\Models\Seguro  $seguro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seguro $seguro)
    {
        //
        $seguro=Seguro::find($request->id);
        $seguro->nombre=strtoupper($request->nombre);
        $seguro->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seguro  $seguro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $seguro=Seguro::find($id);
        $seguro->delete();
    }
}
