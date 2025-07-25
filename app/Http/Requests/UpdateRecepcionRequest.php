<?php

namespace App\Http\Requests;

use App\Models\Recepcion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRecepcionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('recepcion_edit');
    }

    public function rules()
    {
        return [
            'productor_id' => [
                'required',
                'integer',
            ],
            'variedad' => [
                'string',
                'required',
            ],
            'total_kilos' => [
                'numeric',
                'required',
            ],
        ];
    }
}
