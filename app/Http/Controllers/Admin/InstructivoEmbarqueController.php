<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyInstructivoEmbarqueRequest;
use App\Http\Requests\StoreInstructivoEmbarqueRequest;
use App\Http\Requests\UpdateInstructivoEmbarqueRequest;
use App\Models\AgenteAduana;
use App\Models\BaseRecibidor;
use App\Models\BaseContacto;
use App\Models\Chofer;
use App\Models\ClausulaVentum;
use App\Models\Embarcador;
use App\Models\EmisionBl;
use App\Models\FormaPago;
use App\Models\InstructivoEmbarque;
use App\Models\ModVentum;
use App\Models\Moneda;
use App\Models\Naviera;
use App\Models\PlantaCarga;
use App\Models\Puerto;
use App\Models\PuertoCorreo;
use App\Models\Country;
use App\Models\Tipoflete;
use App\Models\ClientesComex;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\InstructivoMaritimoExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\InstructivoEmbarqueMail;

class InstructivoEmbarqueController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('instructivo_embarque_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = InstructivoEmbarque::with(['embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta'])->select(sprintf('%s.*', (new InstructivoEmbarque)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'instructivo_embarque_show';
                $editGate      = 'instructivo_embarque_edit';
                $deleteGate    = 'instructivo_embarque_delete';
                $crudRoutePart = 'instructivo-embarques';

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

            $table->addColumn('embarcador_nombre', function ($row) {
                return $row->embarcador ? $row->embarcador->nombre : '';
            });

            $table->editColumn('embarcador.nombre', function ($row) {
                return $row->embarcador ? (is_string($row->embarcador) ? $row->embarcador : $row->embarcador->nombre) : '';
            });
            $table->addColumn('agente_aduana_nombre', function ($row) {
                return $row->agente_aduana ? $row->agente_aduana->nombre : '';
            });

            $table->addColumn('consignee_codigo', function ($row) {
                return $row->consignee ? $row->consignee->codigo : '';
            });

            $table->addColumn('naviera_nombre', function ($row) {
                return $row->naviera ? $row->naviera->nombre : '';
            });

            $table->editColumn('num_booking', function ($row) {
                return $row->num_booking ? $row->num_booking : '';
            });
            $table->editColumn('nave', function ($row) {
                return $row->nave ? $row->nave : '';
            });
            $table->editColumn('cut_off', function ($row) {
                return $row->cut_off ? $row->cut_off : '';
            });

            $table->addColumn('puerto_embarque_emails', function ($row) {
                return $row->puerto_embarque ? $row->puerto_embarque->emails : '';
            });

            $table->addColumn('puerto_destino_emails', function ($row) {
                return $row->puerto_destino ? $row->puerto_destino->emails : '';
            });

            $table->addColumn('puerto_descarga_nombre', function ($row) {
                return $row->puerto_descarga ? $row->puerto_descarga->nombre : '';
            });

            $table->editColumn('punto_de_entrada', function ($row) {
                return $row->punto_de_entrada ? $row->punto_de_entrada : '';
            });
            $table->editColumn('num_contenedor', function ($row) {
                return $row->num_contenedor ? $row->num_contenedor : '';
            });
            $table->editColumn('ventilacion', function ($row) {
                return $row->ventilacion ? $row->ventilacion : '';
            });
            $table->editColumn('tara_contenedor', function ($row) {
                return $row->tara_contenedor ? $row->tara_contenedor : '';
            });
            $table->editColumn('quest', function ($row) {
                return $row->quest ? $row->quest : '';
            });
            $table->editColumn('num_sello', function ($row) {
                return $row->num_sello ? $row->num_sello : '';
            });
            $table->editColumn('temperatura', function ($row) {
                return $row->temperatura ? $row->temperatura : '';
            });
            $table->editColumn('empresa_transportista', function ($row) {
                return $row->empresa_transportista ? $row->empresa_transportista : '';
            });
            $table->addColumn('conductor_nombre', function ($row) {
                return $row->conductor ? $row->conductor->nombre : '';
            });

            $table->editColumn('rut_conductor', function ($row) {
                return $row->rut_conductor ? $row->rut_conductor : '';
            });
            $table->editColumn('ppu', function ($row) {
                return $row->ppu ? $row->ppu : '';
            });
            $table->editColumn('telefono', function ($row) {
                return $row->telefono ? $row->telefono : '';
            });
            $table->addColumn('planta_carga_nombre', function ($row) {
                return $row->planta_carga ? $row->planta_carga->nombre : '';
            });

            $table->editColumn('direccion', function ($row) {
                return $row->direccion ? $row->direccion : '';
            });

            $table->editColumn('hora_carga', function ($row) {
                return $row->hora_carga ? $row->hora_carga : '';
            });
            $table->editColumn('guia_despacho_dirigida', function ($row) {
                return $row->guia_despacho_dirigida ? $row->guia_despacho_dirigida : '';
            });
            $table->editColumn('planilla_sag_dirigida', function ($row) {
                return $row->planilla_sag_dirigida ? $row->planilla_sag_dirigida : '';
            });
            $table->editColumn('num_po', function ($row) {
                return $row->num_po ? $row->num_po : '';
            });
            $table->addColumn('emision_de_bl_nombre', function ($row) {
                return $row->emision_de_bl ? $row->emision_de_bl->nombre : '';
            });

            $table->addColumn('tipo_de_flete_nombre', function ($row) {
                return $row->tipo_de_flete ? $row->tipo_de_flete->nombre : '';
            });

            $table->addColumn('clausula_de_venta_nombre', function ($row) {
                return $row->clausula_de_venta ? $row->clausula_de_venta->nombre : '';
            });

            $table->addColumn('moneda_nombre', function ($row) {
                return $row->moneda ? $row->moneda->nombre : '';
            });

            $table->addColumn('forma_de_pago_nombre', function ($row) {
                return $row->forma_de_pago ? $row->forma_de_pago->nombre : '';
            });

            $table->addColumn('modalidad_de_venta_nombre', function ($row) {
                return $row->modalidad_de_venta ? $row->modalidad_de_venta->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta']);

            return $table->make(true);
        }

        $embarcadors     = Embarcador::get();
        $agente_aduanas  = AgenteAduana::get();
        $base_recibidors = BaseRecibidor::get();
        $navieras        = Naviera::get();
        $puerto_correos  = PuertoCorreo::get();
        $puertos         = Puerto::get();
        $chofers         = Chofer::get();
        $planta_cargas   = PlantaCarga::get();
        $emision_bls     = EmisionBl::get();
        $tipofletes      = Tipoflete::get();
        $clausula_venta  = ClausulaVentum::get();
        $monedas         = Moneda::get();
        $forma_pagos     = FormaPago::get();
        $mod_venta       = ModVentum::get();

        return view('admin.instructivoEmbarques.index', compact('embarcadors', 'agente_aduanas', 'base_recibidors', 'navieras', 'puerto_correos', 'puertos', 'chofers', 'planta_cargas', 'emision_bls', 'tipofletes', 'clausula_venta', 'monedas', 'forma_pagos', 'mod_venta'));
    }

    public function create()
    {
        abort_if(Gate::denies('instructivo_embarque_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarcadors = Embarcador::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agente_aduanas = AgenteAduana::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $consignees = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $navieras = Naviera::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_embarques = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_destinos = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_descargas = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $conductors = Chofer::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $planta_cargas = PlantaCarga::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $emision_de_bls = EmisionBl::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_de_fletes = Tipoflete::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clausula_de_ventas = ClausulaVentum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $monedas = Moneda::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $forma_de_pagos = FormaPago::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $modalidad_de_ventas = ModVentum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        $pais_embarque=Country::pluck('name','id')->prepend(trans('global.pleaseSelect'), '');
        $pais_destino=Country::pluck('name','id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.instructivoEmbarques.create', compact('agente_aduanas', 'clausula_de_ventas', 'conductors', 'consignees', 'embarcadors', 'emision_de_bls', 'forma_de_pagos', 'modalidad_de_ventas', 'monedas', 'navieras', 'planta_cargas', 'puerto_descargas', 'puerto_destinos', 'puerto_embarques', 'tipo_de_fletes','pais_embarque','pais_destino'));
    }

    public function store(StoreInstructivoEmbarqueRequest $request)
    {
        $instructivoEmbarque = InstructivoEmbarque::create($request->all());

        return redirect()->route('admin.instructivo-embarques.index');
    }

    public function edit(InstructivoEmbarque $instructivoEmbarque)
    {
        abort_if(Gate::denies('instructivo_embarque_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $embarcadors = Embarcador::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agente_aduanas = AgenteAduana::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $consignees = BaseRecibidor::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $navieras = Naviera::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_embarques = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_destinos = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $puerto_descargas = Puerto::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $conductors = Chofer::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $planta_cargas = PlantaCarga::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $emision_de_bls = EmisionBl::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_de_fletes = Tipoflete::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clausula_de_ventas = ClausulaVentum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $monedas = Moneda::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $forma_de_pagos = FormaPago::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $modalidad_de_ventas = ModVentum::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $instructivoEmbarque->load('embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta');

        $pais_embarque=Country::pluck('name','id')->prepend(trans('global.pleaseSelect'), '');
        $pais_destino=Country::pluck('name','id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.instructivoEmbarques.edit', compact('agente_aduanas', 'clausula_de_ventas', 'conductors', 'consignees', 'embarcadors', 'emision_de_bls', 'forma_de_pagos', 'instructivoEmbarque', 'modalidad_de_ventas', 'monedas', 'navieras', 'planta_cargas', 'puerto_descargas', 'puerto_destinos', 'puerto_embarques', 'tipo_de_fletes', 'pais_embarque', 'pais_destino'));
    }

    public function update(UpdateInstructivoEmbarqueRequest $request, InstructivoEmbarque $instructivoEmbarque)
    {
        $instructivoEmbarque->update($request->all());

        return redirect()->route('admin.instructivo-embarques.index');
    }

    public function show(InstructivoEmbarque $instructivoEmbarque)
    {
        abort_if(Gate::denies('instructivo_embarque_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $instructivoEmbarque->load('embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta');

        return view('admin.instructivoEmbarques.show', compact('instructivoEmbarque'));
    }

    public function destroy(InstructivoEmbarque $instructivoEmbarque)
    {
        abort_if(Gate::denies('instructivo_embarque_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $instructivoEmbarque->delete();

        return back();
    }

    public function massDestroy(MassDestroyInstructivoEmbarqueRequest $request)
    {
        $instructivoEmbarques = InstructivoEmbarque::find(request('ids'));

        foreach ($instructivoEmbarques as $instructivoEmbarque) {
            $instructivoEmbarque->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function getchoferbyid($id){
        $chofer=Chofer::find($id)->first();
        return response()->json($chofer);
    }
    public function getembarcadorbyid($id){
        $embarcador=Embarcador::find($id)->first();
        return response()->json($embarcador);
    }
    public function getagente_aduana($id){
        $agente_aduana=AgenteAduana::find($id)->first();
        return response()->json($agente_aduana);
    }
    public function getconsignee($id){
        $consignee=BaseRecibidor::find($id)->first();
        return response()->json($consignee);
    }
    public function getnaviera($id){
        $naviera=Naviera::find($id)->first();
        return response()->json($naviera);
    }
    public function getpuertocorreobyid($id){
        $puerto=PuertoCorreo::where('puerto_embarque_id','=',$id)->first();
        return response()->json($puerto);
    }
    public function getplantacarga($id){
        $planta_carga=PlantaCarga::find($id)->first();
        return response()->json($planta_carga);
    }
    public function download($id)
    {
        $templatePath = storage_path('app/templates/instmaritimo.xlsx');

        // Load the template
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        $instructivoEmbarque = InstructivoEmbarque::findOrFail($id)->first();

        $embarcador=Embarcador::where('id',$instructivoEmbarque->embarcador_id)->first();
        $agente_aduana=AgenteAduana::where('id',$instructivoEmbarque->agente_aduana_id)->first();
        $consignee=BaseRecibidor::where('id',$instructivoEmbarque->consignee_id)->first();
        $cliente=ClientesComex::where('id',$consignee->cliente_id)->first();
        $naviera=Naviera::where('id',$instructivoEmbarque->naviera_id)->first();
        $baseContacto=BaseContacto::where('codigo','=',$consignee->codigo)->get();
        $chofer=Chofer::where('id',$instructivoEmbarque->conductor_id)->first();
        $planta_carga=PlantaCarga::where('id',$instructivoEmbarque->planta_carga_id)->first();
        $emision_de_bl=EmisionBl::where('id',$instructivoEmbarque->emision_de_bl_id)->first();
        $tipo_de_flete=Tipoflete::where('id',$instructivoEmbarque->tipo_de_flete_id)->first();
        $clausula_de_venta=ClausulaVentum::where('id',$instructivoEmbarque->clausula_de_venta_id)->first();
        $moneda=Moneda::where('id',$instructivoEmbarque->moneda_id)->first();
        $forma_de_pago=FormaPago::where('id',$instructivoEmbarque->forma_de_pago_id)->first();
        $modalidad_de_venta=ModVentum::where('id',$instructivoEmbarque->modalidad_de_venta_id)->first();
        $clausula_venta=ClausulaVentum::where('id',$instructivoEmbarque->clausula_de_venta_id)->first();
        $puerto_embarque=Puerto::where('id',$instructivoEmbarque->puerto_embarque_id)->first();
        $puerto_destino=Puerto::where('id',$instructivoEmbarque->puerto_destino_id)->first();
        $puerto_descarga=Puerto::where('id',$instructivoEmbarque->puerto_descarga_id)->first();
        $puerto_correo=PuertoCorreo::where('puerto_embarque_id',$instructivoEmbarque->puerto_embarque_id)->first();
        $country_embarque=Country::where('id',$instructivoEmbarque->pais_embarque_id)->first();
        $country_destino=Country::where('id',$instructivoEmbarque->pais_destino_id)->first();
        //dd($puerto_embarque,$puerto_destino,$country_embarque,$country_destino,$consignee,$cliente,$baseContacto);
        // Sample data (replace with dynamic data from your database)
        $data = [
            'exportador' => [
                'nombre' => 'Greenex SPA',
                'rut' => '76.425.593-3',
                'direccion' => 'Avenida Ohiggins 740, Codegua',
                'contacto' => 'Andre Courtin Arevalo',
                'telefono' => '56 9 3250 5301',
                'email' => 'andre.courtin@greenex.cl',
            ],
            'embarque' => [
                'numero' => $instructivoEmbarque->instructivo,
                'fecha' => $instructivoEmbarque->fecha, // Convert Excel date 45761.55029872685
            ],
            'embarcador' => [
                'nombre' => $embarcador->nombre,
                'rut' => $embarcador->rut,
                'direccion' => '',
                'contacto' => $embarcador->attn,
                'telefono' => $embarcador->telefono,
                'email' => $embarcador->email,
            ],
            'agente_aduana' => [
                'nombre' => $agente_aduana->consignee,
                'rut' => $agente_aduana->rut,
                'direccion' => $agente_aduana->direccion,
                'codigo' => $agente_aduana->codigo,
                'telefono' => $agente_aduana->telefono,
                'email' => $agente_aduana->email,
            ],
            'consignee' => [
                'nombre' => $baseContacto[0]->consignee,
                'id' => $baseContacto[0]->rut_recibidor,
                'direccion' => $baseContacto[0]->direccion,
                'contacto' => $baseContacto[0]->contacto,
                'telefono' => $baseContacto[0]->telefono,
                'email' => $baseContacto[0]->email,
            ],
            'notify' => [
                'nombre' => $baseContacto[1]->notify,
                'id' => $baseContacto[1]->rut_recibidor,
                'direccion' => $baseContacto[1]->direccion,
                'contacto' => $baseContacto[1]->contacto,
                'telefono' => $baseContacto[1]->telefono,
                'email' => $baseContacto[1]->email,
            ],
            'detalle_embarque' => [
                'naviera' => $naviera->nombre,
                'num_contenedor' => $instructivoEmbarque->num_contenedor,
                'ventilacion' => $instructivoEmbarque->ventilacion,
                'num_booking' => $instructivoEmbarque->num_booking,
                'tara_contenedor' => $instructivoEmbarque->tara_contenedor,
                'quest' => $instructivoEmbarque->quest,
                'nave' => $instructivoEmbarque->nave,
                'num_sello' => $instructivoEmbarque->num_sello,
                'temperatura' => $instructivoEmbarque->temperatura,
                'cut_off' => $instructivoEmbarque->cut_off,
                'empresa_transportista' => $instructivoEmbarque->empresa_transportista,
                'stacking' => $instructivoEmbarque->stacking_ini."-".$instructivoEmbarque->stacking_end,
                'conductor' => $chofer->nombre,
                'rut_conductor' => $chofer->rut,
                'etd' => $instructivoEmbarque->etd,
                'ppu' => $instructivoEmbarque->ppu,
                'telefono' => $instructivoEmbarque->telefono,
                'eta' => $instructivoEmbarque->eta,
                'planta_carga' => $planta_carga->nombre,
                'puerto_embarque' => $puerto_embarque->nombre,
                'pais_embarque' => $country_embarque->name,
                'direccion_carga' => $planta_carga->direccion,
                'puerto_destino' => $puerto_destino->nombre,
                'pais_destino' => $country_destino->name,
                'fecha_carga' => $instructivoEmbarque->fecha_carga, // Convert Excel date 45757
                'hora_carga' => $instructivoEmbarque->hora_carga,
                'puerto_descarga' => $puerto_descarga->nombre,
                'guia_despacho' => $embarcador->g_dir_a,
                'punto_entrada' => $instructivoEmbarque->punto_entrada,
                'planilla_sag' => $embarcador->p_sag_dir,
            ],
            'comerciales' => [
                'num_po' => $instructivoEmbarque->num_po,
                'moneda' => $moneda->codigo,
                'emision_bl' => $emision_de_bl->nombre,
                'forma_pago' => $forma_de_pago->nombre,
                'tipo_flete' => $tipo_de_flete->nombre,
                'modalidad_venta' => $modalidad_de_venta->nombre,
                'clausula_venta' => $clausula_de_venta->nombre,
            ],
            'carga' => [
                [
                    'especie' => 'Pears',
                    'variedad' => '',
                    'calibres' => '',
                    'cajas' => 3680,
                    'etiqueta' => 'Diamond Cherries',
                    'pallet' => 20,
                    'categoria' => 'Cat 1',
                    'envase' => 'Caja Master 5 Kg',
                    'peso_neto' => 5,
                    'peso_bruto' => 6,
                    'total_neto' => 18400,
                    'total_bruto' => 22080,
                ],
            ],
            'carga_totals' => [
                'total_cajas' => 3680,
                'total_neto' => 18400,
                'total_peso_pallet' => 400,
                'cantidad_pallet' => 20,
                'total_bruto' => 22080,
                'total_peso_carga' => 22480,
            ],
            'observaciones' => [
                // Add any general observations if needed
            ],
            'instrucciones_aduana' => [
                'Por favor no indique “N/M” en el BL, para evitar cualquier modificación del documento reemplaze mencionando el numero de contenedor.',
                'Por favor agregar leyenda en B/L: "NON FROZEN FOOD"',
                'Por cada contenedor emita un BL, Certificado Fitosanitario, Certificado de Origen y Certificado de Calibración si se está cargando más de un contenedor.',
                'Incluir la frase “ENTREGA DIRECTA” en el BL - casilla del consignatario',
                'Certificado de Origen: Los item indicados en la factura deben ser iguales a los indicados en Certificado de Origen. Desglozar por pesos netos y nombres correctos de etiquetas en caso de que existan varios.',
                'Fitosanitario: En columna 10: Indicar numero de contenedor // Declaración adicional: " This consignment is in compliance with requirements described in the Protocol of Phytosanitary requirements for export of Cherry from Chile to China and is free from the quarantine pests of concern to China " // Por favor, datos deben cargarse en la web antes de llegar la carga a destino.',
            ],
            'instrucciones_frigorifico' => [
                'Favor enviar despacho vía email a los siguientes correos:',

                'CC: comex@greenex.cl; carol.padilla@greenex.cl; exportaciones@greenex.cl; docs@greenex.cl; andre.courtin@greenex.cl; hhoffmann@greenex.cl',
            ],
        ];


     // Map data to specific cells (adjust based on your template's layout)
        // Exportador
        $sheet->setCellValue('B1', $data['exportador']['nombre']);
        $sheet->setCellValue('B2', $data['exportador']['rut']);
        $sheet->setCellValue('B3', $data['exportador']['direccion']);
        $sheet->setCellValue('B4', $data['exportador']['contacto'] . ' // Teléfono: ' . $data['exportador']['telefono']);
        $sheet->setCellValue('B5', $data['exportador']['email']);

        // Embarque
        $sheet->setCellValue('M3', $data['embarque']['numero']);
        $sheet->setCellValue('M4', $data['embarque']['fecha']);

        // Embarcador
        $sheet->setCellValue('C9', $data['embarcador']['nombre']);
        $sheet->setCellValue('C10', $data['embarcador']['rut']);
        $sheet->setCellValue('C11', $data['embarcador']['direccion']);
        $sheet->setCellValue('C12', $data['embarcador']['contacto']);
        $sheet->setCellValue('F12', $data['embarcador']['telefono']);
        $sheet->setCellValue('C13', $data['embarcador']['email']);

        // Agente de Aduanas
        $sheet->setCellValue('J9', $data['agente_aduana']['nombre']);
        $sheet->setCellValue('J10', $data['agente_aduana']['rut']);
        $sheet->setCellValue('J11', $data['agente_aduana']['direccion']);
        $sheet->setCellValue('J12', $data['agente_aduana']['codigo']);
        $sheet->setCellValue('M12', $data['agente_aduana']['telefono']);
        $sheet->setCellValue('J13', $data['agente_aduana']['email']);

        // Consignee
        $sheet->setCellValue('C17', $data['consignee']['nombre']);
        $sheet->setCellValue('C18', $data['consignee']['id']);
        $sheet->setCellValue('C19', $data['consignee']['direccion']);
        $sheet->setCellValue('C20', $data['consignee']['contacto']);
        $sheet->setCellValue('C21', $data['consignee']['telefono']);
        $sheet->setCellValue('C22', $data['consignee']['email']);

        // Notify
        $sheet->setCellValue('J17', $data['notify']['nombre']);
        $sheet->setCellValue('J18', $data['notify']['id']);
        $sheet->setCellValue('J19', $data['notify']['direccion']);
        $sheet->setCellValue('J20', $data['notify']['contacto']);
        $sheet->setCellValue('J21', $data['notify']['telefono']);
        $sheet->setCellValue('J22', $data['notify']['email']);

        // Detalle de Embarque
        $sheet->setCellValue('C26', $data['detalle_embarque']['naviera']);
        $sheet->setCellValue('J26', $data['detalle_embarque']['num_contenedor']);
        $sheet->setCellValue('M26', $data['detalle_embarque']['ventilacion']);
        $sheet->setCellValue('C27', $data['detalle_embarque']['num_booking']);
        $sheet->setCellValue('J27', $data['detalle_embarque']['tara_contenedor']);
        $sheet->setCellValue('M27', $data['detalle_embarque']['quest']);
        $sheet->setCellValue('C28', $data['detalle_embarque']['nave']);
        $sheet->setCellValue('J28', $data['detalle_embarque']['num_sello']);
        $sheet->setCellValue('M28', $data['detalle_embarque']['temperatura']);
        $sheet->setCellValue('C29', $data['detalle_embarque']['cut_off']);
        $sheet->setCellValue('J29', $data['detalle_embarque']['empresa_transportista']);
        $sheet->setCellValue('C30', $data['detalle_embarque']['stacking']);
        $sheet->setCellValue('J30', $data['detalle_embarque']['conductor']);
        $sheet->setCellValue('M30', $data['detalle_embarque']['rut_conductor']);
        $sheet->setCellValue('C31', $data['detalle_embarque']['etd']);
        $sheet->setCellValue('J31', $data['detalle_embarque']['ppu']);
        $sheet->setCellValue('M31', $data['detalle_embarque']['telefono']);
        $sheet->setCellValue('C32', $data['detalle_embarque']['eta']);
        $sheet->setCellValue('J32', $data['detalle_embarque']['planta_carga']);
        $sheet->setCellValue('C33', $data['detalle_embarque']['puerto_embarque']);
        $sheet->setCellValue('F33', $data['detalle_embarque']['pais_embarque']);
        $sheet->setCellValue('J33', $data['detalle_embarque']['direccion_carga']);
        $sheet->setCellValue('C34', $data['detalle_embarque']['puerto_destino']);
        $sheet->setCellValue('F34', $data['detalle_embarque']['pais_destino']);
        $sheet->setCellValue('J34', $data['detalle_embarque']['fecha_carga']);
        $sheet->setCellValue('M34', $data['detalle_embarque']['hora_carga']);
        $sheet->setCellValue('C35', $data['detalle_embarque']['puerto_descarga']);
        $sheet->setCellValue('J35', $data['detalle_embarque']['guia_despacho']);
        $sheet->setCellValue('C36', $data['detalle_embarque']['punto_entrada']);
        $sheet->setCellValue('J36', $data['detalle_embarque']['planilla_sag']);

        // Antecedentes Comerciales
        $sheet->setCellValue('C40', $data['comerciales']['num_po']);
        $sheet->setCellValue('F40', $data['comerciales']['moneda']);
        $sheet->setCellValue('J40', $data['comerciales']['emision_bl']);
        $sheet->setCellValue('C41', $data['comerciales']['forma_pago']);
        $sheet->setCellValue('J41', $data['comerciales']['tipo_flete']);
        $sheet->setCellValue('C42', $data['comerciales']['modalidad_venta']);
        $sheet->setCellValue('J42', $data['comerciales']['clausula_venta']);

        // Detalle de la Carga (assuming cargo starts at row 43)
        // $row = 43;
        // foreach ($data['carga'] as $item) {
        //     $sheet->setCellValue("A{$row}", $item['especie']);
        //     $sheet->setCellValue("B{$row}", $item['variedad']);
        //     $sheet->setCellValue("C{$row}", $item['calibres']);
        //     $sheet->setCellValue("D{$row}", $item['cajas']);
        //     $sheet->setCellValue("E{$row}", $item['etiqueta']);
        //     $sheet->setCellValue("F{$row}", $item['pallet']);
        //     $sheet->setCellValue("G{$row}", $item['categoria']);
        //     $sheet->setCellValue("H{$row}", $item['envase']);
        //     $sheet->setCellValue("I{$row}", $item['peso_neto']);
        //     $sheet->setCellValue("J{$row}", $item['peso_bruto']);
        //     $sheet->setCellValue("K{$row}", $item['total_neto']);
        //     $sheet->setCellValue("L{$row}", $item['total_bruto']);
        //     $row++;
        // }

        // // Carga Totals (assuming totals at row 46)
        // $sheet->setCellValue('B46', $data['carga_totals']['total_cajas']);
        // $sheet->setCellValue('E46', $data['carga_totals']['total_neto']);
        // $sheet->setCellValue('I46', $data['carga_totals']['total_peso_pallet']);
        // $sheet->setCellValue('B47', $data['carga_totals']['cantidad_pallet']);
        // $sheet->setCellValue('E47', $data['carga_totals']['total_bruto']);
        // $sheet->setCellValue('I47', $data['carga_totals']['total_peso_carga']);

        $sheet->setCellValue('B69',$puerto_correo->emails);


        // // Observaciones (assuming starts at row 49)
        // $row = 57;
        // foreach ($data['observaciones'] as $obs) {
        //     $sheet->setCellValue("A{$row}", $obs);
        //     $row++;
        // }

        // // Instrucciones Agencia de Aduanas (assuming starts at row 51)
        // $row = 51;
        // foreach ($data['instrucciones_aduana'] as $inst) {
        //     $sheet->setCellValue("A{$row}", $inst);
        //     $row++;
        // }

        // // Instrucciones Frigorifico (assuming starts at row 58)
        // $row = 58;
        // foreach ($data['instrucciones_frigorifico'] as $inst) {
        //     $sheet->setCellValue("A{$row}", $inst);
        //     $row++;
        // }

        // Generate the file
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $tempFile = tempnam(sys_get_temp_dir(), 'instructivo');
        $writer->save($tempFile);

        // Return the file as a download response
        return response()->download($tempFile, 'INSTMaritimo-'.$instructivoEmbarque->instructivo.'.xlsx')->deleteFileAfterSend(true);
    }

    public function sendEmailWithExcel(Request $request, InstructivoEmbarque $instructivoEmbarque)
    {
        try {
            $templatePath = storage_path('app/templates/instmaritimo.xlsx');
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Re-fetch relations for the email context if needed, or pass them from the show method if available
            $instructivoEmbarque->load('embarcador', 'agente_aduana', 'consignee', 'naviera', 'puerto_embarque', 'puerto_destino', 'puerto_descarga', 'conductor', 'planta_carga', 'emision_de_bl', 'tipo_de_flete', 'clausula_de_venta', 'moneda', 'forma_de_pago', 'modalidad_de_venta', 'pais_embarque', 'pais_destino');

            $embarcador=Embarcador::where('id',$instructivoEmbarque->embarcador_id)->first();
            $agente_aduana=AgenteAduana::where('id',$instructivoEmbarque->agente_aduana_id)->first();
            $consignee=BaseRecibidor::where('id',$instructivoEmbarque->consignee_id)->first();
            $cliente=ClientesComex::where('id',$consignee->cliente_id)->first();
            $naviera=Naviera::where('id',$instructivoEmbarque->naviera_id)->first();
            $baseContacto=BaseContacto::where('codigo','=',$consignee->codigo)->get();
            $chofer=Chofer::where('id',$instructivoEmbarque->conductor_id)->first();
            $planta_carga=PlantaCarga::where('id',$instructivoEmbarque->planta_carga_id)->first();
            $emision_de_bl=EmisionBl::where('id',$instructivoEmbarque->emision_de_bl_id)->first();
            $tipo_de_flete=Tipoflete::where('id',$instructivoEmbarque->tipo_de_flete_id)->first();
            $clausula_de_venta=ClausulaVentum::where('id',$instructivoEmbarque->clausula_de_venta_id)->first();
            $moneda=Moneda::where('id',$instructivoEmbarque->moneda_id)->first();
            $forma_de_pago=FormaPago::where('id',$instructivoEmbarque->forma_de_pago_id)->first();
            $modalidad_de_venta=ModVentum::where('id',$instructivoEmbarque->modalidad_de_venta_id)->first();
            $clausula_venta=ClausulaVentum::where('id',$instructivoEmbarque->clausula_de_venta_id)->first();
            $puerto_embarque=Puerto::where('id',$instructivoEmbarque->puerto_embarque_id)->first();
            $puerto_destino=Puerto::where('id',$instructivoEmbarque->puerto_destino_id)->first();
            $puerto_descarga=Puerto::where('id',$instructivoEmbarque->puerto_descarga_id)->first();
            $puerto_correo=PuertoCorreo::where('puerto_embarque_id',$instructivoEmbarque->puerto_embarque_id)->first();
            $country_embarque=Country::where('id',$instructivoEmbarque->pais_embarque_id)->first();
            $country_destino=Country::where('id',$instructivoEmbarque->pais_destino_id)->first();

            $data = [
                'exportador' => [
                    'nombre' => 'Greenex SPA',
                    'rut' => '76.425.593-3',
                    'direccion' => 'Avenida Ohiggins 740, Codegua',
                    'contacto' => 'Andre Courtin Arevalo',
                    'telefono' => '56 9 3250 5301',
                    'email' => 'andre.courtin@greenex.cl',
                ],
                'embarque' => [
                    'numero' => $instructivoEmbarque->instructivo,
                    'fecha' => $instructivoEmbarque->fecha,
                ],
                'embarcador' => [
                    'nombre' => $embarcador->nombre,
                    'rut' => $embarcador->rut,
                    'direccion' => '',
                    'contacto' => $embarcador->attn,
                    'telefono' => $embarcador->telefono,
                    'email' => $embarcador->email,
                ],
                'agente_aduana' => [
                    'nombre' => $agente_aduana->consignee,
                    'rut' => $agente_aduana->rut,
                    'direccion' => $agente_aduana->direccion,
                    'codigo' => $agente_aduana->codigo,
                    'telefono' => $agente_aduana->telefono,
                    'email' => $agente_aduana->email,
                ],
                'consignee' => [
                    'nombre' => $baseContacto[0]->consignee,
                    'id' => $baseContacto[0]->rut_recibidor,
                    'direccion' => $baseContacto[0]->direccion,
                    'contacto' => $baseContacto[0]->contacto,
                    'telefono' => $baseContacto[0]->telefono,
                    'email' => $baseContacto[0]->email,
                ],
                'notify' => [
                    'nombre' => $baseContacto[1]->notify,
                    'id' => $baseContacto[1]->rut_recibidor,
                    'direccion' => $baseContacto[1]->direccion,
                    'contacto' => $baseContacto[1]->contacto,
                    'telefono' => $baseContacto[1]->telefono,
                    'email' => $baseContacto[1]->email,
                ],
                'detalle_embarque' => [
                    'naviera' => $naviera->nombre,
                    'num_contenedor' => $instructivoEmbarque->num_contenedor,
                    'ventilacion' => $instructivoEmbarque->ventilacion,
                    'num_booking' => $instructivoEmbarque->num_booking,
                    'tara_contenedor' => $instructivoEmbarque->tara_contenedor,
                    'quest' => $instructivoEmbarque->quest,
                    'nave' => $instructivoEmbarque->nave,
                    'num_sello' => $instructivoEmbarque->num_sello,
                    'temperatura' => $instructivoEmbarque->temperatura,
                    'cut_off' => $instructivoEmbarque->cut_off,
                    'empresa_transportista' => $instructivoEmbarque->empresa_transportista,
                    'stacking' => $instructivoEmbarque->stacking_ini."-".$instructivoEmbarque->stacking_end,
                    'conductor' => $chofer->nombre,
                    'rut_conductor' => $chofer->rut,
                    'etd' => $instructivoEmbarque->etd,
                    'ppu' => $instructivoEmbarque->ppu,
                    'telefono' => $instructivoEmbarque->telefono,
                    'eta' => $instructivoEmbarque->eta,
                    'planta_carga' => $planta_carga->nombre,
                    'puerto_embarque' => $puerto_embarque->nombre,
                    'pais_embarque' => $country_embarque->name,
                    'direccion_carga' => $planta_carga->direccion,
                    'puerto_destino' => $puerto_destino->nombre,
                    'pais_destino' => $country_destino->name,
                    'fecha_carga' => $instructivoEmbarque->fecha_carga,
                    'hora_carga' => $instructivoEmbarque->hora_carga,
                    'puerto_descarga' => $puerto_descarga->nombre,
                    'guia_despacho' => $embarcador->g_dir_a,
                    'punto_entrada' => $instructivoEmbarque->punto_entrada,
                    'planilla_sag' => $embarcador->p_sag_dir,
                ],
                'comerciales' => [
                    'num_po' => $instructivoEmbarque->num_po,
                    'moneda' => $moneda->codigo,
                    'emision_bl' => $emision_de_bl->nombre,
                    'forma_pago' => $forma_de_pago->nombre,
                    'tipo_flete' => $tipo_de_flete->nombre,
                    'modalidad_venta' => $modalidad_de_venta->nombre,
                    'clausula_venta' => $clausula_de_venta->nombre,
                ],
                'carga' => [
                    [
                        'especie' => 'Pears',
                        'variedad' => '',
                        'calibres' => '',
                        'cajas' => 3680,
                        'etiqueta' => 'Diamond Cherries',
                        'pallet' => 20,
                        'categoria' => 'Cat 1',
                        'envase' => 'Caja Master 5 Kg',
                        'peso_neto' => 5,
                        'peso_bruto' => 6,
                        'total_neto' => 18400,
                        'total_bruto' => 22080,
                    ],
                ],
                'carga_totals' => [
                    'total_cajas' => 3680,
                    'total_neto' => 18400,
                    'total_peso_pallet' => 400,
                    'cantidad_pallet' => 20,
                    'total_bruto' => 22080,
                    'total_peso_carga' => 22480,
                ],
                'observaciones' => [
                    // Add any general observations if needed
                ],
                'instrucciones_aduana' => [
                    'Por favor no indique “N/M” en el BL, para evitar cualquier modificación del documento reemplaze mencionando el numero de contenedor.',
                    'Por favor agregar leyenda en B/L: "NON FROZEN FOOD"',
                    'Por cada contenedor emita un BL, Certificado Fitosanitario, Certificado de Origen y Certificado de Calibración si se está cargando más de un contenedor.',
                    'Incluir la frase “ENTREGA DIRECTA” en el BL - casilla del consignatario',
                    'Certificado de Origen: Los item indicados en la factura deben ser iguales a los indicados en Certificado de Origen. Desglozar por pesos netos y nombres correctos de etiquetas en caso de que existan varios.',
                    'Fitosanitario: En columna 10: Indicar numero de contenedor // Declaración adicional: " This consignment is in compliance with requirements described in the Protocol of Phytosanitary requirements for export of Cherry from Chile to China and is free from the quarantine pests of concern to China " // Por favor, datos deben cargarse en la web antes de llegar la carga a destino.',
                ],
                'instrucciones_frigorifico' => [
                    'Favor enviar despacho vía email a los siguientes correos:',

                    'CC: comex@greenex.cl; carol.padilla@greenex.cl; exportaciones@greenex.cl; docs@greenex.cl; andre.courtin@greenex.cl; hhoffmann@greenex.cl',
                ],
            ];

            // Map data to specific cells (adjust based on your template's layout)
            // Exportador
            $sheet->setCellValue('B1', $data['exportador']['nombre']);
            $sheet->setCellValue('B2', $data['exportador']['rut']);
            $sheet->setCellValue('B3', $data['exportador']['direccion']);
            $sheet->setCellValue('B4', $data['exportador']['contacto'] . ' // Teléfono: ' . $data['exportador']['telefono']);
            $sheet->setCellValue('B5', $data['exportador']['email']);

            // Embarque
            $sheet->setCellValue('M3', $data['embarque']['numero']);
            $sheet->setCellValue('M4', $data['embarque']['fecha']);

            // Embarcador
            $sheet->setCellValue('C9', $data['embarcador']['nombre']);
            $sheet->setCellValue('C10', $data['embarcador']['rut']);
            $sheet->setCellValue('C11', $data['embarcador']['direccion']);
            $sheet->setCellValue('C12', $data['embarcador']['contacto']);
            $sheet->setCellValue('F12', $data['embarcador']['telefono']);
            $sheet->setCellValue('C13', $data['embarcador']['email']);

            // Agente de Aduanas
            $sheet->setCellValue('J9', $data['agente_aduana']['nombre']);
            $sheet->setCellValue('J10', $data['agente_aduana']['rut']);
            $sheet->setCellValue('J11', $data['agente_aduana']['direccion']);
            $sheet->setCellValue('J12', $data['agente_aduana']['codigo']);
            $sheet->setCellValue('M12', $data['agente_aduana']['telefono']);
            $sheet->setCellValue('J13', $data['agente_aduana']['email']);

            // Consignee
            $sheet->setCellValue('C17', $data['consignee']['nombre']);
            $sheet->setCellValue('C18', $data['consignee']['id']);
            $sheet->setCellValue('C19', $data['consignee']['direccion']);
            $sheet->setCellValue('C20', $data['consignee']['contacto']);
            $sheet->setCellValue('C21', $data['consignee']['telefono']);
            $sheet->setCellValue('C22', $data['consignee']['email']);

            // Notify
            $sheet->setCellValue('J17', $data['notify']['nombre']);
            $sheet->setCellValue('J18', $data['notify']['id']);
            $sheet->setCellValue('J19', $data['notify']['direccion']);
            $sheet->setCellValue('J20', $data['notify']['contacto']);
            $sheet->setCellValue('J21', $data['notify']['telefono']);
            $sheet->setCellValue('J22', $data['notify']['email']);

            // Detalle de Embarque
            $sheet->setCellValue('C26', $data['detalle_embarque']['naviera']);
            $sheet->setCellValue('J26', $data['detalle_embarque']['num_contenedor']);
            $sheet->setCellValue('M26', $data['detalle_embarque']['ventilacion']);
            $sheet->setCellValue('C27', $data['detalle_embarque']['num_booking']);
            $sheet->setCellValue('J27', $data['detalle_embarque']['tara_contenedor']);
            $sheet->setCellValue('M27', $data['detalle_embarque']['quest']);
            $sheet->setCellValue('C28', $data['detalle_embarque']['nave']);
            $sheet->setCellValue('J28', $data['detalle_embarque']['num_sello']);
            $sheet->setCellValue('M28', $data['detalle_embarque']['temperatura']);
            $sheet->setCellValue('C29', $data['detalle_embarque']['cut_off']);
            $sheet->setCellValue('J29', $data['detalle_embarque']['empresa_transportista']);
            $sheet->setCellValue('C30', $data['detalle_embarque']['stacking']);
            $sheet->setCellValue('J30', $data['detalle_embarque']['conductor']);
            $sheet->setCellValue('M30', $data['detalle_embarque']['rut_conductor']);
            $sheet->setCellValue('C31', $data['detalle_embarque']['etd']);
            $sheet->setCellValue('J31', $data['detalle_embarque']['ppu']);
            $sheet->setCellValue('M31', $data['detalle_embarque']['telefono']);
            $sheet->setCellValue('C32', $data['detalle_embarque']['eta']);
            $sheet->setCellValue('J32', $data['detalle_embarque']['planta_carga']);
            $sheet->setCellValue('C33', $data['detalle_embarque']['puerto_embarque']);
            $sheet->setCellValue('F33', $data['detalle_embarque']['pais_embarque']);
            $sheet->setCellValue('J33', $data['detalle_embarque']['direccion_carga']);
            $sheet->setCellValue('C34', $data['detalle_embarque']['puerto_destino']);
            $sheet->setCellValue('F34', $data['detalle_embarque']['pais_destino']);
            $sheet->setCellValue('J34', $data['detalle_embarque']['fecha_carga']);
            $sheet->setCellValue('M34', $data['detalle_embarque']['hora_carga']);
            $sheet->setCellValue('C35', $data['detalle_embarque']['puerto_descarga']);
            $sheet->setCellValue('J35', $data['detalle_embarque']['guia_despacho']);
            $sheet->setCellValue('C36', $data['detalle_embarque']['punto_entrada']);
            $sheet->setCellValue('J36', $data['detalle_embarque']['planilla_sag']);

            // Antecedentes Comerciales
            $sheet->setCellValue('C40', $data['comerciales']['num_po']);
            $sheet->setCellValue('F40', $data['comerciales']['moneda']);
            $sheet->setCellValue('J40', $data['comerciales']['emision_bl']);
            $sheet->setCellValue('C41', $data['comerciales']['forma_pago']);
            $sheet->setCellValue('J41', $data['comerciales']['tipo_flete']);
            $sheet->setCellValue('C42', $data['comerciales']['modalidad_venta']);
            $sheet->setCellValue('J42', $data['comerciales']['clausula_venta']);

            $sheet->setCellValue('B69',$puerto_correo->emails);

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $tempFile = tempnam(sys_get_temp_dir(), 'instructivo_email');
            $writer->save($tempFile);

            // Replace with actual recipient email(s)
            $recipientEmail = env('EMAIL_INSTRUCTIVO_EMBARQUE'); // TODO: Get actual recipient email

            Mail::to($recipientEmail)->send(new InstructivoEmbarqueMail($instructivoEmbarque, $tempFile));

            unlink($tempFile); // Delete the temporary file

            return response()->json(['message' => 'Correo electrónico enviado con éxito.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al enviar el correo electrónico: ' . $e->getMessage()], 500);
        }
    }
}
