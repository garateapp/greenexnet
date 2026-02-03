<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySolicitudCompraRequest;
use App\Http\Requests\StoreSolicitudCompraRequest;
use App\Http\Requests\UpdateSolicitudCompraRequest;
use App\Mail\SolicitudCompraMail;
use App\Models\AdquisicionEstado;
use App\Models\AuditLog;
use App\Models\CentroCosto;
use App\Models\CotizacionCompra;
use App\Models\Moneda;
use App\Models\PoliticaCotizacion;
use App\Models\SolicitudCompra;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SolicitudComprasController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('solicitud_compra_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SolicitudCompra::with(['solicitante', 'estado', 'moneda', 'centroCosto'])->select(sprintf('%s.*', (new SolicitudCompra)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'solicitud_compra_show';
                $editGate      = 'solicitud_compra_edit';
                $deleteGate    = 'solicitud_compra_delete';
                $crudRoutePart = 'solicitud-compras';

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
            $table->editColumn('titulo', function ($row) {
                return $row->titulo ? $row->titulo : '';
            });
            $table->addColumn('solicitante_name', function ($row) {
                return $row->solicitante ? $row->solicitante->name : '';
            });
            $table->editColumn('monto_estimado', function ($row) {
                return $row->monto_estimado ? number_format($row->monto_estimado, 0, ',', '.') : '';
            });
            $table->editColumn('cotizaciones_requeridas', function ($row) {
                return $row->cotizaciones_requeridas ? $row->cotizaciones_requeridas : '';
            });
            $table->addColumn('estado_nombre', function ($row) {
                return $row->estado ? $row->estado->nombre : '';
            });
            $table->addColumn('centro_costo_nombre', function ($row) {
                if (!$row->centroCosto) {
                    return '';
                }
                $label = trim(($row->centroCosto->c_centrocosto ? $row->centroCosto->c_centrocosto . ' - ' : '') . $row->centroCosto->n_centrocosto);
                return $label;
            });
            $table->addColumn('moneda_nombre', function ($row) {
                return $row->moneda ? $row->moneda->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.solicitudCompras.index');
    }

    public function create()
    {
        abort_if(Gate::denies('solicitud_compra_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $centroCostos = $this->getCentroCostos();

        return view('admin.solicitudCompras.create', compact('centroCostos'));
    }

    public function store(StoreSolicitudCompraRequest $request)
    {
        $policy = PoliticaCotizacion::active()->forAmount($request->monto_estimado)->first();
        $cotizacionesRequeridas = $policy ? $policy->cotizaciones_requeridas : 1;
        $estadoInicialId = AdquisicionEstado::orderBy('orden')->value('id');
        $monedaId = $this->getClpMonedaId();
        $cotizacionesPorAdquisiciones = $this->resolveCotizacionesPorAdquisiciones($request);
        $centroCostoId = $this->isAdquisicionesUser() ? $request->centro_costo_id : null;

        $solicitud = SolicitudCompra::create([
            'solicitante_id' => auth()->id(),
            'adquisicion_estado_id' => $estadoInicialId,
            'centro_costo_id' => $centroCostoId,
            'moneda_id' => $monedaId,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'monto_estimado' => $request->monto_estimado,
            'cotizaciones_requeridas' => $cotizacionesRequeridas,
            'cotizaciones_por_adquisiciones' => $cotizacionesPorAdquisiciones,
            'fecha_requerida' => $request->fecha_requerida,
        ]);

        if ($cotizacionesPorAdquisiciones) {
            $solicitud->load(['solicitante', 'estado', 'moneda']);
            $this->sendCreacionAdquisicionesNotification($solicitud);
        }

        return redirect()->route('admin.solicitud-compras.show', $solicitud);
    }

    public function edit(SolicitudCompra $solicitudCompra)
    {
        abort_if(Gate::denies('solicitud_compra_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $solicitudCompra->load('solicitante', 'estado', 'moneda', 'centroCosto');
        $centroCostos = $this->getCentroCostos();

        return view('admin.solicitudCompras.edit', compact('solicitudCompra', 'centroCostos'));
    }

    public function update(UpdateSolicitudCompraRequest $request, SolicitudCompra $solicitudCompra)
    {
        $policy = PoliticaCotizacion::active()->forAmount($request->monto_estimado)->first();
        $cotizacionesRequeridas = $policy ? $policy->cotizaciones_requeridas : $solicitudCompra->cotizaciones_requeridas;
        $monedaId = $this->getClpMonedaId();
        $cotizacionesPorAdquisiciones = $this->resolveCotizacionesPorAdquisiciones($request);
        $centroCostoId = $this->isAdquisicionesUser() ? $request->centro_costo_id : $solicitudCompra->centro_costo_id;

        $solicitudCompra->update([
            'centro_costo_id' => $centroCostoId,
            'moneda_id' => $monedaId,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'monto_estimado' => $request->monto_estimado,
            'cotizaciones_requeridas' => $cotizacionesRequeridas,
            'cotizaciones_por_adquisiciones' => $cotizacionesPorAdquisiciones,
            'fecha_requerida' => $request->fecha_requerida,
        ]);

        return redirect()->route('admin.solicitud-compras.index');
    }

    public function show(SolicitudCompra $solicitudCompra)
    {
        abort_if(Gate::denies('solicitud_compra_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $solicitudCompra->load(['solicitante', 'estado', 'moneda', 'centroCosto', 'cotizaciones.moneda']);

        return view('admin.solicitudCompras.show', compact('solicitudCompra'));
    }

    public function destroy(SolicitudCompra $solicitudCompra)
    {
        abort_if(Gate::denies('solicitud_compra_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $solicitudCompra->delete();

        return back();
    }

    public function massDestroy(MassDestroySolicitudCompraRequest $request)
    {
        $solicitudes = SolicitudCompra::find(request('ids'));

        foreach ($solicitudes as $solicitud) {
            $solicitud->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function kanban()
    {
        abort_if(Gate::denies('solicitud_compra_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $estados = AdquisicionEstado::orderBy('orden')->get();
        $solicitudes = SolicitudCompra::with(['solicitante', 'estado', 'moneda', 'cotizaciones', 'centroCosto'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('adquisicion_estado_id');
        $centroCostos = $this->getCentroCostos();

        return view('admin.solicitudCompras.kanban', compact('estados', 'solicitudes', 'centroCostos'));
    }

    public function updateEstado(Request $request, SolicitudCompra $solicitudCompra)
    {
        abort_if(Gate::denies('solicitud_compra_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if(!$this->isAdquisicionesUser(), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'estado_id' => [
                'required',
                'integer',
                'exists:adquisicion_estados,id',
            ],
        ]);

        $solicitudCompra->load(['estado', 'solicitante', 'moneda']);
        $estadoAnterior = $solicitudCompra->estado ? $solicitudCompra->estado->nombre : null;

        $nuevoEstado = AdquisicionEstado::findOrFail($validated['estado_id']);
        $minOrdenCotizaciones = AdquisicionEstado::where('slug', 'cotizaciones-completas')->value('orden');

        if ($minOrdenCotizaciones !== null && $nuevoEstado->orden >= $minOrdenCotizaciones) {
            $cotizacionesActuales = $solicitudCompra->cotizaciones()->count();
            if ($cotizacionesActuales < $solicitudCompra->cotizaciones_requeridas) {
                return response()->json([
                    'message' => 'Faltan cotizaciones para continuar.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $solicitudCompra->update([
            'adquisicion_estado_id' => $nuevoEstado->id,
        ]);

        $solicitudCompra->refresh()->load(['estado', 'solicitante', 'moneda']);
        $estadoNuevo = $solicitudCompra->estado ? $solicitudCompra->estado->nombre : null;
        $this->logEstadoCambio($solicitudCompra, $estadoAnterior, $estadoNuevo);

        $notificacionEmail = config('panel.adquisiciones_notificacion_email');
        $destinatarios = collect([
            optional($solicitudCompra->solicitante)->email,
            $notificacionEmail,
        ])->filter()->unique()->values()->all();

        if (!empty($destinatarios)) {
            Mail::to($destinatarios)->send(new SolicitudCompraMail($solicitudCompra, 'status', $estadoAnterior, $estadoNuevo));
        }

        return response()->json(['status' => 'ok']);
    }

    public function storeCotizacion(Request $request, SolicitudCompra $solicitudCompra)
    {
        abort_if(Gate::denies('solicitud_compra_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $puedeAdquisiciones = (bool) config('panel.adquisiciones_puede_subir_cotizaciones');
        $esSolicitante = $solicitudCompra->solicitante_id === auth()->id();
        $esAdquisiciones = $this->isAdquisicionesUser();
        $cotizacionesPorAdquisiciones = $solicitudCompra->cotizaciones_por_adquisiciones;

        if ($esSolicitante && $cotizacionesPorAdquisiciones) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        if ($esAdquisiciones && (!$puedeAdquisiciones || !$cotizacionesPorAdquisiciones)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        abort_if(!$esSolicitante && !$esAdquisiciones, Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($solicitudCompra->cotizaciones()->count() >= 3) {
            return back()->withErrors(['archivo' => 'Solo se permiten hasta 3 cotizaciones por solicitud.']);
        }

        $validated = $request->validate([
            'proveedor' => [
                'required',
                'string',
                'max:255',
            ],
            'monto' => [
                'required',
                'integer',
                'min:0',
            ],
            'archivo' => [
                'required',
                'file',
                'max:10240',
            ],
            'fecha_recepcion' => [
                'nullable',
                'date_format:' . config('panel.date_format'),
            ],
        ]);

        $monedaId = $this->getClpMonedaId();
        $path = $request->file('archivo')->store('cotizaciones', 'public');

        CotizacionCompra::create([
            'solicitud_compra_id' => $solicitudCompra->id,
            'proveedor' => $validated['proveedor'],
            'monto' => $validated['monto'],
            'moneda_id' => $monedaId,
            'archivo_path' => $path,
            'fecha_recepcion' => $validated['fecha_recepcion'] ?? null,
        ]);

        $solicitudCompra->refresh()->load(['estado', 'solicitante', 'moneda']);
        $cotizacionesActuales = $solicitudCompra->cotizaciones()->count();

        $estadoEnCotizacion = AdquisicionEstado::where('slug', 'en-cotizacion')->first();
        $estadoCompletas = AdquisicionEstado::where('slug', 'cotizaciones-completas')->first();
        $estadoAnterior = $solicitudCompra->estado ? $solicitudCompra->estado->nombre : null;

        if ($cotizacionesActuales === 1
            && $solicitudCompra->cotizaciones_requeridas > 1
            && $estadoEnCotizacion
            && $solicitudCompra->adquisicion_estado_id !== $estadoEnCotizacion->id
        ) {
            $solicitudCompra->update([
                'adquisicion_estado_id' => $estadoEnCotizacion->id,
            ]);
            $solicitudCompra->refresh()->load(['estado', 'solicitante', 'moneda']);
            $this->logEstadoCambio($solicitudCompra, $estadoAnterior, $solicitudCompra->estado ? $solicitudCompra->estado->nombre : null);
            $this->sendEstadoNotification($solicitudCompra, $estadoAnterior, $solicitudCompra->estado ? $solicitudCompra->estado->nombre : null);
            $estadoAnterior = $solicitudCompra->estado ? $solicitudCompra->estado->nombre : $estadoAnterior;
        }

        if ($estadoCompletas
            && $cotizacionesActuales >= $solicitudCompra->cotizaciones_requeridas
            && $solicitudCompra->adquisicion_estado_id !== $estadoCompletas->id
            && $solicitudCompra->estado
            && $solicitudCompra->estado->orden < $estadoCompletas->orden
        ) {
            $solicitudCompra->update([
                'adquisicion_estado_id' => $estadoCompletas->id,
            ]);
            $solicitudCompra->refresh()->load(['estado', 'solicitante', 'moneda']);
            $estadoNuevo = $solicitudCompra->estado ? $solicitudCompra->estado->nombre : null;
            $this->logEstadoCambio($solicitudCompra, $estadoAnterior, $estadoNuevo);
            $adquisicionesEmails = $this->getAdquisicionesEmails();
            $this->sendEstadoNotification($solicitudCompra, $estadoAnterior, $estadoNuevo, $adquisicionesEmails);
        }

        return redirect()->route('admin.solicitud-compras.show', $solicitudCompra);
    }

    protected function resolveCotizacionesPorAdquisiciones(Request $request): bool
    {
        if (!config('panel.adquisiciones_puede_subir_cotizaciones')) {
            return false;
        }

        return (bool) $request->input('cotizaciones_por_adquisiciones', false);
    }

    protected function getClpMonedaId(): ?int
    {
        $monedaId = Moneda::query()
            ->where('codigo', 'CLP')
            ->orWhere('nombre', 'CLP')
            ->value('id');

        abort_if(!$monedaId, Response::HTTP_UNPROCESSABLE_ENTITY, 'Moneda CLP no configurada.');

        return $monedaId;
    }

    protected function isAdquisicionesUser(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return $user->roles->pluck('title')->contains('Adquisiciones');
    }

    protected function getCentroCostos()
    {
        return CentroCosto::query()
            ->with('entidad')
            ->orderBy('n_centrocosto')
            ->get();
    }

    protected function getAdquisicionesEmails(): array
    {
        return User::query()
            ->whereHas('roles', function ($query) {
                $query->where('title', 'Adquisiciones');
            })
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function sendEstadoNotification(SolicitudCompra $solicitudCompra, ?string $estadoAnterior, ?string $estadoNuevo, array $extraDestinatarios = []): void
    {
        $notificacionEmail = config('panel.adquisiciones_notificacion_email');
        $destinatarios = collect([
            optional($solicitudCompra->solicitante)->email,
            $notificacionEmail,
        ])
            ->merge($extraDestinatarios)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($destinatarios)) {
            Mail::to($destinatarios)->send(new SolicitudCompraMail($solicitudCompra, 'status', $estadoAnterior, $estadoNuevo));
        }
    }

    protected function sendCreacionAdquisicionesNotification(SolicitudCompra $solicitudCompra): void
    {
        $notificacionEmail = config('panel.adquisiciones_notificacion_email');
        $destinatarios = collect($this->getAdquisicionesEmails())
            ->merge([$notificacionEmail])
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($destinatarios)) {
            Mail::to($destinatarios)->send(new SolicitudCompraMail($solicitudCompra, 'created'));
        }
    }

    protected function logEstadoCambio(SolicitudCompra $solicitudCompra, ?string $estadoAnterior, ?string $estadoNuevo): void
    {
        AuditLog::create([
            'description'  => 'audit:estado_cambiado',
            'subject_id'   => $solicitudCompra->id,
            'subject_type' => sprintf('%s#%s', SolicitudCompra::class, $solicitudCompra->id),
            'user_id'      => auth()->id(),
            'properties'   => [
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
            ],
            'host'         => request()->ip(),
        ]);
    }
}
