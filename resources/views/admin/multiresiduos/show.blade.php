@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.multiresiduo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.multiresiduos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.multiresiduo.fields.id') }}
                        </th>
                        <td>
                            {{ $multiresiduo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.multiresiduo.fields.productor') }}
                        </th>
                        <td>
                            {{ $multiresiduo->productor->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.multiresiduo.fields.valor') }}
                        </th>
                        <td>
                            {{ $multiresiduo->valor }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.multiresiduos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection