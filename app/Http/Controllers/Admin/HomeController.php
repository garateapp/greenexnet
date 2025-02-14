<?php

namespace App\Http\Controllers\Admin;

use App\Models\LiqCxCabecera;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use DB;

class HomeController
{
    public function index()
    {
        $liqs=LiqCxCabecera::all()->count();
        $total=DB::connection('sqlsrv')->table('dbo.PKG_Embarques')
        ->where('numero', 'like', '2425%')->count();
        $porCargar=$total-$liqs;
        return view('home', compact('liqs','total'));
    }
}
