@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.naviera.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.navieras.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.naviera.fields.id') }}
                        </th>
                        <td>
                            {{ $naviera->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.naviera.fields.nombre') }}
                        </th>
                        <td>
                            {{ $naviera->nombre }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.navieras.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#naviera_instructivo_embarques" role="tab" data-toggle="tab">
                {{ trans('cruds.instructivoEmbarque.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="naviera_instructivo_embarques">
            @includeIf('admin.navieras.relationships.navieraInstructivoEmbarques', ['instructivoEmbarques' => $naviera->navieraInstructivoEmbarques])
        </div>
    </div>
</div>

@endsection