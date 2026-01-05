<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmbarqueRequest;
use App\Http\Requests\StoreEmbarqueRequest;
use App\Http\Requests\UpdateEmbarqueRequest;
use App\Models\Embarque;
use App\Imports\ExcelImport;
use App\Exports\SeguimientoEmbarquesExport;
use App\Exports\PackingListExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Services\EmbarqueImporter;
use App\Models\Mensaje;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\MensajeGenericoMailable;
use Illuminate\Database\QueryException;

class EmbarquesController extends Controller
{
    use CsvImportTrait;

    protected int $embarquesIndexLimit = 12000;

    public function index(Request $request)
    {
        abort_if(Gate::denies('embarque_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
        $select = <<<SQL
temporada,
semana,
transporte,
num_embarque,
n_cliente,
planta_carga,
n_naviera,
nave,
num_contenedor,
especie,
variedad,
embalajes,
etiqueta,
SUM(cajas) as cajas,
MAX(cant_pallets) as cant_pallets,
SUM(peso_neto) as peso_neto,
puerto_embarque,
pais_destino,
puerto_destino,
etd_estimado,
eta_estimado,
fecha_zarpe_real,
fecha_arribo_real,
estado,
descargado,
retirado_full,
devuelto_vacio,
MIN(notas) as notas,
num_orden,
tipo_especie
SQL;

$groupColumns = [
    'temporada',
    'semana',
    'transporte',
    'num_embarque',
    'n_cliente',
    'planta_carga',
    'n_naviera',
    'nave',
    'num_contenedor',
    'especie',
    'variedad',
    'embalajes',
    'etiqueta',
    'puerto_embarque',
    'pais_destino',
    'puerto_destino',
    'etd_estimado',
    'eta_estimado',
    'fecha_zarpe_real',
    'fecha_arribo_real',
    'estado',
    'descargado',
    'retirado_full',
    'devuelto_vacio',
    'num_orden',
    'tipo_especie',
];

// reemplazamos las columnas “planas” por sus GROUP_CONCAT
$aggregatedSelect = str_replace(
    ['especie,','variedad,', 'embalajes', 'etiqueta'],
    [
        'GROUP_CONCAT(DISTINCT especie SEPARATOR ", ") as especie,',
        'GROUP_CONCAT(DISTINCT variedad SEPARATOR ", ") as variedad,',
        'GROUP_CONCAT(DISTINCT embalajes SEPARATOR ", ") as embalajes',
        'GROUP_CONCAT(DISTINCT etiqueta SEPARATOR ", ") as etiqueta',
    ],
    $select
);

// hint de MySQL
$optimizedSelect = 'SQL_BIG_RESULT ' . $aggregatedSelect;

// en el GROUP BY no van las columnas agregadas
$groupColumnsForQuery = array_values(
    array_diff($groupColumns, ['especie','variedad', 'embalajes', 'etiqueta'])
);

// columnas base que necesita la subquery interna
$baseColumns = array_values(array_unique(array_merge(
    $groupColumns,
    ['cajas', 'cant_pallets', 'peso_neto', 'notas'] // <-- aquí agregamos notas
)));

$baseQuery = Embarque::query()
    ->select($baseColumns);
    //->whereNull('deleted_at');

// $maxRows = (int) config('reporteria.embarques_index_max_rows', $this->embarquesIndexLimit);

// if (! $request->boolean('mostrar_todos', false)) {
//     $baseQuery->orderByDesc('num_embarque')->limit($maxRows);
// }

$query = DB::query()
    ->fromSub($baseQuery, 'emb')
    ->selectRaw($optimizedSelect)
    ->groupBy($groupColumnsForQuery)
    ->orderBy('num_embarque', 'desc');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', function () {
                return '';
            });

            // $table->editColumn('id', function ($row) {
            //     return $row->id ? $row->id : '';
            // });
            $table->editColumn('temporada', function ($row) {
                return $row->temporada ? Embarque::TEMPORADA_SELECT[$row->temporada] : '';
            });
            $table->editColumn('num_embarque', function ($row) {
                return $row->num_embarque ? $row->num_embarque : '';
            });
            // $table->editColumn('id_cliente', function ($row) {
            //     return $row->id_cliente ? $row->id_cliente : '';
            // });
            $table->editColumn('n_cliente', function ($row) {
                return $row->n_cliente ? $row->n_cliente : '';
            });
            $table->editColumn('planta_carga', function ($row) {
                return $row->planta_carga ? $row->planta_carga : '';
            });
            $table->editColumn('n_naviera', function ($row) {
                return $row->n_naviera ? $row->n_naviera : '';
            });
            $table->editColumn('nave', function ($row) {
                return $row->nave ? $row->nave : '';
            });
            $table->editColumn('num_contenedor', function ($row) {
                return $row->num_contenedor ? $row->num_contenedor : '';
            });
            $table->editColumn('especie', function ($row) {
                return $row->especie ? $row->especie : '';
            });
            $table->editColumn('variedad', function ($row) {
                return $row->variedad ? $row->variedad : '';
            });
            $table->editColumn('embalajes', function ($row) {
                return $row->embalajes ? $row->embalajes : '';
            });
            $table->editColumn('etiqueta', function ($row) {
                return $row->etiqueta ? $row->etiqueta : '';
            });
            $table->editColumn('cajas', function ($row) {
                return $row->cajas ? $row->cajas : '';
            });
            $table->editColumn('peso_neto', function ($row) {
                return $row->peso_neto ? $row->peso_neto : '';
            });
            $table->editColumn('puerto_embarque', function ($row) {
                return $row->puerto_embarque ? $row->puerto_embarque : '';
            });
            $table->editColumn('pais_destino', function ($row) {
                return $row->pais_destino ? $row->pais_destino : '';
            });
            $table->editColumn('puerto_destino', function ($row) {
                return $row->puerto_destino ? $row->puerto_destino : '';
            });
            // $table->editColumn('mercado', function ($row) {
            //     return $row->mercado ? $row->mercado : '';
            // });
            $table->editColumn('etd_estimado', function ($row) {
                return $row->etd_estimado ? Carbon::parse($row->etd_estimado)->format('d-m-Y H:i')  :  '';
            });
            $table->editColumn('eta_estimado', function ($row) {
                return $row->eta_estimado ? Carbon::parse($row->eta_estimado)->format('d-m-Y H:i')  : '';
            });

            $table->editColumn('dias_transito_real', function ($row) {
                return  '';//$row->dias_transito_real ? $row->dias_transito_real :
            });
            $table->editColumn('estado', function ($row) {
                return $row->estado ? Embarque::ESTADO_SELECT[$row->estado] : '';
            });
            $table->editColumn('descargado', function ($row) {
                return $row->descargado ? $row->descargado : '';
            });
            $table->editColumn('retirado_full', function ($row) {
                return $row->retirado_full ? $row->retirado_full : '';
            });
            $table->editColumn('devuelto_vacio', function ($row) {
                return $row->devuelto_vacio ? $row->devuelto_vacio : '';
            });
            $table->editColumn('notas', function ($row) {
                return $row->notas ? $row->notas : '';
            });
            // $table->editColumn('calificacion', function ($row) {
            //     return $row->calificacion ? $row->calificacion : '';
            // });
            // $table->editColumn('pais_conexion', function ($row) {
            //     return $row->pais_conexion ? $row->pais_conexion : '';
            // });
            // $table->editColumn('conexiones', function ($row) {
            //     return $row->conexiones ? $row->conexiones : '';
            // });

            // $table->editColumn('status_aereo', function ($row) {
            //     return $row->status_aereo ? Embarque::STATUS_AEREO_SELECT[$row->status_aereo] : '';
            // });
            $table->editColumn('cant_pallets', function ($row) {
                return $row->cant_pallets ? $row->cant_pallets : '';
            });
            // $table->editColumn('embalaje_std', function ($row) {
            //     return $row->embalaje_std ? $row->embalaje_std : '';
            // });
            $table->editColumn('num_orden', function ($row) {
                return $row->num_orden ? $row->num_orden : '';
            });
            $table->editColumn('tipo_especie', function ($row) {
                return $row->tipo_especie ? $row->tipo_especie : '';
            });
            // $table->editColumn('peso_total', function ($row) {
            //     return $row->peso_total ? $row->peso_total : '';
            // });
            // $table->editColumn('numero_reserva_agente_naviero', function ($row) {
            //     return $row->numero_reserva_agente_naviero ? $row->numero_reserva_agente_naviero : '';
            // });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.embarques.index');
    }

    public function create()
    {
        abort_if(Gate::denies('embarque_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.embarques.create');
    }

    public function store(StoreEmbarqueRequest $request)
    {
        $embarque = Embarque::create($request->all());

        return redirect()->route('admin.embarques.index');
    }

    public function edit(Embarque $embarque)
    {
        abort_if(Gate::denies('embarque_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.embarques.edit', compact('embarque'));
    }

    public function update(UpdateEmbarqueRequest $request, Embarque $embarque)
    {
        $embarque->update($request->all());

        return redirect()->route('admin.embarques.index');
    }

    public function show(Embarque $embarque)
    {
        abort_if(Gate::denies('embarque_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.embarques.show', compact('embarque'));
    }

    public function destroy(Embarque $embarque)
    {
        abort_if(Gate::denies('embarque_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarque->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmbarqueRequest $request)
    {
        $embarques = Embarque::find(request('ids'));

        foreach ($embarques as $embarque) {
            $embarque->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function ImportarEmbarques(Request $request, EmbarqueImporter $importer)
    {
        $targetShipment = $request->filled('num_embarque')
            ? trim($request->input('num_embarque'))
            : null;

        $summary = $importer->import(null, false, $targetShipment);

        $message = $summary['processed'] > 0
            ? sprintf(
                'Importación completada: %d nuevos, %d actualizados. (%d registros omitidos)',
                $summary['created'],
                $summary['updated'],
                $summary['skipped']
            )
            : 'No se encontraron nuevos embarques para importar.';

        return redirect()->route('admin.embarques.index')->with('status', $message);
    }
    public function GuardarEmbarques(Request $request)
    {
        switch ($request->column) {
            case 'Fecha Zarpe Real':
                $campo = 'fecha_zarpe_real';
                break;
            case 'Fecha Arribo Real':
                $campo = 'fecha_arribo_real';
                break;
            case 'Dias Transito Real':
                $campo = 'dias_transito_real';
                break;
            case 'Estado':
                $campo = 'estado';
                break;
            case 'Descargado':
                $campo = 'descargado';
                break;
            case 'Retirado Full':
                $campo = 'retirado_full';
                break;
            case 'Devuelto Vacio':
                $campo = 'devuelto_vacio';
                break;
            case 'Notas':
                $campo = 'notas';
                break;
            case 'Calificación':
                $campo = 'calificacion';
                break;
            case 'Conexiones':
                $campo = 'conexiones';
                break;
            case 'Con Fecha Hora':
                $campo = 'con_fecha_hora';
                break;
            case 'Status Aéreo':
                $campo = 'status_aereo';
                break;
            case 'Num Pallets':
                $campo = 'cant_pallets';
                break;
            case 'Embalaje STD':
                $campo = 'embalaje_std';
                break;
            case 'Num Orden':
                $campo = 'num_orden';
                break;
            case 'Tipo Especie':
                $campo = 'tipo_especie';
                break;
            case 'Días Transito Real':
                $campo = 'dias_transito_real';
                break;
            case 'País Conexión':
                $campo = 'pais_conexion';
                break;
            default:
                $campo = $request->column;
                break;
        }

        $numEmbarque = $request->input('num_embarque');

        if (!$numEmbarque) {
            return response()->json(['message' => 'Número de embarque requerido'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        Embarque::where('num_embarque', $numEmbarque)->update([
            $campo => $request->value,
        ]);

        return response()->json([], Response::HTTP_CREATED);
    }

    public function ActualizaSistemaFX(Request $request)
    {
        foreach ($request->ids as $id) {
            $embarque = Embarque::find($id)->first();
              $actualizacion=DB::connection("sqlsrv")->table('dbo.PKG_Embarques')->where("id_embarque", "=", $embarque->origen_embarque_id)
              ->update([
                  "eta" => $embarque->fecha_arribo_real]);


        }
        return response()->json(['message' => 'Se han actualizado los embarques seleccionados'], Response::HTTP_CREATED);
    }
    public function enviarMail()
    {
       $embarques=Embarque::query()
    ->selectRaw("
        temporada,
        semana,
        transporte,
        num_embarque,
        n_cliente,
        planta_carga,
        n_naviera,
        nave,
        num_contenedor,
        GROUP_CONCAT(DISTINCT especie  ORDER BY especie  SEPARATOR ', ') AS especie,
        GROUP_CONCAT(DISTINCT variedad  ORDER BY variedad  SEPARATOR ', ') AS variedades,
        GROUP_CONCAT(DISTINCT embalajes ORDER BY embalajes SEPARATOR ', ') AS embalajes,
        GROUP_CONCAT(DISTINCT etiqueta  ORDER BY etiqueta  SEPARATOR ', ') AS etiquetas,
        SUM(cajas)     AS cajas,
        cant_pallets,
        SUM(peso_neto) AS peso_neto,
        puerto_embarque,
        pais_destino,
        puerto_destino,
        etd_estimado,
        eta_estimado,
        fecha_zarpe_real,
        estado,
        descargado,
        retirado_full,
        devuelto_vacio,
        notas,
        num_orden,
        tipo_especie
    ")
    ->whereNull('deleted_at')
    //->whereNull('fecha_arribo_real')
    ->groupBy([
        'temporada', 'semana', 'transporte', 'num_embarque', 'n_cliente', 'planta_carga',
        'n_naviera', 'nave', 'num_contenedor',  'puerto_embarque', 'pais_destino',
        'puerto_destino', 'etd_estimado', 'eta_estimado', 'fecha_zarpe_real', 'estado',
        'descargado', 'retirado_full', 'devuelto_vacio', 'notas', 'num_orden', 'tipo_especie',
        'cant_pallets',
    ])
    ->orderByDesc('num_embarque')
    ->get();



        if ($embarques->isEmpty()) {
            return redirect()
                ->route('admin.embarques.index')
                ->with('status', 'No hay embarques pendientes para enviar.');
        }

        $mensaje = new Mensaje();
        $mensaje->mensaje = 'CARGA DIARIA/TMP ' . now()->format('Y');

        $totalsByTransport = $this->buildTotalsByTransport($embarques);
        $totalsByTransportAndClient = $this->buildTotalsByTransportAndClient($embarques);

        $disk = 'local';
        $directory = 'temp';
        $fileName = sprintf('%s/embarques_%s.xlsx', $directory, now()->format('Ymd_His'));

        Storage::disk($disk)->makeDirectory($directory);
        Excel::store(new SeguimientoEmbarquesExport($embarques), $fileName, $disk);

        try {
            //$mailList = collect(explode(',', env('MAIL_LIST_EMBARQUE', 'carlos.alvarez@greenex.cl,carol.padilla@greenex.cl')))
            $mailList = collect(explode(',', 'iromero@greenex.cl,rodrigo.garate@greenex.cl,eduardo.garate@greenex.cl,nadia.lell@greenex.cl,viviana.valdebenito@greenex.cl,marcela.naredo@greenex.cl,roberto.arenas@greenex.cl,exportaciones@greenex.cl,hhoffmann@greenex.cl,esteban.acevedo@greenex.cl,maria.mella@greenex.cl,carol.padilla@greenex.cl,tesoreria@greenex.cl'))
                ->map(fn ($email) => trim($email))
                ->filter()
                ->unique()
                ->values();
             Log::info("Enviando correo de seguimiento de embarques a ", ['mailList' => $mailList]);
            // Dividimos en 2 grupos para evitar bloqueos por cantidad de destinatarios
            $groups = $mailList->split(2);
            foreach ($groups as $index => $group) {
                if ($group->isEmpty()) {
                    continue;
                }
                Log::info("Enviando correo de seguimiento de embarques al grupo " . ($index + 1), ['destinatarios' => $group]);
                Mail::to($group->all())
                    ->send(new MensajeGenericoMailable(
                        $mensaje,
                        $fileName,
                        $disk,
                        $totalsByTransportAndClient,
                        $totalsByTransport
                    ));
            }
        } catch (\Throwable $th) {
            Storage::disk($disk)->delete($fileName);
            Log::error('Error al enviar correo de seguimiento de embarques', ['exception' => $th]);

            return redirect()
                ->route('admin.embarques.index')
                ->with('status', 'Ocurrió un problema al enviar el correo: ' . $th->getMessage());
        }

        Storage::disk($disk)->delete($fileName);

        return redirect()
            ->route('admin.embarques.index')
            ->with('status', 'Correo de seguimiento enviado correctamente.');
    }
    public function packingList(Request $request)
    {
        abort_if(Gate::denies('embarque_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $filters = $this->extractPackingListFilters($request);
        $embarques = $this->buildPackingListQuery($filters)->get();

        $baseOptionsQuery = Embarque::query()->whereNull('deleted_at');

        $normalizeOption = static function ($value) {
            if (is_string($value)) {
                return trim($value);
            }

            if (is_numeric($value)) {
                return (string) $value;
            }

            return $value;
        };

        $filterOptions = [
            'destinatarios' => (clone $baseOptionsQuery)
                ->whereNotNull('n_cliente')
                ->orderBy('n_cliente')
                ->distinct()
                ->pluck('n_cliente')
                ->map($normalizeOption)
                ->unique()
                ->values(),
            'embalajes' => (clone $baseOptionsQuery)
                ->whereNotNull('t_embalaje')
                ->orderBy('t_embalaje')
                ->distinct()
                ->pluck('t_embalaje')
                ->map($normalizeOption)
                ->unique()
                ->values(),
            'paises' => (clone $baseOptionsQuery)
                ->whereNotNull('pais_destino')
                ->orderBy('pais_destino')
                ->distinct()
                ->pluck('pais_destino')
                ->map($normalizeOption)
                ->unique()
                ->values(),
            'naves' => (clone $baseOptionsQuery)
                ->whereNotNull('nave')
                ->orderBy('nave')
                ->distinct()
                ->pluck('nave')
                ->map($normalizeOption)
                ->unique()
                ->values(),
            'contenedores' => (clone $baseOptionsQuery)
                ->whereNotNull('num_contenedor')
                ->orderBy('num_contenedor')
                ->distinct()
                ->pluck('num_contenedor')
                ->map($normalizeOption)
                ->unique()
                ->values(),
            'numeros_embarque' => (clone $baseOptionsQuery)
                ->whereNotNull('num_embarque')
                ->orderBy('num_embarque')
                ->distinct()
                ->pluck('num_embarque')
                ->map($normalizeOption)
                ->unique()
                ->values(),
        ];

        $filterDependencies = Embarque::query()
            ->whereNull('deleted_at')
            ->select([
                'n_cliente as destinatario',
                't_embalaje as embalaje',
                'pais_destino',
                'nave',
                'num_contenedor as contenedor',
                'num_embarque',
            ])
            ->get()
            ->map(function ($row) use ($normalizeOption) {
                $normalized = [
                    'destinatario' => $row->destinatario,
                    'embalaje' => $row->embalaje,
                    'pais_destino' => $row->pais_destino,
                    'nave' => $row->nave,
                    'contenedor' => $row->contenedor,
                    'num_embarque' => $row->num_embarque,
                ];
                return array_map($normalizeOption, $normalized);
            })
            ->unique(function (array $row) {
                return implode('|', array_map(static function ($value) {
                    return $value ?? '';
                }, $row));
            })
            ->values();

        return view('admin.embarques.packing-list', [
            'embarques' => $embarques,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'filterDependencies' => $filterDependencies,
        ]);
    }

    public function packingListExport(Request $request)
    {
        abort_if(Gate::denies('embarque_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $filters = $this->extractPackingListFilters($request);
        $records = $this->buildPackingListQuery($filters)->get();

        if ($records->isEmpty()) {
            return redirect()
                ->route('admin.embarques.packingList', array_filter($filters))
                ->with('status', 'No hay datos para exportar con los filtros seleccionados.');
        }

        $fileName = 'packing_list_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PackingListExport($records), $fileName);
    }
    public function ingresaPackingList(Request $request)
    {

        set_time_limit(300);
        // $fecha = explode("/", $request->fecha);
        // $fechaIni = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0] . ' 00:00:00';
        // $fechaFin = $fecha[2] . '-' . $fecha[1] . '-' . ($fecha[0] + 1) . ' 23:59:59';

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);


        // Cargar el archivo y procesarlo
        $file = $request->file('file');

        $data = Excel::toArray(new ExcelImport, $file);




        // Obtener el número de guía desde la primera fila

        $numeroReferencia = $data[0][0]["numero_instructivo"] ?? null;
        $entry = $data[0][0];

        if (!$numeroReferencia) {
            return response()->json(['error' => 'No se encontró número de referencia en el archivo'], 400);
        }

        $numero_r = DB::connection("sqlsrv")
            ->table('dbo.V_PKG_Embarques')
            ->select('numero_guia_cliente')
            ->where('numero_referencia', '=', $numeroReferencia)
            ->first();

        if (!$numero_r) {
            return response()->json(['error' => 'No se encontró número de guía en la base de datos'], 400);
        }

        // Variables globales (cabecera)
        $id_adm_p_entidades_empresa = 4138;
        $id_adm_p_estados = 2;
        $id_adm_p_entidades_packing = 4138;
        $items = new Collection();

        foreach ($data[0] as $index => $entry) {
            // Saltar el encabezado (primera fila)


            //if (!empty($entry["instructivo"])) {
                // Consultas a la base de datos para los IDs
                $envase = DB::connection("sqlsrv")
                    ->table('dbo.ADM_P_items')
                    ->select('id')
                    ->where('codigo', '=', $entry["c_embalaje"])
                    ->first();

                $categoria = DB::connection("sqlsrv")
                    ->table('dbo.PRO_P_categorias')
                    ->select('id')
                    ->where('codigo', '=', $entry["n_categoria"])
                    ->first();

                $calibre = DB::connection("sqlsrv")
                    ->table('dbo.PRO_P_calibres')
                    ->select('id')
                    ->where('codigo', '=', $entry["c_calibre"])
                    ->first();

                $etiquetas = DB::connection("sqlsrv")
                    ->table('dbo.PRO_P_etiquetas')
                    ->select('id')
                    ->where('nombre', '=', $entry["c_etiqueta"])
                    ->first();

                $productor = DB::connection("sqlsrv")
                    ->table('dbo.ADM_P_entidades')
                    ->select('id')
                    ->where('codigo_sag', '=', rtrim($entry["csg_productor_rotulacion"], '.'))
                    ->first();

                // Asignar variedad de rotulación (si existe)
                $variedad_rotulacion = 0;
                if (!empty($entry["n_variedad_rotulacion"])) {
                    $variedad = DB::connection("sqlsrv")
                        ->table('dbo.PRO_P_variedades')
                        ->select('id')
                        ->where('nombre', '=', $entry["n_variedad_rotulacion"])
                        ->first();
                    $variedad_rotulacion = $variedad->id ?? 0;
                }

                if(!empty($entry["csg_productor_rotulacion"])) {
                    $csg_rotulado = DB::connection("sqlsrv")->table('dbo.ADM_P_CentrosCosto')
                    ->select('id')
                    ->where('id_adm_p_entidades', $productor->id)
                    ->where('id_pro_p_variedades', '=', $variedad_rotulacion)
                    ->first();
                }

                if($entry["folio"]){
                $items->push([
                    'id_pkg_stock' => $entry["numero_instructivo"],
                    'folio' => $entry["folio"],
                    'id_adm_p_centroscosto' => $csg_rotulado->id ?? null,
                    'id_adm_p_items' => $envase->id ?? null,
                    'id_pro_p_categorias' => $categoria->id ?? null,
                    'id_pro_p_calibres' => $calibre->id ?? null,
                    'cantidad' => $entry["cantidad"],
                    'peso_neto' => $entry["peso_neto_embalaje"],
                    'creacion_tipo' => 'RFP',
                    'creacion_id' => 3,
                    'destruccion_tipo' => '',
                    'destruccion_id' => 0,
                    'inventario' => 1,
                    'trazabilidad' => 1,
                    'lote_recepcion' => 2,
                    'id_pro_etiquetas' => $etiquetas->id ?? null,
                    'fecha_produccion' => Carbon::parse($this->convertirFechaExcel($entry["fecha_produccion"]))->format('Y-m-d H:i:s'),
                    'id_adm_p_entidad_exportadora' => 22,
                    'id_pro_turno_creacion' => 1,
                    'id_pro_turnos_destruccion' => 0,
                    'fecha_hora_creacion' => date('Y-m-d H:i:s'),
                    'fecha_hora_destruccion' => "1900-01-01 00:00:00",
                    'id_adm_p_entidades_productor_rotulacion' => $productor->id ?? null,
                    'id_pro_p_variedades_rotulacion' => $variedad_rotulacion,
                    'tara_envase' => 0,
                    'id_adm_items_plu' => 2383,
                    'id_adm_p_bodegas_paso' => 1149,
                    'termografo' => 0,
                    'id_adm_p_entidades_packing_origen' => 4138,
                    'id_origen' => 3,
                    'tipo_origen' => 'RFP',
                    'fecha_packing'=>Carbon::parse($this->convertirFechaExcel($entry["fecha_produccion"]))->format('Y-m-d H:i:s'),
                ]);
            }
        }

        // Iniciar una transacción
        // Insertar la cabecera
        $num_i = DB::connection("sqlsrv")->table('PKG_G_Recepcion')->select('numero_i')
            ->where('tipo_i', 'RFP')
            ->where('id_adm_p_entidades_empresa', '=', $id_adm_p_entidades_empresa)
            ->orderBy('tipo_i', 'desc')->limit(1)->get();

        if(count($num_i)==0){
            $numero_i = 1;
        }else{
            $numero_i = $num_i[0]->numero_i + 1;
        }
        // $result = DB::connection("sqlsrv")->table('PKG_G_Recepcion')->insert([
        //     'id_adm_p_entidades_empresa' => '4138', //cambiar a 8581
        //     'tipo_i' => 'RFP',
        //     'numero_i' => $numero_i,
        //     'fecha' => $this->convertirFechaExcel($entry["fecha_de_packing"]), //fecha de carga?
        //     'id_adm_p_estados' => 2,
        //     'aprobado' => 0,
        //     'tipo_d' => 'GD',
        //     'numero_d' => $entry["documento_venta"],
        //     'id_adm_p_entidades_emisor' => '4138',
        //     'id_adm_p_entidades_transportista' => '0',
        //     'id_adm_p_bodegas' => 1091,
        //     'calidad_exportacion' => 0,
        //     'calidad_mercado_interno' => 0,
        //     'id_adm_p_entidades_packing' => 4313,
        //     'transmitido' => 0,
        //     'id_adm_p_entidades_productor_rotulado' => 4313,
        //     'id_origen' => 0,
        //     'origen' => '',
        //     'interplanta' => 0,
        //     'Id_PKG_P_Tratamiento' => 0,
        //     'calidad_desecho' => 100,
        //     //'clave' => ''

        // ]);
        $fecha_packing=Carbon::parse($this->convertirFechaExcel($entry["fecha_produccion"]))->format('d-m-Y H:i:s');

         DB::connection("sqlsrv")->statement('

                                            EXEC PKG_G_Recepcion_Grabar
                                            @id = 0,
                                            @id_adm_p_entidades_empresa = ?,
                                            @tipo_i = ?,
                                            @numero_i = ?,
                                            @fecha = ?,
                                            @id_adm_p_estados = ?,
                                            @aprobado = ?,
                                            @tipo_d = ?,
                                            @numero_d = ?,
                                            @id_adm_p_entidades_emisor = ?,
                                            @id_adm_p_entidades_transportista = ?,
                                            @id_adm_p_bodegas = ?,
                                            @id_adm_p_entidades_packing = ?,
                                            @interplanta = ?,
                                            @id_origen = ?,
                                            @origen = ?,
                                            @Id_PKG_P_Tratamiento = ?,
                                            @id_adm_p_entidades_productor_rotulado = ?',

                                            [
                                                $id_adm_p_entidades_empresa,                    // @id_adm_p_entidades_empresa
                                                'RFP',                   // @tipo_i
                                                $numero_i,               // @numero_i
                                                $fecha_packing, // @fecha
                                                2,                       // @id_adm_p_estados
                                                0,                       // @aprobado
                                                'GD',                    // @tipo_d
                                                0, // @numero_d
                                                $id_adm_p_entidades_empresa,                    // @id_adm_p_entidades_emisor
                                                0,                       // @id_adm_p_entidades_transportista
                                                1129,                    // @id_adm_p_bodegas
                                                $id_adm_p_entidades_packing,                    // @id_adm_p_entidades_packing
                                                0,                       // @interplanta
                                                0,                       // @id_origen
                                                '',                      // @origen
                                                0,                       // @Id_PKG_P_Tratamiento
                                                $productor->id,                     // @id_adm_p_entidades_productor_rotulado
                                            ]);

        // $stock_id = DB::connection("sqlsrv")->table('PKG_Stock')->insertGetId([
        //    'folio'=>$entry["folio"],
        //    'id_adm_p_items_contenedor'=>2733,
        //    'id_adm_p_entidades'=>8892,
        //    'id_pro_p_alturas'=>7,
        //    'tara_contenedor'=>19.5,
        //    'texto_libre_hs'=>'',
        // ]);
        // Insertar los detalles
        Log::critical("message--");
        $origen_id= DB::connection("sqlsrv")->table('PKG_G_Recepcion')
        ->select('id')
        ->where('id_adm_p_entidades_empresa', '=', $id_adm_p_entidades_empresa)
        ->orderBy('id', 'desc')->limit(1)->get();
                                            Log::debug("Origen id: " . $origen_id);
        //CAmbios
        $detalles = [];

        foreach ($items as $item) {
 try {
            DB::connection("sqlsrv")->statement('EXEC PKG_G_Recepcion_Grabar_Detalle
            @id = ?,
            @id_pkg_stock = ?,
            @folio = ?,
            @fecha_cosecha = ?,
            @id_adm_p_centroscosto = ?,
            @id_adm_p_items = ?,
            @id_pro_p_categorias = ?,
            @id_pro_p_calibres = ?,
            @cantidad = ?,
            @peso_neto = ?,
            @creacion_tipo = ?,
            @creacion_id = ?,
            @id_adm_p_bodegas = ?,
            @id_adm_p_entidades = ?,
            @id_pro_p_alturas = ?,
            @id_pro_p_etiquetas = ?,
            @fecha_produccion = ?,
            @id_adm_p_items_contenedor = ?,
            @id_adm_p_entidades_exportadora = ?,
            @id_pro_p_turnos_creacion = ?,
            @id_adm_p_items_plu = ?,
            @tara_contenedor = ?,
            @Id_Adm_P_Contratista = ?,
            @id_adm_p_entidades_packing_origen = ?,
            @id_variedadRotulador = ?,
            @interplanta = ?,
            @textoLibreHS = ?,
            @SecuenciaImpresion = ?',
            [
                0,                                  // @id
                0,                          // @id_pkg_stock
                $item["folio"],                     // @folio
                Carbon::parse($item["fecha_packing"])->format('d-m-Y H:i:s'), // @fecha_cosecha
                $item['id_adm_p_centroscosto'],     // @id_adm_p_centroscosto
                $item['id_adm_p_items'],            // @id_adm_p_items
                $item['id_pro_p_categorias'],       // @id_pro_p_categorias
                $item['id_pro_p_calibres'],         // @id_pro_p_calibres
                $item['cantidad'],                  // @cantidad
                $item['peso_neto']*$item['cantidad'],                 // @peso_neto
                $item['creacion_tipo'],             // @creacion_tipo
                $origen_id[0]->id,               // @creacion_id
                1149,                               // @id_adm_p_bodegas (puedes ajustarlo)
                4138,        // @id_adm_p_entidades
                7,                               // @id_pro_p_alturas
                $item['id_pro_etiquetas'],          // @id_pro_p_etiquetas
                Carbon::parse($item['fecha_produccion'])->format('d-m-Y H:i:s'), // @fecha_produccion
                2733,                               // @id_adm_p_items_contenedor
                $item['id_adm_p_entidad_exportadora'], // @id_adm_p_entidades_exportadora
                $item['id_pro_turno_creacion'],     // @id_pro_p_turnos_creacion
                $item['id_adm_items_plu'],          // @id_adm_p_items_plu
                $item['tara_envase'],               // @tara_contenedor
                null,                               // @Id_Adm_P_Contratista
                $item['id_adm_p_entidades_packing_origen'], // @id_adm_p_entidades_packing_origen
                $item['id_pro_p_variedades_rotulacion'] ?? null, // @id_variedadRotulador
                0,                                  // @interplanta
                '',                                 // @textoLibreHS
                0                                   // @SecuenciaImpresion
            ]);

            } catch (QueryException $e) {

        // Mensaje completo que viene de SQL Server (incluye el RAISERROR)
        $mensaje = $e->getMessage();

        // Detalle técnico del driver (SQLSTATE, código numérico, mensaje)
        $errorInfo = $e->errorInfo; // [sqlstate, code, mensaje]

        Log::error('Error en PKG_G_Recepcion_Grabar_Detalle', [
            'item'      => $item,
            'mensaje'   => $mensaje,
            'sqlstate'  => $errorInfo[0] ?? null,
            'code'      => $errorInfo[1] ?? null,
            'detalle'   => $errorInfo[2] ?? null,
        ]);

        // Si quieres seguir con los demás items:
        // continue;

        // O si quieres cortar todo y devolver algo al usuario:
        throw $e;
    }
        }


        //dd($detalles);
        // Realizar el insert masivo de detalles




        return response()->json(['message' => 'Se han actualizado los embarques seleccionados'], Response::HTTP_CREATED);
    }
    public function ingresagrecepcion()
    {
        return view('admin.embarques.ingresagrecepcion');
    }
    function formatDate2($date)
    {
        try {
            // Excel almacena las fechas como un número de días desde 1900-01-01
            $excelDate = (float)$date;  // Convertir a float para asegurar que el cálculo sea preciso

            // Ajustar el desfase desde 1900-01-01 (día base en Excel)
            $timestamp = Carbon::createFromTimestampUTC((int)(($excelDate - 25569) * 86400));

            // Establecer la zona horaria requerida
            //$timestamp->setTimezone('America/Santiago');

            // Devolver el formato esperado
            return $timestamp->format('d-m-Y H:i');
        } catch (\Exception $e) {
            Log::error("Error al formatear la fecha: " . $e->getMessage());
            return $date; // Si falla, devuelve la fecha original
        }
    }
    function convertirFechaExcel($valorFechaExcel)
    {
        // La fecha base de Excel es el 1 de enero de 1900
        $fechaBase = Carbon::createFromDate(1900, 1, 1);

        // Excel incluye incorrectamente el 29 de febrero de 1900, hay que restar un día
        $fecha = $fechaBase->addDays($valorFechaExcel - 2);

        return $fecha->format('Y-m-d H:i:s');
    }

    protected function buildTotalsByTransport(Collection $embarques): Collection
    {
        return $embarques
            ->groupBy(fn ($row) => $row->transporte ? trim($row->transporte) : 'Sin transporte')
            ->map(function ($group, $transport) {
                return (object) [
                    'transporte' => $transport,
                    'total_pallets' => $this->sumUniquePallets($group),
                    'total_cajas' => (float) $group->sum(fn ($item) => $item->cajas ?? 0),
                    'cargas' => $group->count(),
                ];
            })
            ->values();
    }

    protected function extractPackingListFilters(Request $request): array
    {
        return [
            'destinatario' => $request->filled('destinatario') ? trim($request->input('destinatario')) : null,
            'embalaje' => $request->filled('embalaje') ? trim($request->input('embalaje')) : null,
            'pais_destino' => $request->filled('pais_destino') ? trim($request->input('pais_destino')) : null,
            'nave' => $request->filled('nave') ? trim($request->input('nave')) : null,
            'contenedor' => $request->filled('contenedor') ? trim($request->input('contenedor')) : null,
            'num_embarque' => $request->filled('num_embarque') ? trim($request->input('num_embarque')) : null,
        ];
    }

    protected function buildPackingListQuery(array $filters)
    {
        $query = Embarque::query()
            ->select([
                'transporte',
                'fecha_despacho',
                'num_embarque',
                'c_destinatario',
                'n_cliente',
                'c_packing_origen',
                'folio',
                'etiqueta',
                't_embalaje',
                'peso_std_embalaje',
                'especie',
                'variedad',
                'n_categoria',
                'fecha_produccion',
                'n_productor_rotulacion',
                'csg_productor',
                'comuna_productor_rotulacion',
                'n_calibre',
                'cantidad',
                'num_contenedor',
                'pais_destino',
                'nave',
            ])
            ->whereNull('deleted_at');

        if (!empty($filters['destinatario'])) {
            $query->where('n_cliente', $filters['destinatario']);
        }

        if (!empty($filters['embalaje'])) {
            $query->where('t_embalaje', $filters['embalaje']);
        }

        if (!empty($filters['pais_destino'])) {
            $query->where('pais_destino', $filters['pais_destino']);
        }

        if (!empty($filters['nave'])) {
            $query->where('nave', $filters['nave']);
        }

        if (!empty($filters['contenedor'])) {
            $query->where('num_contenedor', $filters['contenedor']);
        }

        if (!empty($filters['num_embarque'])) {
            $query->where('num_embarque', $filters['num_embarque']);
        }

        return $query->orderByDesc('fecha_despacho')->orderByDesc('created_at');
    }

    protected function buildTotalsByTransportAndClient(Collection $embarques): Collection
    {
        return $embarques
            ->groupBy(function ($row) {
                $client = $row->n_cliente ?: 'Sin nombre';
                $transport = $row->transporte ? trim($row->transporte) : 'Sin transporte';

                return $client . '||' . $transport;
            })
            ->map(function ($group, $key) {
                [$client, $transport] = explode('||', $key, 2);

                return (object) [
                    'n_cliente' => $client,
                    'transporte' => $transport,
                    'total_pallets' => $this->sumUniquePallets($group),
                    'total_cajas' => (float) $group->sum(fn ($item) => $item->cajas ?? 0),
                    'cargas' => $group->count(),
                ];
            })
            ->values();
    }

    protected function sumUniquePallets(Collection $embarques): float
    {
        return (float) $embarques
            ->unique('num_embarque')
            ->sum(fn ($item) => $item->cant_pallets ?? 0);
    }

}
