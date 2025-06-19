@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.embarcador.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.embarcadors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.id') }}
                        </th>
                        <td>
                            {{ $embarcador->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.codigo') }}
                        </th>
                        <td>
                            {{ $embarcador->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.via') }}
                        </th>
                        <td>
                            {{ App\Models\Embarcador::VIA_SELECT[$embarcador->via] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.nombre') }}
                        </th>
                        <td>
                            {{ $embarcador->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.rut') }}
                        </th>
                        <td>
                            {{ $embarcador->rut }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.attn') }}
                        </th>
                        <td>
                            {{ $embarcador->attn }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.email') }}
                        </th>
                        <td>
                            {{ $embarcador->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.telefono') }}
                        </th>
                        <td>
                            {{ $embarcador->telefono }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.cc') }}
                        </th>
                        <td>
                            {{ $embarcador->cc }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.p_sag_dir') }}
                        </th>
                        <td>
                            {{ $embarcador->p_sag_dir }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.embarcador.fields.g_dir_a') }}
                        </th>
                        <td>
                            {{ $embarcador->g_dir_a }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.embarcadors.index') }}">
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
            <a class="nav-link" href="#embarcador_instructivo_embarques" role="tab" data-toggle="tab">
                {{ trans('cruds.instructivoEmbarque.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="embarcador_instructivo_embarques">
            @includeIf('admin.embarcadors.relationships.embarcadorInstructivoEmbarques', ['instructivoEmbarques' => $embarcador->embarcadorInstructivoEmbarques])
        </div>
    </div>
</div>

@endsection