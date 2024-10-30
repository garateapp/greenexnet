<?php

namespace App\Http\Requests;

use App\Models\FrecuenciaTurno;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFrecuenciaTurnoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('frecuencia_turno_edit');
    }

    public function rules()
    {
        return [
            'dia' => [
                'required',
            ],
            'nombre' => [
                'string',
                'nullable',
            ],
        ];
    }
}
