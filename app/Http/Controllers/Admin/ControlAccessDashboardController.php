<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LatestMovementsExport;
use App\Http\Controllers\Controller;
use App\Models\ControlAccessLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ControlAccessDashboardController extends Controller
{
    /**
        * Lista de departamentos considerados contratistas.
        */
    protected array $contractorDepartments = [
        'Valsán Ltda',
        'Fernando Urbina',
        'Las Orquideas SpA',
        'Isaias Ballesteros',
        'Isaias Ballesteros Noche',
        'Valsán Noche',
        'Agricola Lancair',
        'Lancair Noche',
        'Claudia Viera',

    ];

    /**
     * Departamentos por empresa para segmentar dotación.
     */
    protected array $greenexDepartments = [
        'Gerencia Agricola',
        'Gerencia Comercial',
        'Gerencia de Producción',
        'Gerencia General',
        'Logística Comex',
        'RRHH',
        'Gerencia de Administración y Finanzas',
        'Adquisición de Materiales',
        'Control de Calidad GR',
        'Departamento Técnico',
        'Contabilidad Tesorería  y Gestión',
    ];

    protected array $garateDepartments = [
        'Control de Calidad',
        'Inspección SAG',
        'Gerencia Industrial',
        'Mercado Nacional',
        'Frigorífico',
        'Despacho',
        'Bodega',
        'Mantenimiento',
        'Administración Planta',
        'Packing',
        'Recepción Romana y PE',
        'Repaletizaje',
        'Sadema',
    ];

    protected array $sanExpeditoDepartments = [
        'TR 12 Camión Volvo HCJZ-77',
        'Administración San Expedito',
        'TR10 Camión Man GHJG-77',
        'TR15 Tractocamión Volvo LRSX-95',
        'TR16 Camión Volvo KGXC-56',
        'TR20 Tractocamion International HKXW-60',
        'Transporte San Expedito',
    ];

    public function index(Request $request)
    {
        $baseDate = $request->filled('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        $selectedDepartments = $request->input('department', []);
        $selectedDepartments = is_array($selectedDepartments) ? $selectedDepartments : [$selectedDepartments];
        $selectedDepartments = array_values(array_filter($selectedDepartments));
        $onlyContractors = $request->boolean('contractor_only');

        $historicalStart = $request->filled('historical_start')
            ? Carbon::parse($request->input('historical_start'))->startOfDay()
            : Carbon::today()->subDays(6)->startOfDay();
        $historicalEnd = $request->filled('historical_end')
            ? Carbon::parse($request->input('historical_end'))->endOfDay()
            : Carbon::today()->endOfDay();

        if ($historicalEnd->lessThan($historicalStart)) {
            [$historicalStart, $historicalEnd] = [$historicalEnd->copy()->startOfDay(), $historicalStart->copy()->endOfDay()];
        }

        $historicalDepartments = $request->input('historical_departments', []);
        $historicalDepartments = is_array($historicalDepartments) ? $historicalDepartments : [$historicalDepartments];
        $historicalDepartments = array_values(array_filter($historicalDepartments));

        $dayStart = $baseDate->copy()->setTime(6, 0, 0);
        $dayEnd = $baseDate->copy()->setTime(23, 59, 59);
        $dayShiftEnd = $baseDate->copy()->setTime(18, 29, 59);
        $nightShiftStart = $baseDate->copy()->setTime(18, 30, 0);

        // Dotación por departamento (último movimiento del día, sólo los que no registran salida)
        [$whereClause, $bindings] = $this->buildWhereClause($dayStart, $dayEnd, $selectedDepartments, $onlyContractors);

        $departmentCounts = collect(DB::select(
            "
                SELECT
                    t1.departamento,
                    COUNT(t1.personal_id) AS dentro
                FROM (
                    SELECT
                        *,
                        ROW_NUMBER() OVER(PARTITION BY personal_id ORDER BY primera_entrada DESC) AS rn
                    FROM control_access_logs
                    WHERE {$whereClause}
                ) AS t1
                WHERE
                    t1.rn = 1
                    AND t1.ultima_salida IS NULL
                    AND t1.departamento IS NOT NULL
                GROUP BY t1.departamento
            ",
            $bindings
        ));

        $totalInside = $this->buildBaseQuery($dayStart, $dayEnd, $selectedDepartments, $onlyContractors)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $dayShiftInside = $this->buildBaseQuery($dayStart, $dayShiftEnd, $selectedDepartments, $onlyContractors)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $nightShiftInside = $this->buildBaseQuery($nightShiftStart, $dayEnd, $selectedDepartments, $onlyContractors)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $contractorDayInside = $this->buildBaseQuery($dayStart, $dayShiftEnd, $selectedDepartments, true)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $contractorNightInside = $this->buildBaseQuery($nightShiftStart, $dayEnd, $selectedDepartments, true)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $contractorInside = $this->buildBaseQuery($dayStart, $dayEnd, $selectedDepartments, true)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $greenexInside = $this->countByDepartments($dayStart, $dayEnd, $selectedDepartments, $onlyContractors, $this->greenexDepartments);
        $garateInside = $this->countByDepartments($dayStart, $dayEnd, $selectedDepartments, $onlyContractors, $this->garateDepartments);
        $sanExpeditoInside = $this->countByDepartments($dayStart, $dayEnd, $selectedDepartments, $onlyContractors, $this->sanExpeditoDepartments);
        $groupSum = (int) $contractorInside + (int) $garateInside + (int) $sanExpeditoInside + (int) $greenexInside;

        $hourlyRaw = $this->buildBaseQuery($dayStart, $dayEnd, $selectedDepartments, $onlyContractors)
            ->select(DB::raw("DATE_FORMAT(primera_entrada, '%H') as hour"), DB::raw('COUNT(*) as total'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('total', 'hour');

        $hourlySeries = collect(range(0, 23))->map(function ($hour) use ($hourlyRaw) {
            $key = str_pad((string) $hour, 2, '0', STR_PAD_LEFT);
            return (int) ($hourlyRaw[$key] ?? 0);
        });

        $departmentOptions = ControlAccessLog::query()
            ->whereBetween('primera_entrada', [$dayStart, $dayEnd])
            ->whereNotNull('departamento')
            ->distinct()
            ->orderBy('departamento')
            ->pluck('departamento');

        $latestDayStart = $baseDate->copy()->startOfDay();
        $latestDayEnd = $baseDate->copy()->endOfDay();

        $latestMovementsQuery = ControlAccessLog::query()
            ->whereBetween('primera_entrada', [$latestDayStart, $latestDayEnd])
            ->when(!empty($selectedDepartments), fn ($q) => $q->whereIn('departamento', $selectedDepartments))
            ->when($onlyContractors, fn ($q) => $q->whereIn('departamento', $this->contractorDepartments))
            ->select('personal_id', 'nombre', 'departamento', 'primera_entrada', 'ultima_salida')
            ->distinct()
            ->orderByDesc('primera_entrada');

        if ($request->get('export') === 'latest-movements') {
            $fileName = 'movimientos_' . $latestDayStart->format('Ymd') . '.xlsx';

            return Excel::download(
                new LatestMovementsExport($latestMovementsQuery->get()),
                $fileName
            );
        }

        $latestMovements = $latestMovementsQuery->get();

        $deptChart = [
            'labels' => $departmentCounts->pluck('departamento')->map(fn ($d) => $d ?? 'Sin departamento'),
            'series' => $departmentCounts->pluck('dentro')->map(fn ($v) => (int) $v),
        ];

        $contractorPie = [
            'labels' => ['Contratistas', 'Garate', 'San Expedito', 'Greenex', 'Otros'],
            'series' => [
                (int) $contractorInside,
                (int) $garateInside,
                (int) $sanExpeditoInside,
                (int) $greenexInside,
                max((int) $totalInside - $groupSum, 0),
            ],
        ];

        $historicalChart = $this->buildHistoricalSeries($historicalStart, $historicalEnd, $historicalDepartments);

        return view('admin.control-access.dashboard', [
            'selectedDate' => $baseDate->format('Y-m-d'),
            'selectedDepartments' => $selectedDepartments,
            'onlyContractors' => $onlyContractors,
            'totalInside' => $totalInside,
            'dayShiftInside' => $dayShiftInside,
            'nightShiftInside' => $nightShiftInside,
            'contractorDayInside' => $contractorDayInside,
            'contractorNightInside' => $contractorNightInside,
            'greenexInside' => $greenexInside,
            'garateInside' => $garateInside,
            'sanExpeditoInside' => $sanExpeditoInside,
            'departmentCounts' => $departmentCounts,
            'latestMovements' => $latestMovements,
            'departmentOptions' => $departmentOptions,
            'deptChart' => $deptChart,
            'contractorPie' => $contractorPie,
            'hourlySeries' => $hourlySeries,
            'historicalStartDate' => $historicalStart->format('Y-m-d'),
            'historicalEndDate' => $historicalEnd->format('Y-m-d'),
            'historicalDepartments' => $historicalDepartments,
            'historicalChart' => $historicalChart,
        ]);
    }

    protected function buildWhereClause(Carbon $start, Carbon $end, array $departments, bool $onlyContractors): array
    {
        $clauses = ['primera_entrada BETWEEN ? AND ?'];
        $bindings = [$start, $end];

        if (!empty($departments)) {
            $placeholders = implode(',', array_fill(0, count($departments), '?'));
            $clauses[] = "departamento IN ({$placeholders})";
            $bindings = array_merge($bindings, $departments);
        }

        if ($onlyContractors) {
            $placeholders = implode(',', array_fill(0, count($this->contractorDepartments), '?'));
            $clauses[] = "departamento IN ({$placeholders})";
            $bindings = array_merge($bindings, $this->contractorDepartments);
        }

        return [implode(' AND ', $clauses), $bindings];
    }

    protected function buildBaseQuery(Carbon $start, Carbon $end, array $departments, bool $onlyContractors)
    {
        return ControlAccessLog::query()
            ->whereBetween('primera_entrada', [$start, $end])
            ->when(!empty($departments), fn ($q) => $q->whereIn('departamento', $departments))
            ->when($onlyContractors, fn ($q) => $q->whereIn('departamento', $this->contractorDepartments));
    }

    protected function countByDepartments(Carbon $start, Carbon $end, array $selectedDepartments, bool $onlyContractors, array $departments): int
    {
        if (empty($departments)) {
            return 0;
        }

        return $this->buildBaseQuery($start, $end, $selectedDepartments, $onlyContractors)
            ->whereIn('departamento', $departments)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');
    }

    protected function buildHistoricalSeries(Carbon $start, Carbon $end, array $departments): array
    {
        $raw = ControlAccessLog::query()
            ->selectRaw("DATE(primera_entrada) as day")
            ->selectRaw("COUNT(DISTINCT personal_id) as total")
            ->whereBetween('primera_entrada', [$start, $end])
            ->when(!empty($departments), fn ($q) => $q->whereIn('departamento', $departments))
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->pluck('total', 'day');

        $labels = [];
        $data = [];

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $labels[] = $cursor->format('d-m');
            $data[] = (int) ($raw[$key] ?? 0);
            $cursor->addDay();
        }

        return [
            'labels' => $labels,
            'series' => [
                [
                    'name' => 'Personal',
                    'data' => $data,
                ],
            ],
        ];
    }
}
