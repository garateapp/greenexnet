<?php
namespace App\Libs;

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
use App\Models\ClientesComex;
use App\Models\Capturador;
use App\Models\CapturadorEstructura;
use App\Imports\ExcelConversor;
use App\Models\ExcelDato;
use Illuminate\Support\Str;
use App\Models\LiqCxCabecera;
use App\Models\LiquidacionesCx;
use App\Models\LiqCosto;
use App\Models\Costo;
use App\Models\Nafe;
use App\Exports\ComparativaExport;
use App\Models\Diccionario;

class Funciones_Globales
{
function Funciones_Globales(){

}
function traducedatos($texto, $tipo)
    {
        try {
            if ($texto == null || $texto == '') {
                return $texto;
            }
           // Log::info("Traduciendo datos: " . $texto . "----" . $tipo);
            $dato = Diccionario::where("tipo", $tipo)->where("variable", $texto)->first();
            if ($dato == null) {
                return $texto;
            }
            return $dato->valor;
        } catch (\Exception $e) {
           // Log::error("Error al traducir datos: " . $e->getMessage() . "----" . $texto . "----" . $tipo);

            return $texto;
        }
    }
}
