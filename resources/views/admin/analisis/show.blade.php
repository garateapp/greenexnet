@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.analisi.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.analisis.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.analisi.fields.id') }}
                        </th>
                        <td>
                            {{ $analisi->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.analisi.fields.productor') }}
                        </th>
                        <td>
                            {{ $analisi->productor->rut ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.analisi.fields.temporada') }}
                        </th>
                        <td>
                            {{ $analisi->temporada }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.analisi.fields.especie') }}
                        </th>
                        <td>
                            {{ $analisi->especie }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.analisi.fields.csg') }}
                        </th>
                        <td>
                            {{ $analisi->csg }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.analisi.fields.valor') }}
                        </th>
                        <td>
                            {{ $analisi->valor }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.analisis.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection