<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmbarqueRequest;
use App\Http\Requests\StoreEmbarqueRequest;
use App\Http\Requests\UpdateEmbarqueRequest;
use App\Models\Embarque;
use App\Imports\ExcelImport;
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
use App\Models\Mensaje;
use App\Mail\MiMailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeGenericoMailable;

class EmbarquesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('embarque_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Embarque::query()->select(sprintf('%s.*', (new Embarque)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'embarque_show';
                $editGate      = 'embarque_edit';
                $deleteGate    = 'embarque_delete';
                $crudRoutePart = 'embarques';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('temporada', function ($row) {
                return $row->temporada ? Embarque::TEMPORADA_SELECT[$row->temporada] : '';
            });
            $table->editColumn('num_embarque', function ($row) {
                return $row->num_embarque ? $row->num_embarque : '';
            });
            $table->editColumn('id_cliente', function ($row) {
                return $row->id_cliente ? $row->id_cliente : '';
            });
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
            $table->editColumn('mercado', function ($row) {
                return $row->mercado ? $row->mercado : '';
            });
            $table->editColumn('etd_estimado', function ($row) {
                return $row->etd_estimado ? Carbon::parse($row->etd_estimado)->format('d-m-Y H:i')  :  '';
            });
            $table->editColumn('eta_estimado', function ($row) {
                return $row->eta_estimado ? Carbon::parse($row->eta_estimado)->format('d-m-Y H:i')  : '';
            });

            $table->editColumn('dias_transito_real', function ($row) {
                return $row->dias_transito_real ? $row->dias_transito_real : '';
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
            $table->editColumn('calificacion', function ($row) {
                return $row->calificacion ? $row->calificacion : '';
            });
            $table->editColumn('pais_conexion', function ($row) {
                return $row->pais_conexion ? $row->pais_conexion : '';
            });
            $table->editColumn('conexiones', function ($row) {
                return $row->conexiones ? $row->conexiones : '';
            });

            $table->editColumn('status_aereo', function ($row) {
                return $row->status_aereo ? Embarque::STATUS_AEREO_SELECT[$row->status_aereo] : '';
            });
            $table->editColumn('num_pallets', function ($row) {
                return $row->num_pallets ? $row->num_pallets : '';
            });
            $table->editColumn('embalaje_std', function ($row) {
                return $row->embalaje_std ? $row->embalaje_std : '';
            });
            $table->editColumn('num_orden', function ($row) {
                return $row->num_orden ? $row->num_orden : '';
            });
            $table->editColumn('tipo_especie', function ($row) {
                return $row->tipo_especie ? $row->tipo_especie : '';
            });
            $table->editColumn('peso_total', function ($row) {
                return $row->peso_total ? $row->peso_total : '';
            });
            $table->editColumn('numero_reserva_agente_naviero', function ($row) {
                return $row->numero_reserva_agente_naviero ? $row->numero_reserva_agente_naviero : '';
            });
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
    public function ImportarEmbarques()
    {

        $cargados = Embarque::orderBy('num_embarque', 'desc')->first();

        $embarques = DB::connection("sqlsrv")->table('dbo.V_PKG_Embarques')
            ->select(
                'n_embarque',
                'id_destinatario',
                'n_destinatario',
                'fecha_embarque',
                DB::RAW('DATEPART(WEEK, etd) as Semana'),
                'n_packing_origen',
                'n_naviera',
                'n_nave',
                'contenedor',
                'N_Especie',
                'N_Variedad',
                'n_embalaje',
                't_embalaje',
                'n_etiqueta',
                DB::RAW('SUM(Cantidad) as Cajas'),
                DB::RAW('SUM(peso_neto) as Peso_neto'),
                'n_puerto_origen',
                'n_pais_destino',
                'n_puerto_destino',
                'transporte',
                'n_packing_origen',
                'etd',
                'eta',
                'numero_reserva_agente_naviero',
                'total_pallets',
                'numero_referencia',
                'nave'
            )
            ->where(DB::raw('DATEPART(WEEK, etd)'), '>', 43)
            //->where('n_embarque', '>', $cargados->num_embarque)
            ->where('id_exportadora', '=', '22')
            ->whereNotNull('id_destinatario')
            ->whereNotNull('n_destinatario')
            ->groupBy(
                'n_embarque',

                'n_destinatario',
                'id_destinatario',
                'fecha_embarque',
                'n_packing_origen',
                'n_naviera',
                'n_nave',
                'contenedor',
                'N_Especie',
                'N_Variedad',
                'n_embalaje',
                't_embalaje',
                'n_etiqueta',
                'n_puerto_origen',
                'n_pais_destino',
                'n_puerto_destino',
                'transporte',
                'n_packing_origen',
                'total_pallets',
                'etd',
                'eta',
                'numero_reserva_agente_naviero',
                'numero_referencia',
                'nave'
            )->get();
        $lstEmbarque = collect();

        foreach ($embarques as $embarque) {
            $objEmbarque = new Embarque();
            $annioEmb = Carbon::parse($embarque->fecha_embarque)->year;
            $agno = date('Y');

            if (date('Y') == Carbon::parse($embarque->fecha_embarque)->year) {
                $temporada = $annioEmb . '-' . ($annioEmb + 1);
            } else {
                $temporada = ($annioEmb - 1) . '-' . ($annioEmb);
            }

            $objEmbarque->temporada = $temporada;
            $objEmbarque->num_embarque = $embarque->n_embarque;
            $objEmbarque->id_cliente = $embarque->id_destinatario;
            $objEmbarque->n_cliente = $embarque->n_destinatario;
            $objEmbarque->semana = $embarque->Semana;
            $objEmbarque->planta_carga = $embarque->n_packing_origen;
            $objEmbarque->n_naviera = $embarque->n_naviera;
            $objEmbarque->nave = $embarque->n_nave;
            $objEmbarque->num_contenedor = $embarque->contenedor;
            $objEmbarque->especie = $embarque->N_Especie;
            $objEmbarque->variedad = $embarque->N_Variedad;
            $objEmbarque->embalajes = $embarque->n_embalaje;
            $objEmbarque->etiqueta = $embarque->n_etiqueta;
            $objEmbarque->cajas = $embarque->Cajas;
            $objEmbarque->peso_neto = $embarque->Peso_neto;
            $objEmbarque->puerto_embarque = $embarque->n_puerto_origen;
            $objEmbarque->pais_destino = $embarque->n_pais_destino;
            $objEmbarque->puerto_destino = $embarque->n_puerto_destino;
            $objEmbarque->mercado = $embarque->transporte;
            $objEmbarque->etd_estimado = Carbon::parse($embarque->etd)->format('d-m-Y H:i:s'); //$embarque->etd;
            $objEmbarque->eta_estimado = Carbon::parse($embarque->eta)->format('d-m-Y H:i:s'); //$embarque->eta;
            $objEmbarque->numero_reserva_agente_naviero = $embarque->numero_reserva_agente_naviero;
            $objEmbarque->cant_pallets = $embarque->total_pallets;
            $objEmbarque->transporte = $embarque->transporte;


            $lstEmbarque->push($objEmbarque);
        }
        $lstEmbarqueAgrupado = $lstEmbarque->groupBy('num_embarque');
        $lstEmbarque = new Collection();
        $lstEmbarque = $lstEmbarqueAgrupado->map(function ($embarqueAgrupado, $num_embarque) {

            return [
                'num_embarque' => $num_embarque,
                'id_cliente' => $embarqueAgrupado[0]->id_cliente,
                'n_cliente' => $embarqueAgrupado[0]->n_cliente,
                'semana' => $embarqueAgrupado[0]->semana,
                'planta_carga' => $embarqueAgrupado[0]->planta_carga,
                'n_naviera' => $embarqueAgrupado[0]->n_naviera,
                'nave' => $embarqueAgrupado[0]->nave,
                'num_contenedor' => $embarqueAgrupado[0]->num_contenedor,
                'especie' => $embarqueAgrupado[0]->especie,
                'variedad' => collect($embarqueAgrupado->pluck('variedad')->toArray())
                    ->filter() // Eliminar valores nulos o vacíos
                    ->unique() // Asegurar valores únicos
                    ->implode(', '),
                'embalajes' => collect($embarqueAgrupado->pluck('embalajes')->toArray())
                    ->filter() // Eliminar valores nulos o vacíos
                    ->map(function ($embalaje) {
                        // Extraer únicamente los valores de Kg con una expresión regular
                        preg_match('/(\d+(?:[.,]\d+)?)\s*kg/i', $embalaje, $matches);
                        return isset($matches[1]) ? $matches[1] . ' Kg' : null;
                    })
                    ->filter() // Eliminar valores nulos generados por embalajes sin Kg
                    ->unique() // Asegurar valores únicos
                    ->implode(', '),
                'etiqueta' => $embarqueAgrupado[0]->etiqueta,
                'cajas' => $embarqueAgrupado->sum('cajas'),
                'peso_neto' => $embarqueAgrupado->sum('peso_neto'),
                'puerto_embarque' => $embarqueAgrupado[0]->puerto_embarque,
                'pais_destino' => $embarqueAgrupado[0]->pais_destino,
                'puerto_destino' => $embarqueAgrupado[0]->puerto_destino,
                'mercado' => $embarqueAgrupado[0]->mercado,
                'etd_estimado' => Carbon::parse($embarqueAgrupado[0]->etd_estimado)->format('d-m-Y H:i:s'), //$embarqueAgrupado[0]->etd_estimado,
                'eta_estimado' => Carbon::parse($embarqueAgrupado[0]->eta_estimado)->format('d-m-Y H:i:s'), //$embarqueAgrupado[0]->eta_estimado,
                'numero_reserva_agente_naviero' => $embarqueAgrupado[0]->numero_reserva_agente_naviero,
                'cant_pallets' => $embarqueAgrupado->sum('cant_pallets'),
                'temporada' => $embarqueAgrupado[0]->temporada,
                'transporte' => $embarqueAgrupado[0]->transporte,
            ];
        });
        foreach ($lstEmbarque as $embarque) {

            $objEmbarque = new Embarque();

            $objEmbarque->temporada = $embarque["temporada"];
            $objEmbarque->num_embarque = $embarque["num_embarque"];
            $objEmbarque->id_cliente = $embarque["id_cliente"];
            $objEmbarque->n_cliente = $embarque["n_cliente"];
            $objEmbarque->semana = $embarque["semana"];
            $objEmbarque->planta_carga = $embarque["planta_carga"];
            $objEmbarque->n_naviera = isset($embarque["n_naviera"]) ? $embarque["n_naviera"] : 'sin información';
            $objEmbarque->nave = (isset($embarque["nave"])) ? $embarque["nave"] : "sin información";
            $objEmbarque->num_contenedor = $embarque["num_contenedor"];
            $objEmbarque->especie = $embarque["especie"];
            $objEmbarque->variedad = $embarque["variedad"];
            $objEmbarque->embalajes = $embarque["embalajes"];
            $objEmbarque->etiqueta = $embarque["etiqueta"];
            $objEmbarque->cajas = $embarque["cajas"];
            $objEmbarque->peso_neto = $embarque["peso_neto"];
            $objEmbarque->puerto_embarque = $embarque["puerto_embarque"];
            $objEmbarque->pais_destino = $embarque["pais_destino"];
            $objEmbarque->puerto_destino = $embarque["puerto_destino"];
            $objEmbarque->mercado = $embarque["mercado"];
            $objEmbarque->etd_estimado = Carbon::parse($embarque["etd_estimado"])->format('d-m-Y H:i:s'); //$embarque["etd_estimado"];
            $objEmbarque->eta_estimado = Carbon::parse($embarque["eta_estimado"])->format('d-m-Y H:i:s'); //$embarque["eta_estimado"];
            $objEmbarque->numero_reserva_agente_naviero = $embarque["numero_reserva_agente_naviero"];
            $objEmbarque->cant_pallets = $embarque["cant_pallets"];
            $objEmbarque->transporte = $embarque["transporte"];

            $objEmbarque->save();
        }



        return redirect()->route('admin.embarques.index');
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

        $embarque = Embarque::find($request->id);
        $embarque->{$campo} = $request->value;
        $embarque->save();

        return response()->json([], Response::HTTP_CREATED);
    }

    public function ActualizaSistemaFX(Request $request)
    {
        foreach ($request->ids as $id) {
            $embarque = Embarque::find($id)->first();
            //  $actualizacion=DB::connection("sqlsrv")->table('dbo.PKG_Embarques')->where("id_embarque", "=", $request->id_embarque)
            //  ->update([
            //      "eta" => $embarque->fecha_arribo_real]);


        }
        return response()->json(['message' => 'Se han actualizado los embarques seleccionados'], Response::HTTP_CREATED);
    }
    public function enviarMail()
    {
        $embarques = Embarque::whereNull('fecha_arribo_real')->where("transporte", "=", "AEREO")->orderBy('num_embarque', 'desc')->get();
        $mensaje = new Mensaje();
        $mensaje->mensaje = 'CARGA DIARIA/TMP 2024-2025';
        Mail::to(['iromero@greenex.cl', 'rodrigo.garate@greenex.cl', 'eduardo.garate@greenex.cl', 'mario.yanez@greenex.cl', 'cobranzas@greenex.cl', 'pcarreno@greenex.cl'])
            ->cc(['comex@greenex.cl', 'hhoffmann@greenex.cl', 'docs@greenex.cl', 'exportaciones@greenex.cl', 'carol.padilla@greenex.cl'])
            ->send(new MensajeGenericoMailable($mensaje, ''));
        return view('mail.seguimiento-embarques', compact('embarques'));
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

        $numeroReferencia = $data[0][0]["instructivo"] ?? null;
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
        $id_adm_p_entidades_empresa = 8581;
        $id_adm_p_estados = 2;
        $id_adm_p_entidades_packing = 8581;
        $items = new Collection();

        foreach ($data[0] as $index => $entry) {
            // Saltar el encabezado (primera fila)


            //if (!empty($entry["instructivo"])) {
                // Consultas a la base de datos para los IDs
                $envase = DB::connection("sqlsrv")
                    ->table('dbo.ADM_P_items')
                    ->select('id')
                    ->where('codigo', '=', $entry["nombre_abre_envase"])
                    ->first();

                $categoria = DB::connection("sqlsrv")
                    ->table('dbo.PRO_P_categorias')
                    ->select('id')
                    ->where('nombre', '=', $entry["categoria"])
                    ->first();

                $calibre = DB::connection("sqlsrv")
                    ->table('dbo.PRO_P_calibres')
                    ->select('id')
                    ->where('codigo', '=', $entry["denominacion_calibre"])
                    ->first();

                $etiquetas = DB::connection("sqlsrv")
                    ->table('dbo.PRO_P_etiquetas')
                    ->select('id')
                    ->where('nombre', '=', $entry["etiqueta_u_emb"])
                    ->first();

                $productor = DB::connection("sqlsrv")
                    ->table('dbo.ADM_P_entidades')
                    ->select('id')
                    ->where('nombre', '=', rtrim($entry["den_csg_rotulado"], '.'))
                    ->first();

                // Asignar variedad de rotulación (si existe)
                $variedad_rotulacion = 0;
                if (!empty($entry["variedad_rotulada"])) {
                    $variedad = DB::connection("sqlsrv")
                        ->table('dbo.PRO_P_variedades')
                        ->select('id')
                        ->where('nombre', '=', $entry["variedad_rotulada"])
                        ->first();
                    $variedad_rotulacion = $variedad->id ?? 0;
                }
                if(!empty($entry["codigo_csp"])) {
                    $csg_rotulado = DB::connection("sqlsrv")->table('dbo.ADM_P_CentrosCosto')
                    ->select('id')
                    ->where('codigo', 'like', $entry["csg_rotulado"].'%')
                    ->where('id_pro_p_variedades', '=', $variedad_rotulacion)
                    ->first();
                }
                if($entry["pallet"]){
                $items->push([
                    'id_pkg_stock' => $entry["instructivo"],
                    'folio' => $entry["pallet"],
                    'id_adm_p_centroscosto' => $csg_rotulado->id ?? null,
                    'id_adm_p_items' => $envase->id ?? null,
                    'id_pro_p_categorias' => $categoria->id ?? null,
                    'id_pro_p_calibres' => $calibre->id ?? null,
                    'cantidad' => $entry["cantidad_entrega"],
                    'peso_neto' => $entry["peso_neto"],
                    'creacion_tipo' => 'RFP',
                    'creacion_id' => 3,
                    'destruccion_tipo' => '',
                    'destruccion_id' => 0,
                    'inventario' => 1,
                    'trazabilidad' => 1,
                    'lote_recepcion' => 2,
                    'id_pro_etiquetas' => $etiquetas->id ?? null,
                    'fecha_produccion' => Carbon::parse($this->convertirFechaExcel($entry["fecha_de_packing"]))->format('d-m-Y'),
                    'id_adm_p_entidad_exportadora' => 22,
                    'id_pro_turno_creacion' => 1,
                    'id_pro_turnos_destruccion' => 0,
                    'fecha_hora_creacion' => date('Y-m-d H:i:s'),
                    'fecha_hora_destruccion' => "1900-01-01 00:00:00",
                    'id_adm_p_entidades_productor_rotulacion' => $productor->id ?? null,
                    'id_pro_p_variedades_rotulacion' => $variedad_rotulacion,
                    'tara_envase' => 0,
                    'id_adm_items_plu' => 2383,
                    'id_adm_p_bodegas_paso' => 1091,
                    'termografo' => 0,
                    'id_adm_p_entidades_packing_origen' => 8581,
                    'id_origen' => 3,
                    'tipo_origen' => 'RFP',
                    'fecha_packing'=>Carbon::parse($this->convertirFechaExcel($entry["fecha_de_packing"]))->format('d-m-Y'),
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
        $fecha_packing=Carbon::parse($this->convertirFechaExcel($entry["fecha_de_packing"]))->format('d-m-Y');
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
                                                $entry["documento_venta"], // @numero_d
                                                $id_adm_p_entidades_empresa,                    // @id_adm_p_entidades_emisor
                                                0,                       // @id_adm_p_entidades_transportista
                                                1129,                    // @id_adm_p_bodegas
                                                $id_adm_p_entidades_packing,                    // @id_adm_p_entidades_packing
                                                0,                       // @interplanta
                                                0,                       // @id_origen
                                                '',                      // @origen
                                                0,                       // @Id_PKG_P_Tratamiento
                                                4313                     // @id_adm_p_entidades_productor_rotulado
                                            ]);

        // $stock_id = DB::connection("sqlsrv")->select('PKG_Stock')->insertGetId([
        //    'folio'=>$entry["pallet"],
        //    'id_adm_p_items_contenedor'=>2733,
        //    'id_adm_p_entidades'=>4138,
        //    'id_pro_p_alturas'=>7,
        //    'tara_contenedor'=>19.5,
        //    'texto_libre_hs'=>'',
        // ]);
        // Insertar los detalles
        $origen_id= DB::connection("sqlsrv")->table('PKG_G_Recepcion')
        ->select('id')

        ->orderBy('id', 'desc')->limit(1)->get();

        //CAmbios
        $detalles = [];
        foreach ($items as $item) {

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
                1091,                               // @id_adm_p_bodegas (puedes ajustarlo)
                8581,        // @id_adm_p_entidades
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

}
