<?php

namespace App\Bi\Dashboards;

use App\Models\Asistencium;
use App\Models\Locacion;
use Illuminate\Support\Facades\Date;
use LaravelBi\LaravelBi\Dashboard;

use LaravelBi\LaravelBi\Widgets\BigNumber;
use LaravelBi\LaravelBi\Widgets\Table;
use LaravelBi\LaravelBi\Filters\BelongsToFilter;
use LaravelBi\LaravelBi\Filters\TextFilter;
use LaravelBi\LaravelBi\Filters\DateIntervalFilter;
use LaravelBi\LaravelBi\Filters\DateFilter;
use LaravelBi\LaravelBi\Metrics\CountMetric;
use LaravelBi\LaravelBi\Dimensions\DateDimension;
use Symfony\Component\Finder\Iterator\DateRangeFilterIterator;

class AsistenciaDashboard extends Dashboard
{

    public $model  = Asistencium::class;
    public $uriKey = 'asistenciaDashboard';
    public $name   = 'AsistenciaDashboar';

    public function filters()
    {
        $filter = new BelongsToFilter('locacion_id', 'Ubicación');
        $filtroFecha = new DateIntervalFilter('fecha_hora', 'Fechas');
        return [
            $filter->relation('locacion')->otherColumn('nombre'),
            $filtroFecha->defaultDates(Date::now()->subDays(30), Date::now())
        ];
    }

    public function widgets()
    {
        return [
            BigNumber::create('user-count', 'Cantidad de asistencias')
                ->metric(
                    CountMetric::create('count', 'Count')
                        ->color('#ff5555')
                )
                ->width('1/3'),
            Table::create('asistencia-count', 'Cantidad de asistencias')
                ->orderBy('fecha_hora', 'desc')->dimension(
                    DateDimension::create('fecha_hora', 'Fecha')

                )
                ->metric(
                    CountMetric::create('count', 'Count')
                        ->color('#ff5555')
                )
                ->width('1/3'),


        ];
    }
}
