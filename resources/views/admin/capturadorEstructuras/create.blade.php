@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.capturadorEstructura.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.capturador-estructuras.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="capturador_id">{{ trans('cruds.capturadorEstructura.fields.capturador') }}</label>
                <select class="form-control select2 {{ $errors->has('capturador') ? 'is-invalid' : '' }}" name="capturador_id" id="capturador_id" required>
                    @foreach($capturadors as $id => $entry)
                        <option value="{{ $id }}" {{ old('capturador_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('capturador'))
                    <div class="invalid-feedback">
                        {{ $errors->first('capturador') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturadorEstructura.fields.capturador_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tipos_seccion_conversors_id">Tipo de Sección</label>
                <select class="form-control select2 {{ $errors->has('tipos_seccion_conversors_id') ? 'is-invalid' : '' }}" name="tipos_seccion_conversors_id" id="tipos_seccion_conversors_id" required>
                    @foreach($tipos_seccion_conversors as $id => $entry)
                        <option value="{{ $id }}" {{ old('tipos_seccion_conversors_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('capturador'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipos_seccion_conversors') }}
                    </div>
                @endif
                <span class="help-block"></span>
            </div>
            <div class="form-group" id="costosDiv" style="display: none;">
                <label class="required" for="costos">Costo</label>
                <select class="form-control select2 {{ $errors->has('costos') ? 'is-invalid' : '' }}" name="costos" id="costos" style="display: none;">
                    @foreach($costos as $id => $entry)
                        <option value="{{ $id }}" {{ old('costos') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('costos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('costos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturadorEstructura.fields.capturador_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="propiedad">{{ trans('cruds.capturadorEstructura.fields.propiedad') }}</label>
                <input class="form-control {{ $errors->has('propiedad') ? 'is-invalid' : '' }}" type="text" name="propiedad" id="propiedad" value="{{ old('propiedad', '') }}" required>
                @if($errors->has('propiedad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('propiedad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturadorEstructura.fields.propiedad_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="coordenada">{{ trans('cruds.capturadorEstructura.fields.coordenada') }}</label>
                <input class="form-control {{ $errors->has('coordenada') ? 'is-invalid' : '' }}" type="text" name="coordenada" id="coordenada" value="{{ old('coordenada', '') }}" required>
                @if($errors->has('coordenada'))
                    <div class="invalid-feedback">
                        {{ $errors->first('coordenada') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturadorEstructura.fields.coordenada_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="orden">{{ trans('cruds.capturadorEstructura.fields.orden') }}</label>
                <input class="form-control {{ $errors->has('orden') ? 'is-invalid' : '' }}" type="number" name="orden" id="orden" value="{{ old('orden', '') }}" step="1">
                @if($errors->has('orden'))
                    <div class="invalid-feedback">
                        {{ $errors->first('orden') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturadorEstructura.fields.orden_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('visible') ? 'is-invalid' : '' }}">
                    <input class="form-check-input" type="checkbox" name="visible" id="visible" value="1" required {{ old('visible', 0) == 1 || old('visible') === null ? 'checked' : '' }}>
                    <label class="required form-check-label" for="visible">{{ trans('cruds.capturadorEstructura.fields.visible') }}</label>
                </div>
                @if($errors->has('visible'))
                    <div class="invalid-feedback">
                        {{ $errors->first('visible') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturadorEstructura.fields.visible_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="formula">{{ trans('cruds.capturadorEstructura.fields.formula') }}</label>
                <input class="form-control {{ $errors->has('formula') ? 'is-invalid' : '' }}" type="text" name="formula" id="formula" value="{{ old('formula', '') }}">
                @if($errors->has('formula'))
                    <div class="invalid-feedback">
                        {{ $errors->first('formula') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.capturadorEstructura.fields.formula_helper') }}</span>
            </div>
            
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        $("#tipos_seccion_conversors_id").on("change", function() {
            var id = $(this).val();
            if(id==3){
                $("#costosDiv").show();
                $("#costos").attr("required", "required");
                $("#propiedad").attr("disabled", "disabled");

            }
            else{
                $("#costosDiv").hide();
                $("#costos").removeAttr("required");
                $("#propiedad").removeAttr("disabled");
            }
        });
        $("#costos").on("change", function() {
            var id = $(this).val();
            if(id!=""){
                $("#propiedad").val(id);
            }
            
        });

    });
    </script>

@endsection