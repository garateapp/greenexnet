@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.centroCosto.title_singular') }} {{ trans('global.show') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.centroCosto.fields.id') }}
                        </th>
                        <td>
                            {{ $centroCosto->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.centroCosto.fields.entidad') }}
                        </th>
                        <td>
                            {{ $centroCosto->entidad ? $centroCosto->entidad->nombre : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.centroCosto.fields.id_centrocosto') }}
                        </th>
                        <td>
                            {{ $centroCosto->id_centrocosto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.centroCosto.fields.c_centrocosto') }}
                        </th>
                        <td>
                            {{ $centroCosto->c_centrocosto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.centroCosto.fields.n_centrocosto') }}
                        </th>
                        <td>
                            {{ $centroCosto->n_centrocosto }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a class="btn btn-default" href="{{ route('admin.centro-costos.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>

@endsection
