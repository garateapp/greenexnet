@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.interesAnticipo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.interes-anticipos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.interesAnticipo.fields.id') }}
                        </th>
                        <td>
                            {{ $interesAnticipo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.interesAnticipo.fields.anticipo') }}
                        </th>
                        <td>
                            {{ $interesAnticipo->anticipo->fecha_documento ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.interesAnticipo.fields.valor') }}
                        </th>
                        <td>
                            {{ $interesAnticipo->valor }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.interes-anticipos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection