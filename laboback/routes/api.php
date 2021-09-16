<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/login',[\App\Http\Controllers\UserController::class,'login']);
Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::post('/logout',[\App\Http\Controllers\UserController::class,'logout']);
    Route::post('/me',[\App\Http\Controllers\UserController::class,'me']);
//    Route::resource('/contribuyente',\App\Http\Controllers\ContribuyenteController::class);
    Route::resource('/historial', App\Http\Controllers\HistorialController::class);
    Route::resource('/permiso', App\Http\Controllers\PermisoController::class);
    Route::apiResource('/user', App\Http\Controllers\UserController::class);
    Route::post('/estado/{user}', [App\Http\Controllers\UserController::class, 'estado']);
    Route::put('/pass/{user}',[\App\Http\Controllers\UserController::class,'pass']);
    Route::put('/updatepermisos/{user}',[\App\Http\Controllers\UserController::class,'updatepermisos']);
    Route::post('/doctoractivo', [App\Http\Controllers\DoctorController::class, 'doctoractivo']);
    Route::resource('/paciente',\App\Http\Controllers\PacienteController::class);
    Route::resource('/doctor',\App\Http\Controllers\DoctorController::class);
    Route::resource('/hemograma',\App\Http\Controllers\HemogramaController::class);
    Route::resource('/orina',\App\Http\Controllers\OrinaController::class);
    Route::resource('/uretral',\App\Http\Controllers\UretralController::class);
    Route::resource('/sanguinia',\App\Http\Controllers\SanguiniaController::class);
    Route::resource('/reactivo',\App\Http\Controllers\ReactivoController::class);
    Route::resource('/vaginal',\App\Http\Controllers\VaginalController::class);
    Route::resource('/hece',\App\Http\Controllers\HeceController::class);
    Route::resource('/simple',\App\Http\Controllers\SimpleController::class);
    Route::resource('/seriado',\App\Http\Controllers\SeriadoController::class);
    Route::resource('/serologia',\App\Http\Controllers\SerologiaController ::class);
    Route::resource('/labserologia',\App\Http\Controllers\LabserologiaController ::class);
    Route::resource('/reserologia',\App\Http\Controllers\ReserologiaController ::class);
    Route::resource('/ensayo',\App\Http\Controllers\EnsayoController ::class);
    Route::resource('/inventario',\App\Http\Controllers\InventarioController ::class);
    Route::post('/datos',[\App\Http\Controllers\EnsayoController ::class,'datos']);
    Route::get('/caduca',[\App\Http\Controllers\ReactivoController::class,'caduca']);


});
