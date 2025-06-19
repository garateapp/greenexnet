<?php

namespace App\Http\Requests;

use App\Models\FormaPago;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFormaPagoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('forma_pago_create');
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
