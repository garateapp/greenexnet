<?php

namespace App\Http\Requests;

use App\Models\Turno;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTurnoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('turno_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'hora_inicio' => [
                'required',
                'date_format:' . config('panel.time_format'),
            ],
            'hora_fin' => [
                'required',
                'date_format:' . config('panel.time_format'),
            ],
            'fecha'=>[
                'required',
                'date_format:' . config('panel.date_format'),
            ]
        ];
    }
}
