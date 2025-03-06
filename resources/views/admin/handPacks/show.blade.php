@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.handPack.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.hand-packs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.handPack.fields.id') }}
                        </th>
                        <td>
                            {{ $handPack->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.handPack.fields.rut') }}
                        </th>
                        <td>
                            {{ $handPack->rut }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.handPack.fields.fecha') }}
                        </th>
                        <td>
                            {{ $handPack->fecha }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.handPack.fields.embalaje') }}
                        </th>
                        <td>
                            {{ App\Models\HandPack::EMBALAJE_SELECT[$handPack->embalaje] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.handPack.fields.guuid') }}
                        </th>
                        <td>
                            {{ $handPack->guuid }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.hand-packs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection