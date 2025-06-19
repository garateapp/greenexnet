<?php

namespace App\Http\Requests;

use App\Models\FormaPago;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFormaPagoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('forma_pago_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
        ];
    }
}
