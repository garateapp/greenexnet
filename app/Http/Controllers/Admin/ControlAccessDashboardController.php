<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ControlAccessLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ControlAccessDashboardController extends Controller
{
    protected array $rangeOptions = [
        'today' => 'Hoy',
        'yesterday' => 'Ayer',
        'last7' => 'Últimos 7 días',
        'month' => 'Mes actual',
    ];

    protected array $trendWindows = [7, 15, 30];

    protected array $contractorGroups = [
        'Valsán Ltda' => ['valsan', 'valsán', 'valsan ltda', 'valsan noche'],
        'Las Orquídeas SpA' => ['las orquideas', 'las orquídeas'],
        'Isaias Ballesteros' => ['isaias ballesteros', 'isaias ballesteros noche'],
        'Fernando Urbina' => ['fernando urbina'],
    ];

    public function index(Request $request)
    {
        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->input('date'))->startOfDay()
            : Carbon::today();

        $dayStart = $selectedDate->copy()->startOfDay();
        $dayEnd = $selectedDate->copy()->endOfDay();
        $dayShiftStart = $dayStart->copy()->setTime(8, 0);
        $dayShiftEnd = $dayStart->copy()->setTime(16, 40);
        $nightShiftEnd = $dayStart->copy()->addDay()->setTime(6, 0);

        $selectedRange = $request->input('range', 'today');
        if (!array_key_exists($selectedRange, $this->rangeOptions)) {
            $selectedRange = 'today';
        }

        [$rangeStart, $rangeEnd] = $this->resolveRangePeriod($selectedDate->copy(), $selectedRange);

        $selectedDepartment = $request->filled('department')
            ? trim($request->input('department'))
            : null;

        $rangeQuery = ControlAccessLog::query()
            ->whereBetween('fecha', [$rangeStart->copy()->startOfDay(), $rangeEnd->copy()->endOfDay()]);

        $entriesQuery = ControlAccessLog::query()
            ->whereBetween('primera_entrada', [$dayStart, $dayEnd]);

        $exitsQuery = ControlAccessLog::query()
            ->whereBetween('ultima_salida', [$dayStart, $dayEnd]);

        $dayQuery = ControlAccessLog::query()
            ->where(function ($query) use ($dayStart, $dayEnd) {
                $query->whereBetween('fecha', [$dayStart, $dayEnd])
                    ->orWhereBetween('primera_entrada', [$dayStart, $dayEnd])
                    ->orWhereBetween('ultima_salida', [$dayStart, $dayEnd]);
            });

        if (!empty($selectedDepartment)) {
            $rangeQuery->where('departamento', $selectedDepartment);
            $dayQuery->where('departamento', $selectedDepartment);
            $entriesQuery->where('departamento', $selectedDepartment);
            $exitsQuery->where('departamento', $selectedDepartment);
        }

        $totalInside = (clone $entriesQuery)
            ->whereNull('ultima_salida')
            ->count();

        $totalEntries = (clone $entriesQuery)->count();

        $totalExits = (clone $exitsQuery)->count();

        $uniqueToday = (clone $entriesQuery)
            ->select('personal_id')
            ->whereNotNull('personal_id')
            ->distinct()
            ->count('personal_id');

        $todayLogs = (clone $entriesQuery)->get();

        $todayInsideLogs = $todayLogs->filter(fn (ControlAccessLog $log) => empty($log->ultima_salida));

        $typeDistribution = $this->buildTypeDistribution($todayInsideLogs);
        $contractorRatio = $totalInside > 0
            ? round(($typeDistribution['contractor'] / $totalInside) * 100, 1)
            : 0;

        $shiftStats = [
            'day' => ['total' => 0, 'inside' => 0],
            'night' => ['total' => 0, 'inside' => 0],
        ];

        foreach ($todayLogs as $log) {
            $shift = $this->determineShift($log, $dayShiftStart, $dayShiftEnd, $nightShiftEnd);
            $shiftStats[$shift]['total']++;
            if (empty($log->ultima_salida)) {
                $shiftStats[$shift]['inside']++;
            }
        }

        $trendStart = $selectedDate->copy()->subDays(29);

        $dailyUniqueCounts = ControlAccessLog::query()
            ->select([
                DB::raw('DATE(fecha) as log_date'),
                DB::raw('COUNT(DISTINCT personal_id) as total'),
            ])
            ->whereBetween('fecha', [$trendStart->copy()->startOfDay(), $selectedDate->copy()->endOfDay()])
            ->groupBy(DB::raw('DATE(fecha)'))
            ->orderBy('log_date')
            ->get()
            ->mapWithKeys(function ($row) {
                $key = Carbon::parse($row->log_date)->format('Y-m-d');

                return [$key => (int) $row->total];
            })
            ->toArray();

        $dailyTrendSeries = $this->buildDateSeries($trendStart, $selectedDate, $dailyUniqueCounts);

        $weeklyAverage = $this->calculateAverage($dailyUniqueCounts, 7, $selectedDate->format('Y-m-d'));
        $uniqueDelta = $uniqueToday - $weeklyAverage;

        $deptStats = (clone $dayQuery)
            ->select([
                'departamento',
                DB::raw('COUNT(*) as total_registros'),
                DB::raw('SUM(CASE WHEN ultima_salida IS NULL THEN 1 ELSE 0 END) as dentro'),
            ])
            ->groupBy('departamento')
            ->orderByDesc('dentro')
            ->get();

        $deptChart = [
            'labels' => $deptStats->pluck('departamento')->map(fn ($dept) => $dept ?: 'Sin departamento'),
            'inside' => $deptStats->pluck('dentro')->map(fn ($value) => (int) $value),
            'outside' => $deptStats->map(function ($stat) {
                $outside = (int) $stat->total_registros - (int) $stat->dentro;

                return $outside > 0 ? $outside : 0;
            }),
        ];

        $departmentOptions = ControlAccessLog::query()
            ->select('departamento')
            ->whereBetween('primera_entrada', [$dayStart, $dayEnd])
            ->whereNotNull('departamento')
            ->distinct()
            ->orderBy('departamento')
            ->pluck('departamento');

        $hours = collect(range(0, 23))->map(fn ($hour) => str_pad((string) $hour, 2, '0', STR_PAD_LEFT));

        $entriesByHour = (clone $entriesQuery)
            ->get()
            ->groupBy(fn ($log) => optional($log->primera_entrada)->format('H'))
            ->map->count();

        $exitsByHour = (clone $exitsQuery)
            ->get()
            ->groupBy(fn ($log) => optional($log->ultima_salida)->format('H'))
            ->map->count();

        $hourlySeries = [
            'labels' => $hours,
            'entries' => $hours->map(fn ($hour) => (int) ($entriesByHour->get($hour) ?? 0)),
            'exits' => $hours->map(fn ($hour) => (int) ($exitsByHour->get($hour) ?? 0)),
        ];

        $latestMovements = (clone $dayQuery)
            ->orderByDesc('primera_entrada')
            ->limit(10)
            ->get();

        $topContractors = $this->buildTopContractorsTable($todayInsideLogs);

        $rangeLogs = (clone $rangeQuery)
            ->whereNotNull('primera_entrada')
            ->get();

        $contractorBarData = $this->buildContractorContributionData($rangeLogs);
        $contractorStayTrend = $this->buildContractorStayTrend($rangeLogs, $rangeStart, $rangeEnd);

        $donutData = [
            'labels' => ['Empleado', 'Contratista', 'Visita'],
            'series' => [
                $typeDistribution['employee'],
                $typeDistribution['contractor'],
                $typeDistribution['visitor'],
            ],
        ];

        $gaugeMax = max(
            (int) config('control_access.capacity', 500),
            max($dailyTrendSeries['data'] ?? [0]),
            $totalInside
        );

        return view('admin.control-access.dashboard', [
            'selectedDate' => $selectedDate->format('Y-m-d'),
            'selectedDepartment' => $selectedDepartment,
            'selectedRange' => $selectedRange,
            'rangeOptions' => $this->rangeOptions,
            'rangeStart' => $rangeStart->format('Y-m-d'),
            'rangeEnd' => $rangeEnd->format('Y-m-d'),
            'totalInside' => $totalInside,
            'totalEntries' => $totalEntries,
            'totalExits' => $totalExits,
            'uniqueToday' => $uniqueToday,
            'weeklyAverage' => $weeklyAverage,
            'uniqueDelta' => $uniqueDelta,
            'contractorRatio' => $contractorRatio,
            'deptStats' => $deptStats,
            'departmentOptions' => $departmentOptions,
            'hourlySeries' => $hourlySeries,
            'latestMovements' => $latestMovements,
            'deptChart' => $deptChart,
            'dailyTrendSeries' => $dailyTrendSeries,
            'trendWindows' => $this->trendWindows,
            'donutData' => $donutData,
            'topContractors' => $topContractors,
            'contractorBarData' => $contractorBarData,
            'contractorStayTrend' => $contractorStayTrend,
            'gaugeMax' => $gaugeMax,
            'shiftStats' => $shiftStats,
        ]);
    }

    protected function resolveRangePeriod(Carbon $date, string $range): array
    {
        switch ($range) {
            case 'yesterday':
                $start = $date->copy()->subDay()->startOfDay();
                $end = $date->copy()->subDay()->endOfDay();
                break;
            case 'last7':
                $start = $date->copy()->subDays(6)->startOfDay();
                $end = $date->copy()->endOfDay();
                break;
            case 'month':
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();
                break;
            case 'today':
            default:
                $start = $date->copy()->startOfDay();
                $end = $date->copy()->endOfDay();
                break;
        }

        return [$start, $end];
    }

    protected function buildTypeDistribution(Collection $logs): array
    {
        $distribution = [
            'employee' => 0,
            'contractor' => 0,
            'visitor' => 0,
        ];

        foreach ($logs as $log) {
            $type = $this->classifyPersonnelType($log);
            $distribution[$type]++;
        }

        return $distribution;
    }

    protected function classifyPersonnelType(ControlAccessLog $log): string
    {
        if ($this->isContractor($log)) {
            return 'contractor';
        }

        if ($this->isVisitor($log)) {
            return 'visitor';
        }

        return 'employee';
    }

    protected function isContractor(ControlAccessLog $log): bool
    {
        return !empty($this->resolveContractorLabel($log));
    }

    protected function isVisitor(ControlAccessLog $log): bool
    {
        $department = $this->normalize($log->departamento);

        return !empty($department) && Str::contains($department, 'visita');
    }

    protected function resolveContractorLabel(ControlAccessLog $log): ?string
    {
        $candidates = [
            $this->normalize($log->departamento),
            $this->normalize($log->nombre),
        ];

        foreach ($this->contractorGroups as $label => $aliases) {
            foreach ($aliases as $alias) {
                foreach ($candidates as $value) {
                    if (empty($value)) {
                        continue;
                    }

                    if (Str::contains($value, $alias)) {
                        return $label;
                    }
                }
            }
        }

        return null;
    }

    protected function normalize(?string $value): string
    {
        return Str::lower(Str::ascii($value ?? ''));
    }

    protected function buildTopContractorsTable(Collection $logs): Collection
    {
        return $logs
            ->filter(fn ($log) => $this->isContractor($log))
            ->groupBy(fn ($log) => $this->resolveContractorLabel($log) ?? 'Otros')
            ->map->count()
            ->sortDesc()
            ->map(fn ($count, $name) => ['name' => $name, 'count' => $count])
            ->values()
            ->take(5);
    }

    protected function buildContractorContributionData(Collection $logs): array
    {
        $dailyCounts = [];

        foreach ($logs as $log) {
            $label = $this->resolveContractorLabel($log);

            if (!$label) {
                continue;
            }

            $day = optional($log->fecha)->format('Y-m-d') ?? optional($log->primera_entrada)->format('Y-m-d');

            if (!$day) {
                continue;
            }

            $dailyCounts[$label][$day] = ($dailyCounts[$label][$day] ?? 0) + 1;
        }

        $averages = collect($dailyCounts)
            ->map(fn ($days) => round(array_sum($days) / max(count($days), 1), 1))
            ->sortDesc()
            ->take(8);

        return [
            'labels' => $averages->keys()->values(),
            'data' => $averages->values(),
        ];
    }

    protected function buildContractorStayTrend(Collection $logs, Carbon $start, Carbon $end): array
    {
        $dailyTotals = [];

        foreach ($logs as $log) {
            if (!$this->isContractor($log)) {
                continue;
            }

            $minutes = $this->calculateStayMinutes($log);

            if ($minutes === null) {
                continue;
            }

            $day = optional($log->fecha)->format('Y-m-d') ?? optional($log->primera_entrada)->format('Y-m-d');

            if (!$day) {
                continue;
            }

            $dailyTotals[$day]['sum'] = ($dailyTotals[$day]['sum'] ?? 0) + $minutes;
            $dailyTotals[$day]['count'] = ($dailyTotals[$day]['count'] ?? 0) + 1;
        }

        $labels = [];
        $data = [];

        $period = CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $end->copy()->startOfDay());

        foreach ($period as $day) {
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('d/m');

            if (!isset($dailyTotals[$key])) {
                $data[] = 0;

                continue;
            }

            $entry = $dailyTotals[$key];
            $data[] = round($entry['sum'] / max($entry['count'], 1), 1);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    protected function calculateStayMinutes(ControlAccessLog $log): ?int
    {
        if (empty($log->primera_entrada) || empty($log->ultima_salida)) {
            return null;
        }

        try {
            return $log->primera_entrada->diffInMinutes($log->ultima_salida);
        } catch (\Throwable $th) {
            return null;
        }
    }

    protected function calculateAverage(array $dailyCounts, int $days, string $currentDateKey): float
    {
        $currentDate = Carbon::parse($currentDateKey);

        $values = [];

        for ($i = 0; $i < $days; $i++) {
            $key = $currentDate->copy()->subDays($i)->format('Y-m-d');

            if (isset($dailyCounts[$key])) {
                $values[] = $dailyCounts[$key];
            }
        }

        if (empty($values)) {
            return 0;
        }

        return round(array_sum($values) / count($values), 1);
    }

    protected function buildDateSeries(Carbon $start, Carbon $end, array $values): array
    {
        $labels = [];
        $keys = [];
        $data = [];

        $period = CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $end->copy()->startOfDay());

        foreach ($period as $day) {
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('d/m');
            $keys[] = $key;
            $data[] = $values[$key] ?? 0;
        }

        return [
            'labels' => $labels,
            'keys' => $keys,
            'data' => $data,
        ];
    }

    protected function determineShift(ControlAccessLog $log, Carbon $dayShiftStart, Carbon $dayShiftEnd, Carbon $nightShiftEnd): string
    {
        $entry = $log->primera_entrada ?? $log->fecha;

        if (!$entry) {
            return 'day';
        }

        $entryTime = $entry->copy();

        if ($entryTime->betweenIncluded($dayShiftStart, $dayShiftEnd)) {
            return 'day';
        }

        if ($entryTime->betweenIncluded($dayShiftEnd, $nightShiftEnd)) {
            return 'night';
        }

        if ($entryTime->lessThan($dayShiftStart)) {
            return 'night';
        }

        return 'day';
    }
}
