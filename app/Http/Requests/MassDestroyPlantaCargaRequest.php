<?php

namespace App\Http\Requests;

use App\Models\PlantaCarga;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPlantaCargaRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('planta_carga_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:planta_cargas,id',
        ];
    }
}
