<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GlobalSearchController extends Controller
{
    private $models = [
        'Airline'                     => 'cruds.airline.title',
        'Vuelo'                       => 'cruds.vuelo.title',
        'Ciudad'                      => 'cruds.ciudad.title',
        'Region'                      => 'cruds.region.title',
        'Comuna'                      => 'cruds.comuna.title',
        'Status'                      => 'cruds.status.title',
        'TipoHawb'                    => 'cruds.tipoHawb.title',
        'Manifiest'                   => 'cruds.manifiest.title',
        'Guium'                       => 'cruds.guium.title',
        'ImportacionMarcasManifiesto' => 'cruds.importacionMarcasManifiesto.title',
        'PreCargaManifiesto'          => 'cruds.preCargaManifiesto.title',
        'Configuracion'               => 'cruds.configuracion.title',
        'Traduccione'                 => 'cruds.traduccione.title',
        'Hawb'                        => 'cruds.hawb.title',
        'Inhumado'                    => 'cruds.inhumado.title',
        'Aclaracionguium'             => 'cruds.aclaracionguium.title',
        'AclaracionMawb'              => 'cruds.aclaracionMawb.title',
        'Ingreso'                     => 'cruds.ingreso.title',
        'DatosCaja'                   => 'cruds.datosCaja.title',
    ];

    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search === null || ! isset($search['term'])) {
            abort(400);
        }

        $term           = $search['term'];
        $searchableData = [];
        foreach ($this->models as $model => $translation) {
            $modelClass = 'App\Models\\' . $model;
            $query      = $modelClass::query();

            $fields = $modelClass::$searchable;

            foreach ($fields as $field) {
                $query->orWhere($field, 'LIKE', '%' . $term . '%');
            }

            $results = $query->take(10)
                ->get();

            foreach ($results as $result) {
                $parsedData           = $result->only($fields);
                $parsedData['model']  = trans($translation);
                $parsedData['fields'] = $fields;
                $formattedFields      = [];
                foreach ($fields as $field) {
                    $formattedFields[$field] = Str::title(str_replace('_', ' ', $field));
                }
                $parsedData['fields_formated'] = $formattedFields;

                $parsedData['url'] = url('/admin/' . Str::plural(Str::snake($model, '-')) . '/' . $result->id . '/edit');

                $searchableData[] = $parsedData;
            }
        }

        return response()->json(['results' => $searchableData]);
    }
}
