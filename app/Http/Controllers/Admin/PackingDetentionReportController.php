<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackingLineDetention;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PackingDetentionReportController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('packing'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $filters = $this->validateFilters($request);
        $baseQuery = $this->buildBaseQuery($filters);

        $events = (clone $baseQuery)
            ->select([
                'event_id as id',
                'line',
                'event_date',
                'activation_date',
                'duration_minutes as duration',
                'motivo',
                'causa as cause',
                'notas as notes',
                'estado as status',
            ])
            ->orderByDesc('event_date')
            ->limit(250)
            ->get();

        $kpis = (clone $baseQuery)
            ->selectRaw('COUNT(*) as total_events')
            ->selectRaw('SUM(duration_minutes) as total_duration')
            ->selectRaw('AVG(CAST(duration_minutes as FLOAT)) as avg_duration')
            ->selectRaw("SUM(CASE WHEN estado = 'Activo' THEN 1 ELSE 0 END) as active_events")
            ->first();

        $topCauses = (clone $baseQuery)
            ->selectRaw('causa as cause')
            ->selectRaw('COUNT(*) as total_events')
            ->selectRaw('SUM(duration_minutes) as total_duration')
            ->groupBy('causa')
            ->orderByDesc('total_events')
            ->limit(10)
            ->get();

        $lineComparison = (clone $baseQuery)
            ->selectRaw('line')
            ->selectRaw('COUNT(*) as total_events')
            ->selectRaw('SUM(duration_minutes) as total_duration')
            ->groupBy('line')
            ->orderByDesc('total_duration')
            ->get();

        $trendSeries = (clone $baseQuery)
            ->selectRaw('CAST(event_date as date) as event_date')
            ->selectRaw('COUNT(*) as total_events')
            ->selectRaw('SUM(duration_minutes) as total_duration')
            ->groupBy(DB::raw('CAST(event_date as date)'))
            ->orderBy('event_date')
            ->get();

        $motivoBreakdown = (clone $baseQuery)
            ->selectRaw('motivo')
            ->selectRaw('COUNT(*) as total_events')
            ->groupBy('motivo')
            ->orderByDesc('total_events')
            ->get();

        $lineDailyComparison = (clone $baseQuery)
            ->selectRaw('CAST(event_date as date) as event_date')
            ->selectRaw('line')
            ->selectRaw('SUM(duration_minutes) as total_duration')
            ->groupBy(DB::raw('CAST(event_date as date)'), 'line')
            ->orderBy('event_date')
            ->get();

        $trendCategories = [];
        $trendEvents = [];
        $trendDuration = [];
        foreach ($trendSeries as $row) {
            $trendCategories[] = $row->event_date
                ? Carbon::parse($row->event_date)->format('Y-m-d')
                : 'Sin fecha';
            $trendEvents[] = (int) $row->total_events;
            $trendDuration[] = $this->minutesToHours($row->total_duration);
        }

        $lineCategories = $lineComparison->map(fn ($row) => $row->line ?? 'Sin línea');
        $lineDurations = $lineComparison->pluck('total_duration')->map(fn ($minutes) => $this->minutesToHours($minutes));
        $lineEvents = $lineComparison->pluck('total_events');

        $topCauseCategories = $topCauses->map(fn ($row) => $row->cause ?? 'Sin causa');
        $topCauseCounts = $topCauses->pluck('total_events');

        $motivoLabels = $motivoBreakdown->map(fn ($row) => $row->motivo ?? 'Sin motivo');
        $motivoSeries = $motivoBreakdown->pluck('total_events');

        $dailyCategories = [];
        $dailySeriesMap = [];
        foreach ($lineDailyComparison as $row) {
            $dateLabel = $row->event_date
                ? Carbon::parse($row->event_date)->format('Y-m-d')
                : 'Sin fecha';
            if (!in_array($dateLabel, $dailyCategories, true)) {
                $dailyCategories[] = $dateLabel;
            }
            $lineName = $row->line ?? 'Sin línea';
            if (!array_key_exists($lineName, $dailySeriesMap)) {
                $dailySeriesMap[$lineName] = array_fill(0, count($dailyCategories), 0);
            }
            $dateIndex = array_search($dateLabel, $dailyCategories, true);
            $dailySeriesMap[$lineName][$dateIndex] = $this->minutesToHours($row->total_duration);
        }
        // Ensure later line additions align with categories length
        foreach ($dailySeriesMap as $lineName => &$values) {
            if (count($values) < count($dailyCategories)) {
                $values = array_pad($values, count($dailyCategories), 0);
            }
        }
        unset($values);

        $lineDailySeries = collect($dailySeriesMap)->map(function ($values, $lineName) {
            return [
                'name' => $lineName,
                'data' => array_values($values),
            ];
        })->values();

        $chartPayload = [
            'trend' => [
                'categories' => $trendCategories,
                'events' => $trendEvents,
                'duration' => $trendDuration,
            ],
            'lineComparison' => [
                'categories' => $lineCategories,
                'duration' => $lineDurations,
                'events' => $lineEvents,
            ],
            'topCauses' => [
                'categories' => $topCauseCategories,
                'counts' => $topCauseCounts,
            ],
            'motivos' => [
                'labels' => $motivoLabels,
                'series' => $motivoSeries,
            ],
            'lineDaily' => [
                'categories' => $dailyCategories,
                'series' => $lineDailySeries,
            ],
        ];

        return view('admin.packing.detenciones', [
            'filters' => $filters,
            'lines' => $this->getLines(),
            'events' => $events,
            'kpis' => $this->formatKpis($kpis),
            'topCauses' => $topCauses,
            'lineComparison' => $lineComparison,
            'chartPayload' => $chartPayload,
        ]);
    }

    private function validateFilters(Request $request): array
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'line_name' => ['nullable', 'string', 'max:255'],
        ]);

        $start = !empty($validated['start_date'])
            ? Carbon::createFromFormat('Y-m-d', $validated['start_date'])->startOfDay()
            : now()->subDays(14)->startOfDay();

        $end = !empty($validated['end_date'])
            ? Carbon::createFromFormat('Y-m-d', $validated['end_date'])->endOfDay()
            : now()->endOfDay();

        return [
            'start' => $start,
            'end' => $end,
            'line_name' => $validated['line_name'] ?? null,
            'start_for_input' => $start->format('Y-m-d'),
            'end_for_input' => $end->format('Y-m-d'),
        ];
    }

    private function buildBaseQuery(array $filters)
    {
        return PackingLineDetention::query()
            ->when($filters['start'], function ($query, $start) {
                $query->where('event_date', '>=', $start);
            })
            ->when($filters['end'], function ($query, $end) {
                $query->where('event_date', '<=', $end);
            })
            ->when($filters['line_name'], function ($query, $line) {
                $query->where('line', $line);
            });
    }

    private function getLines(): Collection
    {
        return PackingLineDetention::query()
            ->selectRaw('DISTINCT line as name')
            ->whereNotNull('line')
            ->orderBy('name')
            ->get();
    }

    private function minutesToHours($minutes): float
    {
        if (!$minutes) {
            return 0;
        }

        return round($minutes / 60, 2);
    }

    private function formatKpis($raw): array
    {
        if (!$raw) {
            return [
                'total_events' => 0,
                'total_duration_hours' => 0,
                'avg_duration_minutes' => 0,
                'active_ratio' => 0,
                'active_events' => 0,
            ];
        }

        $totalEvents = (int) $raw->total_events;
        $active = (int) $raw->active_events;

        return [
            'total_events' => $totalEvents,
            'total_duration_hours' => $this->minutesToHours($raw->total_duration),
            'avg_duration_minutes' => $raw->avg_duration ? round($raw->avg_duration, 1) : 0,
            'active_ratio' => $totalEvents > 0 ? round(($active / $totalEvents) * 100, 1) : 0,
            'active_events' => $active,
        ];
    }
}
