@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.costo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.costos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.costo.fields.id') }}
                        </th>
                        <td>
                            {{ $costo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.costo.fields.nombre') }}
                        </th>
                        <td>
                            {{ $costo->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.costo.fields.valor_x_defecto') }}
                        </th>
                        <td>
                            {{ $costo->valor_x_defecto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.costo.fields.categoria') }}
                        </th>
                        <td>
                            {{ App\Models\Costo::CATEGORIA_SELECT[$costo->categoria] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.costos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection