@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.entidad.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.entidads.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.entidad.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $entidad->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.entidad.fields.nombre') }}
                                    </th>
                                    <td>
                                        {{ $entidad->nombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.entidad.fields.rut') }}
                                    </th>
                                    <td>
                                        {{ $entidad->rut }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.entidad.fields.tipo') }}
                                    </th>
                                    <td>
                                        {{ $entidad->tipo->nombre ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.entidad.fields.direccion') }}
                                    </th>
                                    <td>
                                        {{ $entidad->direccion }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.entidads.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection