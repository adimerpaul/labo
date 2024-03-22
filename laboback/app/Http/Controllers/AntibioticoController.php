<?php

namespace App\Http\Controllers;

use App\Models\Antibiotico;
use Illuminate\Http\Request;

class AntibioticoController extends Controller
{
    //
    public function listAntib($tipo){
        return Antibiotico::where('tipo',$tipo)->get();
    }
}
