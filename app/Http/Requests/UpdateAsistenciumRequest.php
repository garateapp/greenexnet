<?php

namespace App\Http\Requests;

use App\Models\Asistencium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAsistenciumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('asistencium_edit');
    }

    public function rules()
    {
        return [
            'locacion_id' => [
                'required',
                'integer',
            ],
            'turno_id' => [
                'required',
                'integer',
            ],
            'personal_id' => [
                'required',
                'integer',
            ],
            'fecha_hora' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
