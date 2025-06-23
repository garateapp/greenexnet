<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialProducto;
use App\Models\Material;
use App\Models\Embalaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Crypt as FacadesCrypt;
use Illuminate\Contracts\Encryption\DecryptException;

use App\Models\Especy;
use App\Models\Cliente;


class TarjadoController extends Controller
{
    public function index(Request $request)
    {
        $materiales = Material::all();
        $embalajes = Embalaje::all();
        $especies = Especy::all();
        $materialesProductos = MaterialProducto::all();


        return view('admin.tarjado.index', compact('materiales', 'embalajes', 'materialesProductos','especies'));


    }
    public function getTarjado(Request $request)
    {
        $especies = $request->input('especie', []);
        $embalaje = $request->input('embalaje');
        $altura = $request->input('altura');
        $fecha = $request->input('fecha');
       // $fecha = !empty($fecha) ? date('Y-m-d', strtotime($fecha)) : now()->format('Y-m-d');

       // Subconsulta Estados Únicos (corregida)
    $estadosUnicos = DB::connection('sqlsrv')
        ->table('V_PKG_Produccion_Salidas')
        ->fromSub(function ($query) {
            $query->from('V_PKG_Produccion_Salidas')
                ->select([
                    'id_g_produccion',
                    'estado',
                    'notas',
                    DB::raw("ROW_NUMBER() OVER (PARTITION BY id_g_produccion ORDER BY estado DESC) AS rn")
                ]);
        }, 't')
        ->where('rn', 1);

    // Subconsulta Folios Repetidos
    $foliosRepetidos = DB::connection('sqlsrv')
        ->table('V_PKG_Produccion_Salidas_XXX')
        ->select([
            'folio',
            DB::raw("COUNT(DISTINCT numero_g_produccion) AS conteo_procesos")
        ])
        ->groupBy('folio');

    // Consulta principal
    $query = DB::connection('sqlsrv')
        ->table('V_PKG_Produccion_Salidas_XXX as a')
        ->joinSub($estadosUnicos, 'e', function ($join) {
            $join->on('a.id_g_produccion', '=', 'e.id_g_produccion');
        })
        ->leftJoinSub($foliosRepetidos, 'f', function ($join) {
            $join->on('a.folio', '=', 'f.folio');
        });

    if (!empty($especies)) {
        $query->whereIn('a.id_especie', $especies);
    }

    if (!empty($embalaje)) {
        $query->whereIn('a.c_embalaje', $embalaje);
    }

    if (!empty($altura)) {
        $query->whereIn('a.c_altura', $altura);
    }

    if (!empty($fecha)) {
        $query->whereDate('a.fecha_g_produccion_sh', '>=', $fecha);
    }

    $results = $query
        ->where('a.t_categoria', 'exportacion')
        ->where('a.tipo_g_produccion', 'PRN')
        ->select([
    'a.n_variedad',
    'a.c_embalaje',
    'a.numero_g_produccion',
    'a.n_calibre',
    'a.c_altura',
    'a.folio',
    'a.fecha_g_produccion',
    'a.n_especie',
    'a.n_variedad_rotulacion',
    'e.estado',
    'e.notas',
    'a.n_categoria',
    DB::raw("SUM(a.cantidad) AS cajas"),
    DB::raw("CASE WHEN MAX(f.conteo_procesos) > 1 THEN 'REVISAR' ELSE NULL END AS revisar_folio"),
])
->groupBy([
    'a.n_variedad',
    'a.c_embalaje',
    'a.numero_g_produccion',
    'a.n_calibre',
    'a.c_altura',
    'a.folio',
    'a.fecha_g_produccion',
    'a.n_especie',
    'a.n_variedad_rotulacion',
    'e.estado',
    'a.n_categoria',
    'e.notas'
])
        ->orderByDesc('a.numero_g_produccion')
        ->get();

    // Agrupación adicional por proceso + calibre (como antes)
    $data = [];
    foreach ($results as $row) {
        $key = $row->numero_g_produccion . '-' .$row->folio.'-'. $row->n_calibre;
        if (!isset($data[$key])) {
            $data[$key] = [
                'especie' => $row->n_especie,
                'variedad' => $row->n_variedad,
                'proceso' => $row->numero_g_produccion,
                'notas' => $row->notas,
                'estado' => $row->estado,
                'embalaje' => $row->c_embalaje,
                'categoria' => $row->n_categoria,
                'altura' => $row->c_altura,
                'folio' => $row->folio,
                'calibres' => [],
            ];
        }
        $data[$key]['calibres'][$row->n_calibre] = $row->cajas;
    }

    $calibres = collect($results)->pluck('n_calibre')->unique()
    ->sort(function ($a, $b) {
        return strnatcmp($a, $b); // Comparación natural
    })->values()->all();

    return response()->json([
        'data' => array_values($data),
        'calibres' => $calibres
    ]);
    }
    public function getMaterialesUtilizados (Request $request)
    {
        $folio= $request->input('folio');
        $cantidad = $request->input('cantidad');
        $embalaje = $request->input('embalaje');
        $altura = $request->input('altura');
        $fecha = $request->input('fecha');
        $embalaje = !empty($embalaje) ? Embalaje::find($embalaje) : null;
        if( !$embalaje) {
            return response()->json(['error' => 'Embalaje no encontrado'], 404);
        }
        $cajaxlinea=$embalaje->cajasxlinea;
        $lineasxpallet=$embalaje->lineasxpallet;
        $costos=MaterialProducto::select()->with(['material'])
            ->where('embalaje_id', $embalaje->id)
            ->get();
        dd($costos);


    return response()->json([
        'materiales' => $materiales,
        'folio' => $folio,
        'embalaje' => $embalaje->c_embalaje,
        'fecha' => $fecha
    ]);


        return response()->json($materialesUtilizados);
    }
}
