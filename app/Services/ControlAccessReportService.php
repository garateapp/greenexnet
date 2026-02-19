<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\ControlAccessLog;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ControlAccessReportService
{
    public function buildForRange(CarbonInterface $start, CarbonInterface $end): Collection
    {
        $entidades = [4, 5, 6, 7, 9];

        $attendanceSub = Attendance::query()
            ->select('personal_id', DB::raw('COUNT(*) as attendance_count'))
            ->whereBetween('timestamp', [$start, $end])
            ->groupBy('personal_id');
        Log::info('attendanceSub: ' . $attendanceSub->toSql());
        Log::info("ControlAccessLog: " . ControlAccessLog::from('control_access_logs as c')
            ->join('personals as p', 'c.personal_id', '=', 'p.codigo')
            ->join('entidads as e', 'e.id', '=', 'p.entidad_id')
            ->leftJoinSub($attendanceSub, 'a', function ($join) {
                $join->on('a.personal_id', '=', 'p.id');
            })
            ->select([
                DB::raw('DATE(c.fecha) as fecha'),
                'p.codigo as personal_id',
                'p.rut',
                'p.nombre',
                'e.nombre as departamento',
                'e.id as entidad_id',
                DB::raw('MIN(c.primera_entrada) as primera_marca'),
                DB::raw('MAX(c.ultima_salida) as ultima_salida'),
                'p.id as personal_db_id',
                DB::raw('COALESCE(a.attendance_count, 0) as attendance_count'),
                DB::raw('CASE WHEN a.attendance_count IS NULL THEN 1 ELSE 0 END as sin_asistencia'),
            ])
            ->whereBetween('c.fecha', [$start, $end])
            ->whereIn('p.entidad_id', $entidades)
            ->whereNull('p.deleted_at')
            ->groupBy(
                DB::raw('DATE(c.fecha)'),
                'p.id',
                'p.codigo',
                'p.rut',
                'p.nombre',
                'e.nombre',
                'e.id',
                'a.attendance_count'
            )
            ->orderBy('fecha')
            ->orderBy('p.rut')
            ->toSql());
        return ControlAccessLog::from('control_access_logs as c')
            ->join('personals as p', 'c.personal_id', '=', 'p.codigo')
            ->join('entidads as e', 'e.id', '=', 'p.entidad_id')
            ->leftJoinSub($attendanceSub, 'a', function ($join) {
                $join->on('a.personal_id', '=', 'p.id');
            })
            ->select([
                DB::raw('DATE(c.fecha) as fecha'),
                'p.codigo as personal_id',
                'p.rut',
                'p.nombre',
                'e.nombre as departamento',
                'e.id as entidad_id',
                DB::raw('MIN(c.primera_entrada) as primera_marca'),
                DB::raw('MAX(c.ultima_salida) as ultima_salida'),
                'p.id as personal_db_id',
                DB::raw('COALESCE(a.attendance_count, 0) as attendance_count'),
                DB::raw('CASE WHEN a.attendance_count IS NULL THEN 1 ELSE 0 END as sin_asistencia'),
            ])
            ->whereBetween('c.fecha', [$start, $end])
            ->whereIn('p.entidad_id', $entidades)
            ->whereNull('p.deleted_at')
            ->groupBy(
                DB::raw('DATE(c.fecha)'),
                'p.id',
                'p.codigo',
                'p.rut',
                'p.nombre',
                'e.nombre',
                'e.id',
                'a.attendance_count'
            )
            ->orderBy('fecha')
            ->orderBy('p.rut')
            ->get();
    }

    public function buildSummary(Collection $rows): array
    {
        $total = $rows->count();
        $sinAsistencia = $rows->where('sin_asistencia', 1)->count();
        $conAsistencia = $total - $sinAsistencia;

        return [
            'total' => $total,
            'con_asistencia' => $conAsistencia,
            'sin_asistencia' => $sinAsistencia,
        ];
    }

    public function buildTotalsByDepartment(Collection $rows): Collection
    {
        return $rows
            ->groupBy('departamento')
            ->map(function ($group, $departamento) {
                return [
                    'departamento' => $departamento,
                    'total' => $group->count(),
                    'con_asistencia' => $group->where('sin_asistencia', 0)->count(),
                    'sin_asistencia' => $group->where('sin_asistencia', 1)->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();
    }
}
