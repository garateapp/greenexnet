<?php

namespace App\Bi\Dashboards;

use App\Models\Asistencium;
use LaravelBi\LaravelBi\Dashboard;

use LaravelBi\LaravelBi\Widgets\BigNumber;
use LaravelBi\LaravelBi\Filters\DateFilter;
use LaravelBi\LaravelBi\Metrics\CountMetric;

class AsistenciaDashboard extends Dashboard
{

    public $model  = Asistencium::class;
    public $uriKey = 'asistenciaDashboard';
    public $name   = 'AsistenciaDashboar';

    public function filters()
    {
        return [];
    }

    public function widgets()
    {
        return [
            BigNumber::create('user-count', 'Registered users')
                ->metric(
                    CountMetric::create('count', 'Count')
                        ->color('#ff5555')
                )
                ->width('1/3')
        ];
    }
}
