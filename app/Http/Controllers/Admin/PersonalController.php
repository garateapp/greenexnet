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


class PersonalController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('personal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Personal::with(['cargo', 'estado', 'entidad'])->select(sprintf('%s.*', (new Personal)->table));
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

            $table->rawColumns(['actions', 'placeholder', 'cargo', 'estado', 'entidad']);

            return $table->make(true);
        }

        return view('admin.personals.index');
    }

    public function create()
    {
        abort_if(Gate::denies('personal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cargos = Cargo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.personals.create', compact('cargos', 'entidads', 'estados'));
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
        $base64Image = $request->input('foto');
        $fileData = explode(',', $base64Image);
        $imageData = base64_decode($fileData[1]);
        // Generar un nombre único para el archivo
        $fileName = $request->rut . '.jpg'; // Puedes cambiar la extensión según el tipo de imagen

        // Guardar la imagen en el storage
        $filePath = 'images/' . $fileName;
        Storage::disk('public')->put($filePath, $imageData);

        $personal->foto = $filePath;

        $personal->save();

        // $personal = Personal::create($request->all());

        return redirect()->route('admin.personals.index');
    }

    public function edit(Personal $personal)
    {
        abort_if(Gate::denies('personal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cargos = Cargo::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $estados = Estado::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entidads = Entidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $personal->load('cargo', 'estado', 'entidad');

        return view('admin.personals.edit', compact('cargos', 'entidads', 'estados', 'personal'));
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
        //dd($request->foto);
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
        //$personal->update($request->all());

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


    //Cuadratura de Asistencia
    public function cuadratura()
    {
        return view('admin.personals.cuadratura');
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
        $unificados=collect();
        $pareados = collect();
        $fechaXls=$fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];
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
                $personal=Personal::where('rut', $entry[0])->first();
                if(!$personal){
                    $personal=new Personal();
                    $personal->rut=$entry[0];
                    $personal->name=$entry[1];
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
       return Excel::download(new MultiSheetExport($pareados, $unificados), 'reporte_asistencia-depurado-'.$fechaXls.'.xlsx');




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
                'Fecha/Hora'=>$item->getDateTime(),
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
