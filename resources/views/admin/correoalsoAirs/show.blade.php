@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.correoalsoAir.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.correoalso-airs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.id') }}
                        </th>
                        <td>
                            {{ $correoalsoAir->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.cliente') }}
                        </th>
                        <td>
                            {{ $correoalsoAir->cliente->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.puerto_requerido') }}
                        </th>
                        <td>
                            {{ $correoalsoAir->puerto_requerido }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.correos') }}
                        </th>
                        <td>
                            {{ $correoalsoAir->correos }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.also_notify') }}
                        </th>
                        <td>
                            {{ $correoalsoAir->also_notify }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.correoalsoAir.fields.transporte') }}
                        </th>
                        <td>
                            {{ App\Models\CorreoalsoAir::TRANSPORTE_SELECT[$correoalsoAir->transporte] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.correoalso-airs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection