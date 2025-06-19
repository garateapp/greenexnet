@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.clausulaVentum.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clausula-venta.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.clausulaVentum.fields.id') }}
                        </th>
                        <td>
                            {{ $clausulaVentum->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clausulaVentum.fields.nombre') }}
                        </th>
                        <td>
                            {{ $clausulaVentum->nombre }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clausula-venta.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection