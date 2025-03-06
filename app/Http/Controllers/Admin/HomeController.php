<?php

namespace App\Http\Controllers\Admin;

use App\Models\LiqCxCabecera;
use App\Models\LiquidacionesCx;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use DB;

class HomeController
{
    public function index()
    {
        $liqs = LiqCxCabecera::all()->count();
        $total = DB::connection('sqlsrv')->table('dbo.V_PKG_Embarques')
            ->where('n_embarque', 'like', '2425%')
            ->where('id_especie', 7)
            ->distinct('n_embarque')
            ->count('n_embarque');
        $porCargar = $total - $liqs;
        return view('home', compact('liqs', 'total'));
    }

}
