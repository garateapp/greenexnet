<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\ControlAccessLog;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ControlAccessReportService
{
    public function buildForRange(CarbonInterface $start, CarbonInterface $end): Collection
    {
        $attendanceSub = Attendance::query()
            ->select('personal_id', DB::raw('COUNT(*) as attendance_count'))
            ->whereBetween('timestamp', [$start, $end])
            ->groupBy('personal_id');

        return ControlAccessLog::from('control_access_logs as c')
            ->join('personals as p', 'c.personal_id', '=', 'p.codigo')
            ->leftJoinSub($attendanceSub, 'a', function ($join) {
                $join->on('a.personal_id', '=', 'p.id');
            })
            ->select([
                'c.fecha',
                'c.personal_id',
                'c.nombre',
                'c.departamento',
                'c.primera_entrada',
                'c.ultima_salida',
                'p.id as personal_db_id',
                'p.rut',
                DB::raw('COALESCE(a.attendance_count, 0) as attendance_count'),
                DB::raw('CASE WHEN a.attendance_count IS NULL THEN 1 ELSE 0 END as sin_asistencia'),
            ])
            ->whereBetween('c.fecha', [$start, $end])
            ->where(function ($query) use ($end) {
                $query->whereNull('p.deleted_at')
                    ->orWhere('p.deleted_at', '>', $end);
            })
            ->orderBy('c.fecha')
            ->orderBy('c.personal_id')
            ->get();
    }
}
