<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function resumen(Request $request)
    {
        $now = Carbon::now();
        $year = (int)$now->year - 1;
        $ini = $request->input('ini', Carbon::create($year, 1, 1)->toDateString());
        $fin = $request->input('fin', Carbon::create($year, 12, 31)->toDateString());

        $validator = Validator::make(
            ['ini' => $ini, 'fin' => $fin],
            ['ini' => 'required|date', 'fin' => 'required|date|after_or_equal:ini']
        );
        if ($validator->fails()) {
            return response()->json(['message' => 'Rango invalido', 'errors' => $validator->errors()], 422);
        }

        $base = $this->queryBase();
        $anual = (clone $base)
            ->whereDate('fechatoma', '>=', $ini)
            ->whereDate('fechatoma', '<=', $fin);

        $totalAnual = (clone $anual)->count();
        $pacientesAnual = (clone $anual)->whereNotNull('paciente_id')->distinct('paciente_id')->count('paciente_id');

        $topTipos = (clone $anual)
            ->select('tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return response()->json([
            'cards' => [
                'total_periodo' => $totalAnual,
                'pacientes_periodo' => $pacientesAnual,
            ],
            'rango' => ['ini' => $ini, 'fin' => $fin],
            'charts' => [
                'top_tipos' => $topTipos,
            ],
        ]);
    }

    private function baseQuery(string $table, string $origen)
    {
        return DB::table($table . ' as e')
            ->leftJoin('pacientes as p', 'p.id', '=', 'e.paciente_id')
            ->leftJoin('doctors as d', 'd.id', '=', 'e.doctor_id')
            ->leftJoin('tipos as t', 't.id', '=', 'e.tipo_id')
            ->selectRaw(
                "e.id, e.paciente_id, e.doctor_id, e.fechatoma, e.solicitud, ? as origen,
                TRIM(CONCAT(COALESCE(p.nombre, ''), ' ', COALESCE(p.paterno, ''), ' ', COALESCE(p.materno, ''))) as paciente,
                TRIM(CONCAT(COALESCE(d.nombre, ''), ' ', COALESCE(d.paterno, ''), ' ', COALESCE(d.materno, ''))) as doctor,
                COALESCE(t.nombre, '') as tipo",
                [$origen]
            );
    }

    private function queryBase()
    {
        $union = $this->baseQuery('laboratorios', 'LABORATORIO')
            ->unionAll($this->baseQuery('cultivos', 'CULTIVO'))
            ->unionAll($this->baseQuery('inmunologias', 'INMUNOLOGIA'))
            ->unionAll($this->baseQuery('sanguineas', 'SANGUINEA'));

        return DB::query()->fromSub($union, 'r');
    }
}
