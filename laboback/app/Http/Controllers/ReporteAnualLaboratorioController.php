<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReporteAnualLaboratorioController extends Controller
{
    public function index(Request $request)
    {
        [$ini, $fin] = $this->resolveRange($request);
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 10;
        $paciente = trim((string) $request->input('paciente', ''));

        $valid = $this->validateRange($ini, $fin);
        if ($valid !== true) {
            return $valid;
        }

        $result = $this->queryReporte($ini, $fin, $paciente)
            ->orderByDesc('fechatoma')
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json(array_merge($result->toArray(), [
            'resumen' => $this->buildResumen($ini, $fin, $paciente),
        ]));
    }

    public function chart(Request $request)
    {
        [$ini, $fin] = $this->resolveRange($request);
        $paciente = trim((string) $request->input('paciente', ''));
        $valid = $this->validateRange($ini, $fin);
        if ($valid !== true) {
            return $valid;
        }

        $query = $this->queryReporte($ini, $fin, $paciente);
        $base = DB::query()->fromSub($query, 'r');
        $porOrigen = (clone $base)
            ->select('origen', DB::raw('COUNT(*) as total'))
            ->groupBy('origen')
            ->orderBy('origen')
            ->get();

        $porTipo = (clone $base)
            ->select('tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->orderBy('tipo')
            ->get();

        return response()->json([
            'por_origen' => $porOrigen,
            'por_tipo' => $porTipo,
        ]);
    }

    public function printAll(Request $request)
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '1024M');

        [$ini, $fin] = $this->resolveRange($request);
        if ($request->boolean('full_year')) {
            $year = (int) date('Y');
            if (!empty($request->input('ini'))) {
                $year = (int) date('Y', strtotime($request->input('ini')));
            }
            $ini = sprintf('%04d-01-01', $year);
            $fin = sprintf('%04d-12-31', $year);
        }
        $valid = $this->validateRange($ini, $fin);
        if ($valid !== true) {
            return $valid;
        }

        $rows = $this->queryReporte($ini, $fin, '')
            ->orderBy('paciente')
            ->orderBy('fechatoma')
            ->get();

        if ($rows->count() > 3000) {
            return response()->json([
                'message' => 'Demasiados registros para PDF en una sola corrida. Use exportar a Excel o reduzca el rango.'
            ], 422);
        }

        return $this->renderPdf($rows, "Reporte de laboratorios ($ini a $fin)", $request->user()->name ?? '');
    }

    public function printByPaciente(Request $request)
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '1024M');

        [$ini, $fin] = $this->resolveRange($request);
        $paciente = trim((string) $request->input('paciente', ''));
        $valid = $this->validateRange($ini, $fin);
        if ($valid !== true) {
            return $valid;
        }

        if ($paciente === '') {
            return response()->json(['message' => 'Debe indicar un paciente para este reporte'], 422);
        }

        $rows = $this->queryReporte($ini, $fin, $paciente)
            ->orderBy('paciente')
            ->orderBy('fechatoma')
            ->get();

        return $this->renderPdf($rows, "Reporte por paciente: $paciente ($ini a $fin)", $request->user()->name ?? '');
    }

    public function exportExcel(Request $request)
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '1024M');

        [$ini, $fin] = $this->resolveRange($request);
        $paciente = trim((string) $request->input('paciente', ''));
        $valid = $this->validateRange($ini, $fin);
        if ($valid !== true) {
            return $valid;
        }

        $rows = $this->queryReporte($ini, $fin, $paciente)
            ->orderBy('paciente')
            ->orderBy('fechatoma')
            ->get();

        $filename = 'reporte_laboratorios_' . $ini . '_' . $fin . '.xls';
        $html = '<html><head><meta charset="UTF-8"><style>
            table{border-collapse:collapse; width:100%;}
            th,td{border:1px solid #cfd8dc; padding:6px; font-size:11px;}
            th{background:#eceff1;}
            h3{margin:0 0 10px 0;}
            .meta{margin-bottom:10px; font-size:11px; color:#455a64;}
        </style></head><body>';
        $html .= '<h3>Reporte de laboratorios</h3>';
        $html .= '<div class="meta">Rango: ' . e($ini) . ' a ' . e($fin) . ' | Total: ' . $rows->count() . '</div>';
        $html .= '<table><thead><tr>
            <th>#</th><th>Fecha</th><th>Origen</th><th>Tipo</th><th>Solicitud</th><th>Paciente</th>
            <th>CI</th><th>Usuario</th><th>Doctor</th><th>Muestra</th><th>Responsable</th>
        </tr></thead><tbody>';
        $i = 1;
        foreach ($rows as $r) {
            $html .= '<tr>';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td>' . e($r->fechatoma) . '</td>';
            $html .= '<td>' . e($r->origen) . '</td>';
            $html .= '<td>' . e($r->tipo) . '</td>';
            $html .= '<td>' . e($r->solicitud) . '</td>';
            $html .= '<td>' . e($r->paciente) . '</td>';
            $html .= '<td>' . e($r->paciente_ci) . '</td>';
            $html .= '<td>' . e($r->usuario) . '</td>';
            $html .= '<td>' . e($r->doctor) . '</td>';
            $html .= '<td>' . e($r->tipomuestra) . '</td>';
            $html .= '<td>' . e($r->responsable_mostrar ?? $r->responsable) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function resolveRange(Request $request): array
    {
        $year = (int) date('Y') - 1;
        $ini = $request->input('ini', sprintf('%04d-01-01', $year));
        $fin = $request->input('fin', sprintf('%04d-12-31', $year));
        return [$ini, $fin];
    }

    private function validateRange(string $ini, string $fin)
    {
        $validator = Validator::make(
            ['ini' => $ini, 'fin' => $fin],
            ['ini' => 'required|date', 'fin' => 'required|date|after_or_equal:ini']
        );

        if ($validator->fails()) {
            return response()->json(['message' => 'Rango de fechas invalido', 'errors' => $validator->errors()], 422);
        }
        return true;
    }

    private function baseQuery(string $table, string $origen, string $ini, string $fin)
    {
        return DB::table($table . ' as e')
            ->leftJoin('pacientes as p', 'p.id', '=', 'e.paciente_id')
            ->leftJoin('users as u', 'u.id', '=', 'e.user_id')
            ->leftJoin('doctors as d', 'd.id', '=', 'e.doctor_id')
            ->leftJoin('tipos as t', 't.id', '=', 'e.tipo_id')
            ->whereDate('e.fechatoma', '>=', $ini)
            ->whereDate('e.fechatoma', '<=', $fin)
            ->selectRaw(
                "e.id, e.paciente_id, e.fechatoma, e.solicitud, e.tipomuestra, e.responsable, ? as origen,
                COALESCE(p.ci, '') as paciente_ci,
                TRIM(CONCAT(COALESCE(p.nombre, ''), ' ', COALESCE(p.paterno, ''), ' ', COALESCE(p.materno, ''))) as paciente,
                COALESCE(u.name, '') as usuario,
                TRIM(CONCAT(COALESCE(d.nombre, ''), ' ', COALESCE(d.paterno, ''), ' ', COALESCE(d.materno, ''))) as doctor,
                CASE
                    WHEN COALESCE(NULLIF(TRIM(e.responsable), ''), '') = '' THEN
                        TRIM(CONCAT(COALESCE(d.nombre, ''), ' ', COALESCE(d.paterno, ''), ' ', COALESCE(d.materno, '')))
                    ELSE e.responsable
                END as responsable_mostrar,
                COALESCE(t.nombre, '') as tipo",
                [$origen]
            );
    }

    private function queryReporte(string $ini, string $fin, string $paciente = '')
    {
        $union = $this->baseQuery('laboratorios', 'LABORATORIO', $ini, $fin)
            ->unionAll($this->baseQuery('cultivos', 'CULTIVO', $ini, $fin))
            ->unionAll($this->baseQuery('inmunologias', 'INMUNOLOGIA', $ini, $fin))
            ->unionAll($this->baseQuery('sanguineas', 'SANGUINEA', $ini, $fin));

        $query = DB::query()->fromSub($union, 'r');
        if ($paciente !== '') {
            $query->where('paciente', 'like', '%' . $paciente . '%');
        }

        return $query;
    }

    private function buildResumen(string $ini, string $fin, string $paciente = ''): array
    {
        $base = $this->queryReporte($ini, $fin, $paciente);
        $totalRegistros = (clone $base)->count();
        $totalPacientes = (clone $base)->whereNotNull('paciente_id')->distinct('paciente_id')->count('paciente_id');

        $porOrigen = (clone $base)
            ->select('origen', DB::raw('COUNT(*) as total'))
            ->groupBy('origen')
            ->pluck('total', 'origen');

        return [
            'total_registros' => $totalRegistros,
            'total_pacientes' => $totalPacientes,
            'total_laboratorios' => (int) ($porOrigen['LABORATORIO'] ?? 0),
            'total_cultivos' => (int) ($porOrigen['CULTIVO'] ?? 0),
            'total_inmunologias' => (int) ($porOrigen['INMUNOLOGIA'] ?? 0),
            'total_sanguineas' => (int) ($porOrigen['SANGUINEA'] ?? 0),
        ];
    }

    private function renderPdf($rows, string $title, string $printedBy = '')
    {
        $logoData = '';
        $logoPath = public_path('img/natividad.jpeg');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('logo.png');
        }
        if (file_exists($logoPath)) {
            $logoData = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
        $printedAt = now()->format('d-m-Y H:i');

        $html = '<html><head><style>
            body{font-family: DejaVu Sans, sans-serif; font-size: 11px;}
            h2{margin: 0 0 4px 0;}
            table{width: 100%; border-collapse: collapse;}
            th, td{border: 1px solid #ddd; padding: 4px;}
            th{background:#f2f2f2; text-align:left;}
            .header{margin-bottom:10px; border-bottom:1px solid #cfd8dc; padding-bottom:8px;}
            .meta{font-size:10px; color:#455a64;}
            .num{width:28px; text-align:center;}
            .logo{height:42px;}
        </style></head><body>';
        $html .= '<div class="header">';
        if ($logoData !== '') {
            $html .= '<img class="logo" src="' . $logoData . '" alt="logo">';
        }
        $html .= '<h2>' . e($title) . '</h2>';
        $html .= '<div class="meta">Impreso: ' . e($printedAt) . ' | Usuario: ' . e($printedBy !== '' ? $printedBy : 'N/D') . '</div>';
        $html .= '</div>';
        $html .= '<table><thead><tr>
            <th class="num">#</th><th>Fecha</th><th>Origen</th><th>Tipo</th><th>Solicitud</th><th>Paciente</th>
            <th>CI</th><th>Usuario</th><th>Doctor</th><th>Muestra</th><th>Responsable</th>
        </tr></thead><tbody>';

        $index = 1;
        foreach ($rows as $r) {
            $html .= '<tr>';
            $html .= '<td class="num">' . $index++ . '</td>';
            $html .= '<td>' . e($r->fechatoma) . '</td>';
            $html .= '<td>' . e($r->origen) . '</td>';
            $html .= '<td>' . e($r->tipo) . '</td>';
            $html .= '<td>' . e($r->solicitud) . '</td>';
            $html .= '<td>' . e($r->paciente) . '</td>';
            $html .= '<td>' . e($r->paciente_ci) . '</td>';
            $html .= '<td>' . e($r->usuario) . '</td>';
            $html .= '<td>' . e($r->doctor) . '</td>';
            $html .= '<td>' . e($r->tipomuestra) . '</td>';
            $html .= '<td>' . e($r->responsable_mostrar ?? $r->responsable) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table></body></html>';

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html)->setPaper('letter', 'landscape');
        $binary = $pdf->output();
        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reporte_laboratorios.pdf"',
        ]);
    }
}
