<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Carbon\Carbon;
use App\Models\Cargo;
use App\Models\Estado;
use App\Models\Entidad;
use App\Models\Personal;
use App\Models\Asistencium;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ControlAccessLog;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StorePersonalRequest;
use App\Http\Requests\UpdatePersonalRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPersonalRequest;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Exports\AsistenciaExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Configuracion;
use DB;
use App\Models\TratoContratistas;
use App\Exports\TratoContratistasTemplateExport;
use App\Models\User; // Import the User model

class PersonalController extends Controller
{
    use CsvImportTrait;

    protected array $contractorGroups = [
        'Valsan Ltda' => ['valsan', 'valsán', 'valsan ltda', 'valsan noche'],
        'Las Orquídeas SpA' => ['Las Orquideas SpA','las orquideas', 'las orquídeas','Orquídeas Noche','Las Orquídeas SpA Noche'],
        'Isaias Ballesteros' => ['isaias ballesteros', 'isaias ballesteros noche'],
        'Agrícola Lancair' => ['agrícola lancair', 'lancair noche','Agrícola Lancair Noche'],
        'Fernando Urbina' => ['fernando urbina'],
        'Claudia Viera'=>['claudia viera'],
    ];

    public function index(Request $request)
    {
        abort_if(Gate::denies('personal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Personal::with(['cargo', 'estado', 'entidad', 'user'])->select(sprintf('%s.*', (new Personal)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'personal_show';
                $editGate      = 'personal_edit';
                $deleteGate    = 'personal_delete';
                $crudRoutePart = 'personals';

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
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('rut', function ($row) {
                return $row->rut ? $row->rut : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('telefono', function ($row) {
                return $row->telefono ? $row->telefono : '';
            });
            $table->addColumn('cargo_nombre', function ($row) {
                return $row->cargo ? $row->cargo->nombre : '';
            });

            $table->addColumn('estado_nombre', function ($row) {
                return $row->estado ? $row->estado->nombre : '';
            });

            $table->addColumn('entidad_nombre', function ($row) {
                return $row->entidad ? $row->entidad->nombre : '';
            });
            $table->addColumn('foto', function ($row) {
                return $row->foto ? $row->foto : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'cargo', 'estado', 'entidad', 'user']);

            return $table->make(true);
        }

        return view('admin.personals.index');
    }

    public function importFromAccessLogs()
    {
        abort_if(Gate::denies('personal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logs = ControlAccessLog::select('personal_id', 'nombre', 'departamento')
            ->whereNotNull('personal_id')
            ->whereIn('departamento',['Valsán Noche','Valsán Ltda Noche','Valsán Ltda','Orquídeas Noche',
            'Las Orquídeas SpA Noche','Las Orquideas SpA','Lancair Noche','Isaias Ballesteros Noche','Isaias Ballesteros',
            'Fernando Urbina Noche','Fernando Urbina','Claudia Viera Noche','Claudia Viera','Jenny Padilla','Agricola Lancair Noche','Agrícola Lancair'])
            ->get()
            ->unique('personal_id');

        $created = 0;
        $updated = 0;
        $bypassed = 0;
        foreach ($logs as $log) {
            $rutFormateado = $this->formatearRutConDv($log->personal_id);
            if (!$rutFormateado) {
                continue;
            }

            $departmentName = $log->departamento ?? null;
            $contractorGroup = $this->resolveContractorGroup($departmentName);

            if ($contractorGroup) {
                $departmentName = $contractorGroup;
            }

            $entidadId = $departmentName ? $this->getDeptoId($departmentName) : null;
            $personal = Personal::where('rut', $rutFormateado)->first();
            Log::info([$personal, $entidadId, $rutFormateado,$departmentName]);
            if (!$personal) {
                if ($contractorGroup && $entidadId) {
                    Personal::create([
                        'nombre'     => $log->nombre,
                        'codigo'     => $log->personal_id,
                        'rut'        => $rutFormateado,
                        'entidad_id' => $entidadId,
                        'cargo_id'   => 1,
                    ]);
                    $created++;
                }
                Log::debug("bypassed", $log->personal_id);
                $bypassed++;
                continue;

            }

            if ($contractorGroup && $entidadId && $personal->entidad_id !== $entidadId) {
                $personal->entidad_id = $entidadId;
                $personal->save();
                $updated++;
            }
        }

        return redirect()->route('admin.personals.index')
            ->with('import_personals_message', "Importación completada. Creados {$created}, actualizados {$updated}, omitidos {$bypassed}.");
    }

    public function create()
    {
        abort_if(Gate::denies('personal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cargos = Cargo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.personals.create', compact('cargos', 'entidads', 'estados', 'users'));
    }

    public function store(StorePersonalRequest $request)
    {
        $personal = new Personal();
        $personal->nombre = $request->nombre;
        $personal->codigo = $request->codigo;
        $personal->rut = $request->rut;
        $personal->email = $request->email;
        $personal->telefono = $request->telefono;
        $personal->cargo_id = $request->cargo_id;
        $personal->estado_id = $request->estado_id;
        $personal->entidad_id = $request->entidad_id;
        $personal->user_id = $request->user_id; // Save user_id
        if ($request->foto != null) {
            $base64Image = $request->input('foto');
            $fileData = explode(',', $base64Image);
            $imageData = base64_decode($fileData[1]);
            // Generar un nombre único para el archivo
            $fileName = $request->rut . '.jpg'; // Puedes cambiar la extensión según el tipo de imagen

            // Guardar la imagen en el storage
            $filePath = 'images/' . $fileName;
            Storage::disk('public')->put($filePath, $imageData);

            $personal->foto = $filePath;
        }


        $personal->save();

        return redirect()->route('admin.personals.index');
    }

    public function edit(Personal $personal)
    {
        abort_if(Gate::denies('personal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cargos = Cargo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $personal->load('cargo', 'estado', 'entidad', 'user');

        return view('admin.personals.edit', compact('cargos', 'entidads', 'estados', 'personal', 'users'));
    }

    public function update(UpdatePersonalRequest $request, Personal $personal)
    {

        $nPersonal = Personal::find($personal->id);
        $nPersonal->nombre = $request->nombre;
        $nPersonal->codigo = $request->codigo;
        $nPersonal->rut = $request->rut;
        $nPersonal->email = $request->email;
        $nPersonal->telefono = $request->telefono;
        $nPersonal->cargo_id = $request->cargo_id;
        $nPersonal->estado_id = $request->estado_id;
        $nPersonal->entidad_id = $request->entidad_id;
        if($request->user_id != null){
            $nPersonal->user_id = $request->user_id;
        }
        //$nPersonal->user_id = $request->user_id; // Update user_id
        if ($request->foto != null) {
            $base64Image = $request->input('foto');
            $fileData = explode(',', $base64Image);
            $imageData = base64_decode($fileData[1]);
            // Generar un nombre único para el archivo
            $fileName = $request->rut . '.jpg'; // Puedes cambiar la extensión según el tipo de imagen

            // Guardar la imagen en el storage
            $filePath = 'images/' . $fileName;
            Storage::disk('public')->put($filePath, $imageData);
            $nPersonal->foto = $filePath;
        }


        $nPersonal->save();

        return redirect()->route('admin.personals.index');
    }

    public function show(Personal $personal)
    {
        abort_if(Gate::denies('personal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personal->load('cargo', 'estado', 'entidad');

        return view('admin.personals.show', compact('personal'));
    }

    public function destroy(Personal $personal)
    {
        abort_if(Gate::denies('personal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personal->delete();

        return back();
    }

    public function massDestroy(MassDestroyPersonalRequest $request)
    {
        $personals = Personal::find(request('ids'));

        foreach ($personals as $personal) {
            $personal->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    //Trato embalaje y HandPack
    public function tratoEmbalaje()
    {
        return view('admin.personals.tratoembalaje');
    }
    public function ejecutatratoembalaje(Request $request)
    {

        // Obtener el valor de trato diario
        $config = Configuracion::where('variable', 'ValorTratoDiario')->first();
        if (!$config) {
            return response()->json(['error' => 'Configuración ValorTratoDiario no encontrada.'], 500);
        };
        $valorTratoDiario = (float) $config->valor;
        $datos = DB::connection("sqlsrv")
            ->table('V_PKG_Embaladoras_MiniPC as A')
            ->select(
                'A.Creacion',
                'A.C_Trabajador',
                'A.nombre',
                'A.Rut_Trabajador',
                'A.N_embalaje_Actual',
                'B.peso_std',
                DB::raw('count(A.caja) as Cantidad_Cajas'),
                'B.CP4 as Valor'
            )
            ->leftJoin('V_Maestro_Items as B', 'A.C_embalaje_Actual', '=', 'B.codigo')
            ->whereNotNull('A.C_Trabajador')
            ->where('A.Creacion', '>=', $request->fechaInicio)
            ->where('A.Creacion', '<=', $request->fechaFinal)
            ->groupBy(
                'A.Creacion',
                'A.C_Trabajador',
                'A.nombre',
                'A.Rut_Trabajador',
                'A.N_embalaje_Actual',
                'B.peso_std',
                'B.CP4'
            )
            ->orderByDesc('A.Rut_Trabajador', 'A.Creacion')
            ->get();




        // --b.CP4*b.peso_std as Valor_Kilo,
        // --b.CP4*b.peso_std *count (a.caja) as Valor_Ganado_diario,
        // --b.CP4*b.peso_std *count (a.caja)-12500 as Total_a_Pagar
        $agrupadosPorTrabajador = $datos->sortBy([['Rut_Trabajador', 'asc']])
        ->groupBy('Rut_Trabajador')
        ->map(function ($items, $rut) use ($valorTratoDiario) {
            // Obtener nombre
            $nombre = $items->first()->nombre ?? '';

            // Agrupar detalles por fecha y calcular totales diarios
            $detallesPorFecha = $items->groupBy(function ($item) {
                return Carbon::parse($item->Creacion)->format('Y-m-d');
            })->map(function ($detalles, $fecha) use ($valorTratoDiario) {
                // Calcular la suma de cajas y el total del valor diario
                $totalCajasPorFecha = $detalles->sum('Cantidad_Cajas');
                $totalValorPorFecha = $detalles->reduce(function ($carry, $item) {
                    return $carry + ((float)$item->Valor * (float)$item->peso_std * (int)$item->Cantidad_Cajas);
                }, 0);

                // Restar el valor del trato diario y ajustar si es negativo
                $totalValorPorFecha -= $valorTratoDiario;
                $totalValorPorFecha = ($totalValorPorFecha < 0) ? 0 : $totalValorPorFecha;

                return [
                    'Fecha' => $fecha,
                    'Detalles' => $detalles->map(function ($item) {
                        return [
                            'Creacion' => Carbon::parse($item->Creacion)->format('d-m-Y'),
                            'C_Trabajador' => $item->C_Trabajador,
                            'N_embalaje_Actual'=> $item->N_embalaje_Actual,
                            'Nombre' => $item->nombre,
                            'Cantidad_Cajas' => $item->Cantidad_Cajas,
                            'Peso_Std' => $item->peso_std,
                            'Valor_Caja' => $item->Valor,
                        ];
                    })->values(),
                    'Total_Cajas' => $totalCajasPorFecha,
                    'Total_Valor' => $totalValorPorFecha,
                ];
            })->values();

            // Calcular totales generales por trabajador (suma de totales diarios)
            $totalCajas = $detallesPorFecha->sum('Total_Cajas');
            $totalValor = $detallesPorFecha->sum('Total_Valor');

            return [
                'Rut_Trabajador' => $rut,
                'Nombre' => $nombre,
                'Total_Cajas' => $totalCajas,
                'Total_a_pagar' => $totalValor,
                'Detalles' => $detallesPorFecha,
            ];
        })->values();
        Log::info("resultado",[$agrupadosPorTrabajador]);
         // Usamos values() para eliminar las claves asociativas y devolver un array numerado.
       // Log::info("resultado",[$agrupadosPorTrabajador]);




        // Retornar en formato JSON

        return response()->json($agrupadosPorTrabajador);
    }

    public function tratoContratista()
    {

        $personal = Personal::whereIn('entidad_id', [2, 3])->select(DB::raw("CONCAT(rut, '-', nombre) as full_name"), 'id')
            ->pluck('full_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $data = TratoContratistas::whereBetween('fecha', [Carbon::now()->subDay()->format('Y-m-d'), Carbon::now()->format('Y-m-d')])->with('personal')->get();
        foreach ($data as $t) {
            $personals = Personal::where('id', '=', $t->personal_id)->with("entidad")->first();
            $t->contratista = $personals->entidad->nombre;
        }
        //$tratoHP=TratoContratistas::where('fecha',Carbon::now()->subDay()->format('Y-m-d'))->get();
        return view('admin.personals.tratocontratista', compact('personal', 'data'));
    }
    public function guardatratohandpack(Request $request)
    {
        $tratoHP = new TratoContratistas();
        $tratoHP->fecha = Carbon::parse($request->fecha)->format('Y-m-d'); //$request->fecha;
        $tratoHP->personal_id = $request->personal_id;
        $tratoHP->cantidad = $request->cantidad;
        $tratoHP->monto_a_pagar = $request->monto_a_pagar;
        $tratoHP->factor_a_pagar = $request->factor_a_pagar;
        $tratoHP->cant_x_factor = $request->cant_x_factor;
        if ($tratoHP->monto_a_pagar < 0) {
            $tratoHP->monto_a_pagar = 0;
        }
        $tratoHP->save();
        $tratoHP = TratoContratistas::where('fecha', $request->fecha)->with('personal')->get();
        foreach ($tratoHP as $t) {
            $personal = Personal::where('id', '=', $t->personal_id)->with("entidad")->first();
            $t->contratista = $personal->entidad->nombre;
        }

        return response()->json($tratoHP);
    }
    public function destroyhandpack(Request $request)
    {
        try {
            $tratoHP = TratoContratistas::find($request->id);
            $tratoHP->delete();
            $message = 'Eliminado con exito';
        } catch (\Exception $e) {
            $message = "Error:" . $e->getMessage();
        }


        return response()->json(['message' => $message, 'success' => true], 200);
    }
    public function uploadtrato(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls',

            ]);
            $archivo = $request->file('file');
            $data = Excel::toArray(new ExcelImport, $archivo);
            $data = collect($data[0]);

            foreach ($data as $key => $value) {

                $tratoHP = new TratoContratistas();
                $tratoHP->fecha = Carbon::parse($this->formatDate2($value["fecha"]))->format('Y-m-d'); //$request->fecha;
                $rut = $value["rut"];
                $c9 = $value["9"];
                $c7 = $value["7"];
                $c6 = $value["6"];
                $c5 = $value["5"];
                $monto = 0;
                $montoTotal = 0;
                $maxVal = max([$c9, $c7, $c6, $c5]);
                $t9 = $c9 * 9;
                $t7 = $c7 * 7;
                $t6 = $c6 * 6;
                $t5 = $c5 * 5;
                $monto = $t9 + $t7 + $t6 + $t5;
                $valCajaResta = 315;
                $valCaja9 = 613;
                $valCaja7 = 477;
                $valCaja6 = 405;
                $valCaja5 = 341;
                if ($c9 == $maxVal) {
                    $montoTotal = ((($t9 + $t7 + $t6 + $t5) - $valCajaResta) / 9) * $valCaja9;
                    $factor = $valCaja9;
                    $cant_x_factor = 9;
                    $tratoHP->cantidad = $c9;
                } elseif ($c7 == $maxVal) {
                    $montoTotal = ((($t9 + $t7 + $t6 + $t5) - $valCajaResta) / 7) * $valCaja7;
                    $factor = $valCaja7;
                    $cant_x_factor = 7;
                    $tratoHP->cantidad = $c7;
                } elseif ($c6 == $maxVal) {
                    $montoTotal = ((($t9 + $t7 + $t6 + $t5) - $valCajaResta) / 6) * $valCaja6;
                    $factor = $valCaja6;
                    $cant_x_factor = 6;
                    $tratoHP->cantidad = $c6;
                } elseif ($c5 == $maxVal) {
                    $montoTotal = ((($t9 + $t7 + $t6 + $t5) - $valCajaResta) / 5) * $valCaja5;
                    $factor = $valCaja5;
                    $cant_x_factor = 5;
                    $tratoHP->cantidad = $c5;
                } else {
                    $montoTotal = 0;
                    $factor = 0;
                    $cant_x_factor = 0;
                    $tratoHP->cantidad = 0;
                }


                if ($montoTotal < 0) {
                    $montoTotal = 0;
                }
                $tratoHP->monto_a_pagar = round($montoTotal, 0);
                $tratoHP->factor_a_pagar = $factor;
                $tratoHP->cant_x_factor = $cant_x_factor;

                $personal = Personal::firstOrCreate(['rut' => $rut]);
                if ($personal) {
                    $personal->entidad_id = $value["contratista"];
                    $personal->save();
                }
                else
                {
                    $personal=Personal::where('rut','=',str_replace('.', '', $rut))->first();
                    if($personal){
                        $personal->entidad_id = $value["contratista"];
                        $personal->save();
                    }
                }
                $tratoHP->personal_id = $personal->id;
                $tratoHP->save();
                }

                return redirect()->route('admin.personals.tratoContratista', ['message' => 'Tratos cargados correctamente']);

        } catch (\Exception) {
            return redirect()->route('admin.personals.tratoContratista', ['message' => 'Error al cargar los tratos']);
        }
    }
    public function downloadtrato()
    {
        /*
        Fecha 2024-01-01
        rut 12345678-9
        9
        7
        6
        5
        valores de cajas
        */
        return Excel::download(new TratoContratistasTemplateExport, 'trato_contratistas_template.xlsx');
    }

    public function consultahandpack(Request $request)
    {
        $query = TratoContratistas::whereBetween('fecha', [$request->fecha, $request->fecha_fin])
            ->with('personal');

        if (!isset($request->personal_id) || $request->personal_id == null) {
            // No agregar condición de personal_id
        } else {
            $query->where('personal_id', $request->personal_id);
        }

        $tratoHP = $query->get();
        foreach ($tratoHP as $t) {
            $personal = Personal::where('id', '=', $t->personal_id)->with("entidad")->first();
            $t->contratista = $personal->entidad->nombre;
        }
        return response()->json($tratoHP);
    }

    protected function resolveContractorGroup(?string $departamento): ?string
    {
        if (!$departamento) {
            return null;
        }

        $dep = mb_strtolower(trim($departamento));

        foreach ($this->contractorGroups as $groupName => $aliases) {
            foreach ($aliases as $alias) {
                if ($dep === mb_strtolower($alias)) {
                    return $groupName;
                }
            }
        }

        return null;
    }

    protected function getDeptoId(string $departamento)
    {
        $depto = Entidad::where('nombre', 'like', $departamento . '%')->first();
        if (!$depto) {
            return null;
        }

        return $depto->id;
    }

    protected function formatearRutConDv($rut)
    {
        // Dejar solo n£meros
        $rut = preg_replace('/[^0-9]/', '', $rut);

        if ($rut === '' || !ctype_digit($rut)) {
            return null; // Manejo b sico de error
        }

        // ---- CALCULAR DV ----
        $suma = 0;
        $multiplicador = 2;

        for ($i = strlen($rut) - 1; $i >= 0; $i--) {
            $suma += intval($rut[$i]) * $multiplicador;
            $multiplicador++;
            if ($multiplicador > 7) {
                $multiplicador = 2;
            }
        }

        $resto = $suma % 11;
        $dv = 11 - $resto;

        if ($dv == 11) {
            $dv = "0";
        } elseif ($dv == 10) {
            $dv = "K";
        } else {
            $dv = (string)$dv;
        }

        // ---- FORMATEAR RUT ----
        $rutInvertido = strrev($rut);
        $rutFormateado = '';

        for ($i = 0; $i < strlen($rutInvertido); $i++) {
            if ($i > 0 && $i % 3 === 0) {
                $rutFormateado .= '.';
            }
            $rutFormateado .= $rutInvertido[$i];
        }

        $rutFormateado = strrev($rutFormateado);

        return $rutFormateado . '-' . $dv;
    }
    //Trato de embalaje

    //Cuadratura de Asistencia
    public function cuadratura()
    {
        return view('admin.personals.cuadratura');
    }

    public function assignLocationForm(Request $request)
    {
        abort_if(Gate::denies('personal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $supervisors = Personal::where('is_supervisor', 1) // Assuming cargo_id 2 is for supervisors
            ->pluck('nombre', 'id');

        $allLocations = \App\Models\Locacion::all()->keyBy('id');

        $parentLocations = $allLocations
            ->mapWithKeys(function ($locacion) use ($allLocations) {
                $visited = [];
                $segments = [];
                $current = $locacion;

                while ($current) {
                    if (isset($visited[$current->id])) {
                        break;
                    }

                    array_unshift($segments, $current->nombre);
                    $visited[$current->id] = true;

                    $parentId = $current->locacion_padre_id;
                    $current = $parentId ? $allLocations->get($parentId) : null;
                }

                return [$locacion->id => implode(' - ', $segments)];
            })
            ->sort()
            ->toArray();

        $assignedSupervisors = Personal::with('assignedLocation')
            ->whereNotNull('assigned_location_id')
            ->where('cargo_id', 2)
            ->get();

        $selectedSupervisor = null;
        $selectedLocation = null;

        if ($request->has('id')) {
            $selectedSupervisor = Personal::with('assignedLocation')->find($request->input('id'));
            if ($selectedSupervisor) {
                $selectedLocation = $selectedSupervisor->assigned_location_id;
            }
        }

        return view('admin.personals.assign_location', compact('supervisors', 'parentLocations', 'assignedSupervisors', 'selectedSupervisor', 'selectedLocation'));
    }

    public function assignLocationStore(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:personals,id',
            'location_id' => 'required|exists:locacions,id',
        ]);

        $personal = Personal::find($request->input('supervisor_id'));
        $personal->assigned_location_id = $request->input('location_id');
        $personal->save();

        return redirect()->route('admin.personals.assignLocationForm')->with('success', 'Ubicación asignada con éxito.');
    }

    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Executes the cuadratura process for attendance verification.
     *
     * This function takes a request containing a date and an uploaded file (xlsx, xls, or csv format),
     * processes the file to extract attendance data, and performs a verification against existing
     * attendance records in the database. The function expects the date in "dd/mm/yyyy" format and
     * uses it to define a date range for matching attendance entries. The uploaded file is read and
     * processed to extract relevant rows, which are then compared against the database records.
     *
     * The function categorizes attendance entries based on matches:
     * - "verde" for correctly paired entries.
     * - "amarillo" for entries with an entry time but no exit time.
     * - "rojo" for entries with an exit time but no entry time.
     *
     * @param Request $request The HTTP request object containing 'fecha' and 'file' inputs.
     * @return void
     */
    /******  a294a59f-e4d9-4b4c-b9e8-6fd681304ced  *******/
    public function ejecutaCuadratura(Request $request)
    {
        $fecha = explode("/", $request->fecha);
        $fechaIni = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0] . ' 00:00:00';
        $fechaFin = $fecha[2] . '-' . $fecha[1] . '-' . ($fecha[0] + 1) . ' 23:59:59';

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);


        // Cargar el archivo y procesarlo
        $file = $request->file('file');

        $data = Excel::toArray(new ExcelImport, $file);

        $data = collect($data[0])
            ->filter(function ($item) {
                // Filtrar valores donde la columna 4 no sea nula, vacía o "Fecha/Hora"
                return isset($item[4]) && $item[4] != "" && $item[4] != "Fecha/Hora";
            })
            ->sortBy(function ($item) {
                // Ordenar por la fecha formateada

                return $this->formatDate2($item[4]); // Fecha y hora en columna 4
            });




        $entradas = collect();
        $salidas = collect();
        $anulados = collect();
        $unificados = collect();
        $pareados = collect();
        $fechaXls = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];
        foreach ($data as $entry) {
            if ($entry[5] == "Entrada") {
                $entrada = new relojControl(); // Crear una nueva instancia para cada entrada
                $entrada->setRut($entry[0]);
                $entrada->setName($entry[1]);
                $entrada->setPuesto($entry[3]);
                $entrada->setDateTime($this->formatDate2($entry[4]));
                $entrada->setType($entry[5]);
                $entrada->setLlave($entrada->getRut() . '-' . $entrada->getDateTime());
                $entradas->push($entrada);
                $personal = Personal::where('rut', $entry[0])->first();
                if (!$personal) {
                    $personal = new Personal();
                    $personal->rut = $entry[0];
                    $personal->nombre = $entry[1];
                    $personal->save();
                }
            }
            if ($entry[5] == "Salida") {
                $salida = new relojControl(); // Crear una nueva instancia para cada salida
                $salida->setRut($entry[0]);
                $salida->setName($entry[1]);
                $salida->setPuesto($entry[3]);
                $salida->setDateTime($this->formatDate2($entry[4]));
                $salida->setType($entry[5]);
                $salida->setLlave($salida->getRut() . '-' . $salida->getDateTime());
                $salidas->push($salida);
            }
        }

        // Eliminar filas duplicadas del array final
        $entradas = $entradas->unique(function ($item) {
            return $item->getLlave();
        });

        $salidas = $salidas->unique(function ($item) {
            return $item->getLlave();
        });
        foreach ($entradas as $entrada) {
            $matchingSalida = $salidas->first(function ($salida) use ($entrada) {
                return $salida->getRut() === $entrada->getRut() &&
                    $salida->getName() === $entrada->getName() &&
                    $salida->getDateTime() > $entrada->getDateTime();
            });

            if ($matchingSalida) {
                $pareados->push([
                    'entrada' => $entrada,
                    'salida' => $matchingSalida
                ]);

                // Remover de entradas y salidas
                $entradas = $entradas->reject(function ($item) use ($entrada) {
                    return $item->getLlave() === $entrada->getLlave();
                });

                $salidas = $salidas->reject(function ($item) use ($matchingSalida) {
                    return $item->getLlave() === $matchingSalida->getLlave();
                });
            }
        }
        $unificados = $entradas->merge($salidas)->sortBy(function ($item) {
            return $item->getRut() . '-' . $item->getName() . '-' . $item->getDateTime();
        });

        //exportToExcel($pareados, $unificados,$fechaXls);
        return Excel::download(new MultiSheetExport($pareados, $unificados), 'reporte_asistencia-depurado-' . $fechaXls . '.xlsx');




        // $writer = new Xlsx($spreadsheet);
        // $filePath = storage_path('app/public/attendance_report.xlsx');
        // // $writer->save($filePath);

        // return response()->download($filePath)->deleteFileAfterSend(true);
    }
    function buscarSalida($datos, $rut)
    {
        foreach ($datos as $registro) {
            if ($registro[0] === $rut && $registro[5] === 'Salida') {
                return $registro;
            }
        }
        return null;
    }

    function buscarEntrada($datos, $rut)
    {
        foreach ($datos as $registro) {
            if ($registro[0] === $rut && $registro[5] === 'Entrada') {
                return $registro;
            }
        }
        return null;
    }
    public function descargarExcel($datos)
    {
        $nombreArchivo = 'reporte_asistencia.xlsx';

        // Pasa los datos procesados a la exportación
        $datosProcesados = $this->procesarDatos($datos);

        return Excel::download(new AsistenciaExport($datosProcesados), $nombreArchivo);
    }
    function formatDate($date)
    {
        try {
            $excelDate = $date;  // El valor que quieres convertir
            Log::info($excelDate);
            $timestamp = Carbon::createFromTimestamp((int)(($excelDate - 25569) * 86400));
            //$timestamp->setTimezone('America/Santiago');
            return $timestamp->format('d-m-Y H:i');
        } catch (\Exception $e) {
            Log::error($e);
            return $date;
        }
    }
    function formatDateQuery($date)
    {
        $excelDate = $date;  // El valor que quieres convertir
        $timestamp = Carbon::createFromTimestamp((int)(($excelDate - 25569) * 86400));
        //$timestamp->setTimezone('America/Santiago');
        return $timestamp->format('Y-m-d');
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
}
class AttendanceExport implements FromCollection, WithHeadings
{
    private $collection;
    private $headings;

    public function __construct($collection, $headings)
    {
        $this->collection = $collection;
        $this->headings = $headings;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}

// Generar el archivo Excel

class relojControl
{
    private $rut;
    private $name;
    private $puesto;
    private $dateTime;
    private $type;
    private $llave;

    public function __construct() {}

    public function getRut()
    {
        return $this->rut;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPuesto()
    {
        return $this->puesto;
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getLlave()
    {
        return $this->llave;
    }

    public function setRut($rut)
    {
        $this->rut = $rut;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;
    }

    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setLlave($llave)
    {
        $this->llave = $llave;
    }

    public function toArray()
    {
        return [
            'rut' => $this->rut,
            'name' => $this->name,
            'puesto' => $this->puesto,
            'dateTime' => $this->dateTime,
            'type' => $this->type,
            'llave' => $this->llave,
        ];
    }
}
class MultiSheetExport implements WithMultipleSheets
{
    protected $pareados;
    protected $unificados;

    public function __construct($pareados, $unificados)
    {
        $this->pareados = $pareados;
        $this->unificados = $unificados;
    }

    public function sheets(): array
    {
        return [
            new PareadosSheet($this->pareados),
            new UnificadosSheet($this->unificados),
        ];
    }
}
class PareadosSheet implements FromCollection, WithHeadings
{
    protected $pareados;

    public function __construct($pareados)
    {
        $this->pareados = $pareados;
    }

    public function collection()
    {
        return collect($this->pareados)->map(function ($item) {
            return [
                'RUT Entrada' => $item['entrada']->getRut(),
                'Nombre Entrada' => $item['entrada']->getName(),
                'Fecha/Hora Entrada' => $item['entrada']->getDateTime(),
                'RUT Salida' => $item['salida']->getRut(),
                'Nombre Salida' => $item['salida']->getName(),
                'Fecha/Hora Salida' => $item['salida']->getDateTime(),
                'Departamento' => $item['salida']->getPuesto(),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'RUT Entrada',
            'Nombre Entrada',
            'Fecha/Hora Entrada',
            'RUT Salida',
            'Nombre Salida',
            'Fecha/Hora Salida',
            'Departamento',
        ];
    }
}
class UnificadosSheet implements FromCollection, WithHeadings
{
    protected $unificados;

    public function __construct($unificados)
    {
        $this->unificados = $unificados;
    }

    public function collection()
    {
        return collect($this->unificados)->map(function ($item) {

            return [
                'RUT' => $item->getRut(),
                'Nombre' => $item->getName(),
                'Departamento' => $item->getPuesto(),
                'Fecha/Hora' => $item->getDateTime(),
                'Tipo' => $item->getType(),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'RUT',
            'Nombre',
            'Puesto',
            'Fecha/Hora',
            'Tipo',

        ];
    }
}
