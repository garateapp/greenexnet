<?php

namespace App\Http\Requests;

use App\Models\MaterialProducto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMaterialProductoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('material_producto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:material_productos,id',
        ];
    }
}
