<?php

namespace App\Http\Requests;

use App\Models\TurnosFrecuencium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTurnosFrecuenciumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('turnos_frecuencium_edit');
    }

    public function rules()
    {
        return [
            'frecuencia_id' => [
                'required',
                'integer',
            ],
            'locacion_id' => [
                'required',
                'integer',
            ],
            'nombre' => [
                'string',
                'required',
            ],
        ];
    }
}
