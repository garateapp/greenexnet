<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackingLineAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PackingLineAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $defaultStart = Carbon::now()->startOfDay()->format('Y-m-d');
        $defaultEnd = Carbon::now()->endOfDay()->format('Y-m-d');

        return view('admin.packing.line-attendances.index', [
            'defaultStart' => $defaultStart,
            'defaultEnd' => $defaultEnd,
        ]);
    }

    public function list(Request $request)
    {
        $query = $this->buildQuery($request);

        $records = $query
            ->orderByDesc('fecha_hora_salida')
            ->get()
            ->map(function (PackingLineAttendance $attendance) {
                return [
                    'personal_name' => $attendance->personal->nombre ?? 'Sin nombre',
                    'personal_rut' => $attendance->personal->rut ?? 'Sin RUT',
                    'location' => $attendance->location_id,
                    'fecha_hora_salida' => optional($attendance->fecha_hora_salida)->format('d-m-Y H:i:s'),
                    'fecha_hora_entrada' => optional($attendance->fecha_hora_entrada)->format('d-m-Y H:i:s') ?? '—',
                    'minutos' => $attendance->minutos ?? 0,
                ];
            });

        return response()->json(['data' => $records]);
    }

    public function export(Request $request)
    {
        $fileName = 'packing_line_attendance_' . now()->format('Ymd_His') . '.csv';
        $query = $this->buildQuery($request);

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Nombre',
                'RUT',
                'Ubicación',
                'Fecha Hora Salida',
                'Fecha Hora Entrada',
                'Minutos',
            ]);

            $query->orderByDesc('fecha_hora_salida')
                ->chunk(500, function ($chunk) use ($handle) {
                    foreach ($chunk as $attendance) {
                        fputcsv($handle, [
                            $attendance->personal->nombre ?? 'Sin nombre',
                            $attendance->personal->rut ?? 'Sin RUT',
                            $attendance->location_id,
                            optional($attendance->fecha_hora_salida)->format('d-m-Y H:i:s'),
                            optional($attendance->fecha_hora_entrada)->format('d-m-Y H:i:s'),
                            $attendance->minutos ?? 0,
                        ]);
                    }
                });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename={$fileName}");

        return $response;
    }

    protected function buildQuery(Request $request)
    {
        $start = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfDay();
        $end = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $search = $request->input('search');
        $rut = $request->input('rut');

        $query = PackingLineAttendance::with('personal')
            ->whereBetween('fecha_hora_salida', [$start, $end]);

        if (!empty($search)) {
            $query->whereHas('personal', function ($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%');
            });
        }

        if (!empty($rut)) {
            $query->whereHas('personal', function ($q) use ($rut) {
                $q->where('rut', 'like', '%' . $rut . '%');
            });
        }

        return $query;
    }
}
