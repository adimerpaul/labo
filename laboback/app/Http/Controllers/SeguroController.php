<?php

namespace App\Http\Controllers;

use App\Models\Seguro;
use App\Http\Requests\StoreSeguroRequest;
use App\Http\Requests\UpdateSeguroRequest;

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
        return Seguro::all();
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
    public function store(StoreSeguroRequest $request)
    {
        //
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
    public function update(UpdateSeguroRequest $request, Seguro $seguro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seguro  $seguro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seguro $seguro)
    {
        //
    }
}
