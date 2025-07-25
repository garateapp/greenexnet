@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.otroCobro.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.otro-cobros.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.otroCobro.fields.id') }}
                        </th>
                        <td>
                            {{ $otroCobro->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.otroCobro.fields.productor') }}
                        </th>
                        <td>
                            {{ $otroCobro->productor->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.otroCobro.fields.valor') }}
                        </th>
                        <td>
                            {{ $otroCobro->valor }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.otro-cobros.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection