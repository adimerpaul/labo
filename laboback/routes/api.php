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
    Route::post('/estadodoc/{doctor}', [App\Http\Controllers\DoctorController::class, 'estadodoc']);
    Route::apiResource('/paciente',\App\Http\Controllers\PacienteController::class);
    Route::apiResource('/doctor',\App\Http\Controllers\DoctorController::class);
    Route::apiResource('/hemograma',\App\Http\Controllers\HemogramaController::class);
    Route::apiResource('/orina',\App\Http\Controllers\OrinaController::class);
    Route::apiResource('/uretral',\App\Http\Controllers\UretralController::class);
    Route::apiResource('/sanguinia',\App\Http\Controllers\SanguiniaController::class);
    Route::apiResource('/reactivo',\App\Http\Controllers\ReactivoController::class);
    Route::apiResource('/vaginal',\App\Http\Controllers\VaginalController::class);
    Route::apiResource('/hece',\App\Http\Controllers\HeceController::class);
    Route::apiResource('/simple',\App\Http\Controllers\SimpleController::class);
    Route::apiResource('/seriado',\App\Http\Controllers\SeriadoController::class);
    Route::apiResource('/serologia',\App\Http\Controllers\SerologiaController ::class);
    Route::apiResource('/labserologia',\App\Http\Controllers\LabserologiaController ::class);
    Route::apiResource('/reserologia',\App\Http\Controllers\ReserologiaController ::class);
    Route::apiResource('/ensayo',\App\Http\Controllers\EnsayoController ::class);
    Route::apiResource('/inventario',\App\Http\Controllers\InventarioController ::class);
    Route::post('/datos',[\App\Http\Controllers\EnsayoController ::class,'datos']);
    Route::get('/caduca',[\App\Http\Controllers\ReactivoController::class,'caduca']);


});
