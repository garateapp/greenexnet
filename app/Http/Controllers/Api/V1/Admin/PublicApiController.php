<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PublicApiController extends Controller
{
    public function entidadesTipoDos()
    {
        $entidades = Entidad::query()
            ->where('tipo_id', 2)
            ->orderBy('nombre')
            ->get();

        return response()->json($entidades, Response::HTTP_OK);
    }

    public function diasTrabajados(Request $request)
    {
        $validated = $request->validate([
            'rut' => ['required', 'string'],
            'entidad' => ['required', 'integer', 'exists:entidads,id'],
            'mes' => ['required', 'integer', 'between:1,12'],
        ]);

        $resultado = DB::table('attendances as a')
            ->join('personals as p', 'p.id', '=', 'a.personal_id')
            ->join('entidads as e', 'e.id', '=', 'a.location')
            ->selectRaw('COUNT(a.personal_id) as dias_trabajados, p.nombre as personal_nombre, e.nombre as entidad_nombre')
            ->where('p.rut', $validated['rut'])
            ->where('e.id', $validated['entidad'])
            ->whereMonth('a.timestamp', $validated['mes'])
            ->groupBy('p.nombre', 'e.nombre')
            ->first();

        if (!$resultado) {
            return response()->json([
                'dias_trabajados' => 0,
                'personal_nombre' => null,
                'entidad_nombre' => null,
                'rut' => $validated['rut'],
                'entidad' => (int) $validated['entidad'],
                'mes' => (int) $validated['mes'],
            ], Response::HTTP_OK);
        }

        return response()->json([
            'dias_trabajados' => (int) $resultado->dias_trabajados,
            'personal_nombre' => $resultado->personal_nombre,
            'entidad_nombre' => $resultado->entidad_nombre,
            'rut' => $validated['rut'],
            'entidad' => (int) $validated['entidad'],
            'mes' => (int) $validated['mes'],
        ], Response::HTTP_OK);
    }
}
