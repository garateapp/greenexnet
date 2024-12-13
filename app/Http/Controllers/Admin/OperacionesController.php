<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencium;
use App\Models\Personal;
use App\Models\Locacion;
use App\Models\Area;
use App\Models\ClientesComex;
use App\Models\Embalaje;
use App\Models\Turno;
use App\Models\FrecuenciaTurno;
use App\Models\MetasClienteComex;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
use DB;
use Yajra\DataTables\Facades\DataTables;

class OperacionesController extends Controller
{
    public function fusionarFolios()
    {

       $stock= DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
       ->select('id'
      ,'id_pkg_stock'
      ,'folio'
      ,'cantidad'
      ,'peso_neto'
      ,'creacion_tipo'
      ,'creacion_id'
      ,'destruccion_tipo'
      ,'destruccion_id'
      ,'peso_final'
      ,'valor'
      ,'Origen'
      ,'Id_origen'
      ,'tipo_origen')
      ->where('fecha_destruccion', '>=', DB::RAW("DATEADD(DAY, -3, GETDATE())"))
      ->get();

        return view('admin.operaciones.fusionarFolios',compact('stock'));
    }
}
