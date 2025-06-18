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
use Illuminate\Support\Str;
use App\Exports\ComparativaExport;
use Exception;
use Psy\Readline\Hoa\Console;
use Symfony\Component\Console\Logger\ConsoleLogger;
use App\Models\Productor;
use App\Models\Especy;
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
                                //'analisis' => $analisis
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
    public function generatepdf(Request $request){

         // Validar la solicitud
         $request->validate([
            'tabs' => 'required|array',
            'tabs.*.name' => 'required|string',
            'tabs.*.html' => 'required|string',
        ]);
        $productor =Productor::find($request->input('productor_id'))->first();

         // Preparar datos para la vista
         $tabs_pdf = $request->input('tabs');
         $tabs_processed_pdf = array_map(function ($tab_pdf) {
            $productor_nombre_pdf = '';
            if ($tab_pdf['name'] === 'Norma Con Semana') {
                if (preg_match('/<td[^>]*class="productorNombre"[^>]*>([^<]*)<\/td>/i', $tab_pdf['html'], $matches_pdf)) {
                    $productor_nombre_pdf = $matches_pdf[1];
                }
            }
            return [
                'name' => $tab_pdf['name'],
                'html' => $tab_pdf['html'],
                'productor_nombre' => $productor_nombre_pdf,
            ];
        }, $tabs_pdf);

        $data_pdf = [
            'tabs' => $tabs_processed_pdf,
            'logo_path' => public_path('storage/cabecera_pdf.jpg'),
            'footer_path' => public_path('storage/footer_pdf.jpg'),
        ];

        $snappy_pdf = new Pdf('C:\\wkhtmltopdf\\bin\\wkhtmltopdf'); // Adjust path
        $html_pdf = view('admin.constructorliquidacion.tabs', $data_pdf)->render();

        return response($snappy_pdf->getOutputFromHtml($html_pdf, [
            'orientation' => 'Portrait',
            'page-size' => 'A4',
            'margin-top' => '10mm',
            'margin-bottom' => '10mm',
            'margin-left' => '10mm',
            'margin-right' => '10mm',
            'dpi' => 150,
            'enable-local-file-access' => true,
            'no-stop-slow-scripts' => true,
        ]))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Liquidación-'.$productor->nombre.'-'.date('Y-m-d').'.pdf"');

        // Descargar el PDF
       // return $pdf_pdf->download('Liquidación-'.$productor->nombre.'-'.date('Y-m-d').'.pdf');
    }


}
