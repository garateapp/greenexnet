@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.recepcion.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.recepcions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.recepcion.fields.id') }}
                        </th>
                        <td>
                            {{ $recepcion->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recepcion.fields.productor') }}
                        </th>
                        <td>
                            {{ $recepcion->productor->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recepcion.fields.variedad') }}
                        </th>
                        <td>
                            {{ $recepcion->variedad }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recepcion.fields.total_kilos') }}
                        </th>
                        <td>
                            {{ $recepcion->total_kilos }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.recepcions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection