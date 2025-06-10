@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.valorFlete.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.valor-fletes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.valorFlete.fields.id') }}
                        </th>
                        <td>
                            {{ $valorFlete->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.valorFlete.fields.condicion') }}
                        </th>
                        <td>
                            {{ $valorFlete->condicion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.valorFlete.fields.productor') }}
                        </th>
                        <td>
                            {{ $valorFlete->productor->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.valorFlete.fields.valor') }}
                        </th>
                        <td>
                            {{ $valorFlete->valor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.valorFlete.fields.valor_dolar') }}
                        </th>
                        <td>
                            {{ $valorFlete->valor_dolar->valor ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.valor-fletes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection