<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ControlAccessLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $selectedDepartment = $request->filled('department') ? $request->input('department') : null;
        $onlyContractors = $request->boolean('contractor_only');

        $dayStart = $baseDate->copy()->setTime(6, 0, 0);
        $dayEnd = $baseDate->copy()->setTime(23, 59, 59);
        $dayShiftEnd = $baseDate->copy()->setTime(16, 29, 59);
        $nightShiftStart = $baseDate->copy()->setTime(15, 30, 0);

        // Dotación por departamento (último movimiento del día, sólo los que no registran salida)
        [$whereClause, $bindings] = $this->buildWhereClause($dayStart, $dayEnd, $selectedDepartment, $onlyContractors);

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

        $totalInside = $this->buildBaseQuery($dayStart, $dayEnd, $selectedDepartment, $onlyContractors)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $dayShiftInside = $this->buildBaseQuery($dayStart, $dayShiftEnd, $selectedDepartment, $onlyContractors)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $nightShiftInside = $this->buildBaseQuery($nightShiftStart, $dayEnd, $selectedDepartment, $onlyContractors)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $contractorDayInside = $this->buildBaseQuery($dayStart, $dayShiftEnd, $selectedDepartment, true)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $contractorNightInside = $this->buildBaseQuery($nightShiftStart, $dayEnd, $selectedDepartment, true)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $contractorInside = $this->buildBaseQuery($dayStart, $dayEnd, $selectedDepartment, true)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');

        $greenexInside = $this->countByDepartments($dayStart, $dayEnd, $selectedDepartment, $onlyContractors, $this->greenexDepartments);
        $garateInside = $this->countByDepartments($dayStart, $dayEnd, $selectedDepartment, $onlyContractors, $this->garateDepartments);
        $sanExpeditoInside = $this->countByDepartments($dayStart, $dayEnd, $selectedDepartment, $onlyContractors, $this->sanExpeditoDepartments);
        $groupSum = (int) $contractorInside + (int) $garateInside + (int) $sanExpeditoInside + (int) $greenexInside;

        $hourlyRaw = $this->buildBaseQuery($dayStart, $dayEnd, $selectedDepartment, $onlyContractors)
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

        $latestMovements = ControlAccessLog::query()
            ->whereBetween('primera_entrada', [$dayStart, $dayEnd])
            ->when($selectedDepartment, fn ($q) => $q->where('departamento', $selectedDepartment))
            ->when($onlyContractors, fn ($q) => $q->whereIn('departamento', $this->contractorDepartments))
            ->orderByDesc('primera_entrada')
            ->limit(20)
            ->get();

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

        return view('admin.control-access.dashboard', [
            'selectedDate' => $baseDate->format('Y-m-d'),
            'selectedDepartment' => $selectedDepartment,
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
        ]);
    }

    protected function buildWhereClause(Carbon $start, Carbon $end, ?string $department, bool $onlyContractors): array
    {
        $clauses = ['primera_entrada BETWEEN ? AND ?'];
        $bindings = [$start, $end];

        if ($department) {
            $clauses[] = 'departamento = ?';
            $bindings[] = $department;
        }

        if ($onlyContractors) {
            $placeholders = implode(',', array_fill(0, count($this->contractorDepartments), '?'));
            $clauses[] = "departamento IN ({$placeholders})";
            $bindings = array_merge($bindings, $this->contractorDepartments);
        }

        return [implode(' AND ', $clauses), $bindings];
    }

    protected function buildBaseQuery(Carbon $start, Carbon $end, ?string $department, bool $onlyContractors)
    {
        return ControlAccessLog::query()
            ->whereBetween('primera_entrada', [$start, $end])
            ->when($department, fn ($q) => $q->where('departamento', $department))
            ->when($onlyContractors, fn ($q) => $q->whereIn('departamento', $this->contractorDepartments));
    }

    protected function countByDepartments(Carbon $start, Carbon $end, ?string $department, bool $onlyContractors, array $departments): int
    {
        if (empty($departments)) {
            return 0;
        }

        return $this->buildBaseQuery($start, $end, $department, $onlyContractors)
            ->whereIn('departamento', $departments)
            ->whereNull('ultima_salida')
            ->distinct('personal_id')
            ->count('personal_id');
    }
}
