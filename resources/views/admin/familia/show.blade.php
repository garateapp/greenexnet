@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.familium.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.familia.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.familium.fields.id') }}
                        </th>
                        <td>
                            {{ $familium->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.familium.fields.codigo') }}
                        </th>
                        <td>
                            {{ $familium->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.familium.fields.nombre') }}
                        </th>
                        <td>
                            {{ $familium->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.familium.fields.cap') }}
                        </th>
                        <td>
                            {{ $familium->cap }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.familia.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection