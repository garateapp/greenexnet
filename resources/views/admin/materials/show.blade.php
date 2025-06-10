@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.material.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.materials.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.material.fields.id') }}
                        </th>
                        <td>
                            {{ $material->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.material.fields.codigo') }}
                        </th>
                        <td>
                            {{ $material->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.material.fields.nombre') }}
                        </th>
                        <td>
                            {{ $material->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.material.fields.unidad') }}
                        </th>
                        <td>
                            {{ App\Models\Material::UNIDAD_SELECT[$material->unidad] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.material.fields.costo_ult_oc') }}
                        </th>
                        <td>
                            {{ $material->costo_ult_oc }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.materials.index') }}">
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
            <a class="nav-link" href="#material_material_productos" role="tab" data-toggle="tab">
                {{ trans('cruds.materialProducto.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="material_material_productos">
            @includeIf('admin.materials.relationships.materialMaterialProductos', ['materialProductos' => $material->materialMaterialProductos])
        </div>
    </div>
</div>

@endsection