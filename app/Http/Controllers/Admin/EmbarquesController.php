<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmbarqueRequest;
use App\Http\Requests\StoreEmbarqueRequest;
use App\Http\Requests\UpdateEmbarqueRequest;
use App\Models\Embarque;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
            //->where(DB::raw('DATEPART(WEEK, etd)'), '>', 48)
            ->where('n_embarque', '>', $cargados->num_embarque)
            ->where('id_exportadora','=','22')
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
    public function testMail(){
        $embarques=Embarque::whereNull('fecha_arribo_real')->orderBy('num_embarque','desc')->get();
        return view('mail.seguimiento-embarques', compact('embarques'));
    }
}
