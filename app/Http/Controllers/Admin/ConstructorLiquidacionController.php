<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmbarqueRequest;
use App\Http\Requests\StoreEmbarqueRequest;
use App\Http\Requests\UpdateEmbarqueRequest;
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
use App\Mail\MiMailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeGenericoMailable;
use App\Imports\ExcelConversor;
use App\Models\Proceso;
//use App\Models\Analisis;
use App\Models\Anticipo;
use App\Models\Recepcion;
use App\Models\ValorFlete;
use App\Models\ValorEnvase;
use App\Models\InteresAnticipo;
use App\Models\Bonificacion;
use App\Models\Multiresiduo;
use App\Models\OtroCobro;
use Illuminate\Support\Str;
use App\Exports\ComparativaExport;
use Exception;
use Psy\Readline\Hoa\Console;
use Symfony\Component\Console\Logger\ConsoleLogger;
use App\Models\Productor;
use App\Models\Especy;
use App\Models\InteresAnticipo;
//use Barryvdh\DomPDF\Facade\Pdf;
use Knp\Snappy\Pdf;
class ConstructorLiquidacionController extends Controller
{
use CsvImportTrait;


    public function selector(Request $request)
    {
        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        $temporada= Proceso::pluck('temporada')->unique()->prepend(trans('global.pleaseSelect'), '');
        $especie= Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.constructorliquidacion.index', compact('productors', 'temporada', 'especie'));
    }
    public function getProcesos(Request $request)
    {
        $productor = $request->input('productor_id');
        $temporada = $request->input('temporada');
        $especie = $request->input('especie_id');


        // Realiza la consulta a la base de datos
        $result = Proceso::where('productor_id', $productor)
        ->where('temporada', $temporada)
        ->whereIn('especie_id', [4,5,6])->with("especie")->get();

        $anticipos = Anticipo::where('productor_id', $productor)
        ->where('temporada', $temporada)->get();
        //->whereIn('especie_id',  [4,5,6])->get();
        $valorflete=Valorflete::where('productor_id', $productor)
        ->where('temporada', $temporada)->get();
        $prod=Productor::where('id', $productor)->first();
        $envases=valorEnvase::where('productor_id', $productor)
        ->where('temporada', $temporada)->get();
        // $analisis=Analisis::where('productor_id', $productor)
        // ->where('temporada', $temporada)->get();
        $bonificacion=Bonificacion::where('productor_id', $productor)->get();
        $OtroCobro=OtroCobro::where('productor_id', $productor)->get();
        $multiresiduo=Multiresiduo::where('productor_id', $productor)->get();
        $InteresAnticipo=InteresAnticipo::where('productor_id', $productor)->get();
        // Verifica si se encontraron resultados
        if (!$result) {
            return response()->json(['message' => 'No se encontraron resultados'], 404);
        }
        // Si se encontraron resultados, devuelve los datos en formato JSON

        return response()->json(['result' => $result,'success' => true,
                                'anticipos' => $anticipos,
                                'valorflete' => $valorflete,
                                'productor' => $prod,
                                'envases' => $envases,
                                //'analisis' => $analisis,
                                'bonificacion' => $bonificacion,
                                'OtroCobro' => $OtroCobro,
                                'multiresiduo' => $multiresiduo,
                                'InteresAnticipo' => $InteresAnticipo
                                ]
                                , 200);

    }
    public function show()
    {
        $productors = Productor::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        $temporada= Proceso::pluck('temporada')->unique()->prepend(trans('global.pleaseSelect'), '');
        $especie= Especy::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.constructorliquidacion.index', compact('productors', 'temporada', 'especie'));
    }
 public function generatepdf(Request $request)
{
    // Validar entrada
    $request->validate([
        'tabs' => 'required|array',
        'chartImages' => 'nullable|array'
    ]);

    $tabs = $request->input('tabs');
    $chartImages = $request->input('chartImages', []);

    $data = [
        'tabs' => $tabs,
        'chartImages' => $chartImages,
        'logo_path' => public_path('img/cabecera_pdf.jpg'),
        'footer_path' => public_path('img/footer_pdf.jpg'),
        'portada'=>public_path('img/portada-carozos2425.jpeg')
    ];

    $snappy = new Pdf('/usr/bin/wkhtmltopdf');
    $productor=Productor::where('id',$request->input('productor_id'))->first();
    $html = view('admin.constructorliquidacion.tabs', $data)->render();

    return response($snappy->getOutputFromHtml($html, [
        'orientation' => 'Portrait',
        'page-size' => 'A4',
        'dpi' => 150,
        'enable-local-file-access' => true,
        'no-stop-slow-scripts' => true
    ]))->header('Content-Type', 'application/pdf')
      ->header('Content-Disposition', 'inline; filename="Liquidacion-' .$productor->nombre.'-Carozos2425-'.now()->format('Y-m-d') . '.pdf"');
}

}
