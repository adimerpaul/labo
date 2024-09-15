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
Route::get('/hemograma/{id}',[\App\Http\Controllers\HemogramaController::class,'generar']);
Route::get('/orina/{id}',[\App\Http\Controllers\OrinaController::class,'generar']);
Route::get('/sanguinia/{id}',[\App\Http\Controllers\SanguiniaController::class,'generar']);
Route::get('/uretral/{id}',[\App\Http\Controllers\UretralController::class,'generar']);
Route::get('/vaginal/{id}',[\App\Http\Controllers\VaginalController::class,'generar']);
Route::get('/hece/{id}',[\App\Http\Controllers\HeceController::class,'generar']);
Route::get('/simple/{id}',[\App\Http\Controllers\SimpleController::class,'generar']);
Route::get('/seriado/{id}',[\App\Http\Controllers\SeriadoController::class,'generar']);
Route::get('/serologia/{id}',[\App\Http\Controllers\SerologiaController::class,'generar']);
Route::get('/labserologia/{id}',[\App\Http\Controllers\LabserologiaController::class,'generar']);
Route::get('/reserologia/{id}',[\App\Http\Controllers\ReserologiaController::class,'generar']);
Route::get('/ensayo/{id}',[\App\Http\Controllers\EnsayoController::class,'generar']);
Route::get('/embarazo/{id}',[\App\Http\Controllers\EmbarazoController::class,'generar']);

Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::post('/logout',[\App\Http\Controllers\UserController::class,'logout']);
    Route::post('/me',[\App\Http\Controllers\UserController::class,'me']);
    Route::resource('/antibioticos',\App\Http\Controllers\AntibioticoController::class);
//    Route::resource('/contribuyente',\App\Http\Controllers\ContribuyenteController::class);
    Route::resource('/historial', App\Http\Controllers\HistorialController::class);
    Route::resource('/permiso', App\Http\Controllers\PermisoController::class);
    Route::resource('/seguro', App\Http\Controllers\SeguroController::class);
    Route::apiResource('/user', App\Http\Controllers\UserController::class);
    Route::post('/estado/{user}', [App\Http\Controllers\UserController::class,'estado']);
    Route::post('/listuser', [App\Http\Controllers\UserController::class,'listuser']);
    Route::get('/listseguro', [App\Http\Controllers\SeguroController::class,'listseguro']);
    Route::get('/listadoSeguro', [App\Http\Controllers\SeguroController::class,'listadoSeguro']);
    Route::put('/pass/{user}',[\App\Http\Controllers\UserController::class,'pass']);
    Route::put('/updatepermisos/{user}',[\App\Http\Controllers\UserController::class,'updatepermisos']);
    Route::post('/doctoractivo', [App\Http\Controllers\DoctorController::class,'doctoractivo']);
    Route::get('/listdoctor', [App\Http\Controllers\DoctorController::class,'listdoctor']);
    Route::post('/reactivoestado', [App\Http\Controllers\ReactivoController::class,'reactivoestado']);
    Route::post('/listinventario', [App\Http\Controllers\InventarioController::class,'listinventario']);
    Route::post('/listretiro', [App\Http\Controllers\RetiroController::class, 'listretiro']);
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
    Route::resource('/serologia',\App\Http\Controllers\SerologiaController::class);
    Route::resource('/labserologia',\App\Http\Controllers\LabserologiaController::class);
    Route::resource('/reserologia',\App\Http\Controllers\ReserologiaController::class);
    Route::resource('/ensayo',\App\Http\Controllers\EnsayoController::class);
    Route::resource('/inventario',\App\Http\Controllers\InventarioController ::class);
    Route::resource('/embarazo',\App\Http\Controllers\EmbarazoController ::class);
    Route::post('/fvencido',[\App\Http\Controllers\InventarioController ::class,'fvencido']);
    Route::resource('/retiro',\App\Http\Controllers\RetiroController::class);
    Route::resource('/laboratorio',\App\Http\Controllers\LaboratorioController::class);
    Route::post('/updateimagen',[\App\Http\Controllers\LaboratorioController::class,'updateimagen']);
    Route::resource('/tipo',\App\Http\Controllers\TipoController::class);
    Route::post('/invent',[\App\Http\Controllers\RetiroController::class,'invent']);
    Route::post('/datos',[\App\Http\Controllers\EnsayoController::class,'datos']);
    Route::post('/caduca',[\App\Http\Controllers\ReactivoController::class,'caduca']);
    Route::post('/minimo',[\App\Http\Controllers\ReactivoController::class,'minimo']);
    Route::post('/impresion',[\App\Http\Controllers\ReactivoController::class,'impresion']);
    Route::get('/historialform/{id}',[\App\Http\Controllers\PacienteController::class,'historialform']);
    Route::get('/muestra',[\App\Http\Controllers\PacienteController::class,'muestra']);
    Route::post('/eliminar',[\App\Http\Controllers\PacienteController::class,'eliminar']);
    Route::post('/reporte',[\App\Http\Controllers\PacienteController::class,'reporte']);
    Route::post('/repseguro',[\App\Http\Controllers\PacienteController::class,'repseguro']);
    Route::post('/listLabo',[\App\Http\Controllers\PacienteController::class,'listLabo']);
    Route::post('/base64',[\App\Http\Controllers\LaboratorioController::class,'base64']);
    Route::post('/repelemento',[\App\Http\Controllers\LaboratorioController::class,'repelemento']);
    Route::post('/resumenlab',[\App\Http\Controllers\LaboratorioController::class,'resumenlab']);
    Route::post('/listmuestra',[\App\Http\Controllers\LaboratorioController::class,'listmuestra']);
    Route::post('/reporteformularios',[\App\Http\Controllers\LaboratorioController::class,'reporteformularios']);
    Route::post('/totalsolicitud',[\App\Http\Controllers\LaboratorioController::class,'totalsolicitud']);
    Route::post('/valpaciente',[\App\Http\Controllers\PacienteController::class,'valpaciente']);

    Route::get('/listAntib/{tipo}',[\App\Http\Controllers\AntibioticoController::class,'listAntib']);
    Route::resource('/cultivo',\App\Http\Controllers\CultivoController ::class);
    Route::resource('/inmunologia',\App\Http\Controllers\InmunologiaController::class);
    Route::post('/listCultivo',[\App\Http\Controllers\CultivoController::class,'listCultivo']);
    Route::post('/listImmunologia',[\App\Http\Controllers\InmunologiaController::class,'listImmunologia']);
    
    Route::resource('/sanguinea',\App\Http\Controllers\SanguineaController::class);
    Route::post('/listSanguinea',[\App\Http\Controllers\SanguineaController::class,'listSanguinea']);
});
