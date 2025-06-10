@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.valorEnvase.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.valor-envases.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.valorEnvase.fields.id') }}
                        </th>
                        <td>
                            {{ $valorEnvase->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.valorEnvase.fields.productor') }}
                        </th>
                        <td>
                            {{ $valorEnvase->productor->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.valorEnvase.fields.valor') }}
                        </th>
                        <td>
                            {{ $valorEnvase->valor }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.valor-envases.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection