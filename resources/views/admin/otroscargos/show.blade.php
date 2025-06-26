@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.otroscargo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.otroscargos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.otroscargo.fields.id') }}
                        </th>
                        <td>
                            {{ $otroscargo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.otroscargo.fields.productor') }}
                        </th>
                        <td>
                            {{ $otroscargo->productor->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.otroscargo.fields.valor') }}
                        </th>
                        <td>
                            {{ $otroscargo->valor }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.otroscargos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection