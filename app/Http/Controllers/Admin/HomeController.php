<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    public function index()
    {

        return view('home');
    }
}
