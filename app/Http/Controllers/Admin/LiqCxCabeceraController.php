<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLiqCxCabeceraRequest;
use App\Http\Requests\StoreLiqCxCabeceraRequest;
use App\Http\Requests\UpdateLiqCxCabeceraRequest;
use App\Models\ClientesComex;
use App\Models\LiqCosto;
use App\Models\LiqCxCabecera;
use App\Models\LiquidacionesCx;
use App\Models\Costo;
use App\Models\ExcelDato;
use App\Models\Nafe;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Guid\Guid;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Libs\Liquidaciones;
use App\Libs\Funciones_Globales;
use DB;
use Illuminate\Support\Str;


class LiqCxCabeceraController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('liq_cx_cabecera_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LiqCxCabecera::with(['cliente', 'nave'])->select(sprintf('%s.*', (new LiqCxCabecera)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'liq_cx_cabecera_show';
                $editGate      = 'liq_cx_cabecera_edit';
                $deleteGate    = 'liq_cx_cabecera_delete';
                $crudRoutePart = 'liq-cx-cabeceras';

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
            $table->editColumn('instructivo', function ($row) {
                return $row->instructivo ? $row->instructivo : '';
            });
            $table->addColumn('cliente_nombre_fantasia', function ($row) {
                return $row->cliente ? $row->cliente->nombre_fantasia : '';
            });

            $table->editColumn('cliente.codigo_cliente', function ($row) {
                return $row->cliente ? (is_string($row->cliente) ? $row->cliente : $row->cliente->codigo_cliente) : '';
            });
            $table->addColumn('nave_nombre', function ($row) {
                return $row->nave ? $row->nave->nombre : '';
            });

            $table->editColumn('nave.codigo', function ($row) {
                return $row->nave ? (is_string($row->nave) ? $row->nave : $row->nave->codigo) : '';
            });

            $table->editColumn('tasa_intercambio', function ($row) {
                return $row->tasa_intercambio ? $row->tasa_intercambio : '';
            });
            $table->editColumn('total_costo', function ($row) {
                return $row->total_costo ? $row->total_costo : '';
            });
            $table->editColumn('total_bruto', function ($row) {
                return $row->total_bruto ? $row->total_bruto : '';
            });
            $table->editColumn('total_neto', function ($row) {
                return $row->total_neto ? $row->total_neto : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'cliente', 'nave']);

            return $table->make(true);
        }

        $clientes_comexes = ClientesComex::get();
        $naves            = Nafe::get();

        return view('admin.liqCxCabeceras.index', compact('clientes_comexes', 'naves'));
    }

    public function create()
    {
        abort_if(Gate::denies('liq_cx_cabecera_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $naves = Nafe::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.liqCxCabeceras.create', compact('clientes', 'naves'));
    }

    public function store(StoreLiqCxCabeceraRequest $request)
    {

        $liqCxCabecera = new LiqCxCabecera(); //::create($request->all());
        $liqCxCabecera->instructivo = $request->instructivo;
        $liqCxCabecera->cliente_id = $request->cliente_id;
        $liqCxCabecera->nave_id = $request->nave_id;
        $liqCxCabecera->tasa_intercambio = $request->tasa_intercambio;
        $liqCxCabecera->total_costo = $request->total_costo;
        $liqCxCabecera->total_bruto = $request->total_bruto;
        $liqCxCabecera->total_neto = $request->total_neto;
        $liqCxCabecera->flete_exportadora = $request->flete_exportadora;
        $liqCxCabecera->tipo_transporte = $request->tipo_transporte;
        $liqCxCabecera->factor_imp_destino = $request->factor_imp_destino;
        $liqCxCabecera->eta = $this->convertirFormatoFecha($request->eta);
        $liqCxCabecera->save();
        $excel = new ExcelDato();
        $excel->fecha_arribo = $this->convertirFormatoFecha($request->eta);
        $excel->fecha_liquidacion = $this->convertirFormatoFecha($request->fecha_liquidacion);
        $excel->fecha_venta = $this->convertirFormatoFecha($request->fecha_venta);
        $excel->archivo_id = $this->generateSimpleGuid();
        $excel->master_id = 99;
        $excel->modulo = 1;
        $excel->cliente = $request->cliente_id; //SUNHOLA
        $excel->nombre_archivo = "Subida Manual";
        $excel->tasa = $request->tasa_intercambio;
        $excel->datos = json_encode(["sin datos"]);

        $excel->save();

        return redirect()->route('admin.liq-cx-cabeceras.index');
    }
    function convertirFormatoFecha(string $fecha): string
    {
        // Crear un objeto DateTime a partir de la fecha en formato d/m/Y
        $fechaObjeto = \DateTime::createFromFormat('d/m/Y', $fecha);

        // Verificar si la conversión fue exitosa
        if ($fechaObjeto === false) {
            throw new \Exception("Formato de fecha inválido: $fecha");
        }

        // Retornar la fecha en el formato Y-m-d
        return $fechaObjeto->format('Y-m-d');
    }
    function generateSimpleGuid(): string
    {
        $uniqid = uniqid(mt_rand(), true);
        return substr($uniqid, 0, 8) . '-' . substr($uniqid, 8, 4) . '-' .
            substr($uniqid, 12, 4) . '-' . substr($uniqid, 16, 4) . '-' .
            substr($uniqid, 20, 12);
    }
    public function edit(LiqCxCabecera $liqCxCabecera)
    {
        abort_if(Gate::denies('liq_cx_cabecera_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = ClientesComex::pluck('nombre_fantasia', 'id')->prepend(trans('global.pleaseSelect'), '');

        $naves = Nafe::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $liqCxCabecera->load('cliente', 'nave');

        return view('admin.liqCxCabeceras.edit', compact('clientes', 'liqCxCabecera', 'naves'));
    }
    public function updateInline(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:liquidaciones_cxes,id',
            'field' => 'required|string',
            'value' => 'nullable|string|max:255',
        ]);

        $liquidacion = LiquidacionesCx::find($validated['id']);
        $liquidacion->{$validated['field']} = $validated['value'];
        $liquidacion->save();
        $liq = LiquidacionesCx::where('id', '=', $validated['id'])
            ->first();

        $total_bruto = 0;

        Log::info("liq. " . $liq);
        //$total_bruto = $total_bruto + ((float)$l->cantidad * $l->precio_unitario);


        // $liqCxCabecera = LiqCxCabecera::find($liq->liqcabecera_id);
        // $liqCxCabecera->total_bruto = $total_bruto;
        // $liqCxCabecera->total_neto = $total_bruto - $liqCxCabecera->total_costo;
        // $liqCxCabecera->save();
        return response()->json(['success' => true, 'message' => 'Campo actualizado con éxito']);
    }

    public function getDatosLiqItems(Request $request)
    {

        $liquidacion = LiquidacionesCx::where('liqcabecera_id', $request->id)->get();

        return response()->json(['success' => true, 'message' => 'Campo actualizado con éxito', 'data' => $liquidacion]);
    }
    public function getDatosLiqCostos(Request $request)
    {
        $costos = LiqCosto::where('liq_cabecera_id', $request->id)->get();
        foreach ($costos as $costo) {
            $costo->categoria = (Costo::where("nombre", $costo->nombre_costo)->first() == null) ? 'Sin Categoría' : Costo::where("nombre", $costo->nombre_costo)->first()->categoria;
        }

        return response()->json(['success' => true, 'message' => 'Campo actualizado con éxito', 'data' => $costos]);
    }
    public function update(UpdateLiqCxCabeceraRequest $request, LiqCxCabecera $liqCxCabecera)
    {
        $liqCxCabecera->update($request->all());

        return redirect()->route('admin.liq-cx-cabeceras.index');
    }

    public function show(LiqCxCabecera $liqCxCabecera)
    {
        abort_if(Gate::denies('liq_cx_cabecera_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liqCxCabecera->load('cliente', 'nave');

        return view('admin.liqCxCabeceras.show', compact('liqCxCabecera'));
    }
    public function destroyItem($id)
    {
        $liquidacion = LiquidacionesCx::find($id);

        if (!$liquidacion) {

            return response()->json(['success' => false, 'message' => 'Línea no encontrada.'], 404);
        }

        $liquidacion->delete();

        return response()->json(['success' => true, 'message' => 'Línea eliminada correctamente.']);
    }
    public function destroyCosto($id)
    {
        $costo = LiqCosto::find($id);

        if (!$costo) {
            return response()->json(['success' => false, 'message' => 'costo no encontrado.'], 404);
        }

        $costo->delete();
        $liq = Costo::find($costo->liq_cabecera_id)
            ->get();
        $total_costo = 0;
        foreach ($liq as $l) {
            $total_costo = $total_costo + $l->valor;
        }
        $liqCxCabecera = LiqCxCabecera::find($costo->liq_cabecera_id);
        $liqCxCabecera->total_costo = $total_costo;
        $liqCxCabecera->total_neto = $liqCxCabecera->total_bruto - $total_costo;
        $liqCxCabecera->save();
        return response()->json(['success' => true, 'message' => 'costo eliminada correctamente.']);
    }
    public function destroy(LiqCxCabecera $liqCxCabecera)
    {
        abort_if(Gate::denies('liq_cx_cabecera_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $liqCxCabecera->delete();
        $liqCostos = LiqCosto::where('liq_cabecera_id', '=', $liqCxCabecera->id)->get();
        foreach ($liqCostos as $item) {
            $item->delete();
        }
        $liquidacion = LiquidacionesCx::where('liqcabecera_id', '=', $liqCxCabecera->id)->get();
        foreach ($liquidacion as $liq) {
            $liq->delete();
        }
        $exceldato = ExcelDato::where('instructivo', '=', $liqCxCabecera->instructivo)->first();
        $exceldato->delete();



        return back();
    }

    public function massDestroy(MassDestroyLiqCxCabeceraRequest $request)
    {
        $liqCxCabeceras = LiqCxCabecera::find(request('ids'));

        foreach ($liqCxCabeceras as $liqCxCabecera) {
            $liqCxCabecera->delete();
            $liqCostos = LiqCosto::where('liq_cabecera_id', '=', $liqCxCabecera->id)->get();
            foreach ($liqCostos as $item) {
                $item->delete();
            }
            $liquidacion = LiquidacionesCx::where('liqcabecera_id', '=', $liqCxCabecera->id)->get();
            foreach ($liquidacion as $liq) {
                $liq->delete();
            }
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function actualizarValorGD_Unitario(Request $request)
    {

        $id = $request->id;
        $affectedRows = 0;
        $resEjec = collect();
        $liq = new Liquidaciones();

        // Obtener la sesión correctamente
        $liqs = $liq->ConsolidadoLiquidacionesUnitario($id);

        // Obtener cabeceras
        $liqCxCabeceras = LiqCxCabecera::whereNull('deleted_at')->where('id', $id)->get();

        foreach ($liqCxCabeceras as $liqCxCabecera) {
            try {
                // Obtener despachos
                $despachos = DB::connection('sqlsrv')->table("V_PKG_Despachos")
                    ->select('folio', 'n_variedad', 'c_embalaje', 'n_calibre', 'n_etiqueta', 'id_pkg_stock_det')
                    ->where('tipo_g_despacho', '=', 'GDP')
                    ->where('numero_embarque', '=', str_replace('i', '', str_replace('I', '', $liqCxCabecera->instructivo)))
                    ->get();

                foreach ($despachos as $despacho) {
                    $EFOB = 0;
                    $ECCajas = 0;
                    $valor = 0;

                    $items = $liqs->filter(fn($item) =>
                    $item['folio_fx'] === $despacho->folio && // Comparación exacta
                    strcasecmp($item['variedad'], $despacho->n_variedad) === 0 &&
                    strcasecmp($item['embalaje'], $despacho->c_embalaje) === 0 &&
                    strcasecmp($item['calibre'], $despacho->n_calibre) === 0 &&
                    strcasecmp($item['etiqueta'], $despacho->n_etiqueta) === 0
                );



                    Log::info('Folio despacho: ' . ($despacho->folio ?? 'N/A'));

                    foreach ($items as $item) {
                        $EFOB += $item['FOB_TO_USD'];
                        $ECCajas += $item['Cajas'];
                    }

                    // Evitar división por cero
                    $valor = ($ECCajas > 0) ? ($EFOB / $ECCajas) : 0;

                    $resEjec->push([
                        'folio' => $despacho->folio,
                        'valor' => $valor,
                    ]);
                    try {
                        //   dd(DB::connection('sqlsrv')->getPdo());
                    } catch (\Exception $e) {
                        die("Could not connect to the database.  Please check your configuration. error:" . $e);
                    }
                    // Realizar el UPDATE en la base de datos
                    $affectedRows = DB::connection('sqlsrv')
                        ->table('PKG_Stock_Det')
                        ->where('folio', $despacho->folio)
                        ->where('id', $despacho->id_pkg_stock_det)
                        ->where('destruccion_tipo', 'GDP')
                        ->update(['valor' => $valor]);
                }
            } catch (Exception $e) {
                Log::error("Error al actualizar valor GD en FX: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return response()->json(["message" => "Se modificaron $affectedRows registros", "data" => $affectedRows], 200);
    }
}
