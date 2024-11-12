@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.personal.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.personals.store') }}" enctype="multipart/form-data">
                @csrf
                <button id="start-camera" type="button"
                    style="border-radius: 15px; width: 30px;height: 30px; background-color: #ff7313; border: none;">
                    <i class="fa-fw fas fa-camera" style="color: white;"></i></button>
                <video id="video" width="320" height="240" autoplay></video>
                <button id="click-photo" type="button">Sacar Foto</button>
                <canvas id="canvas" width="320" height="240"></canvas>
                <input type="hidden" name="foto" id="foto">
                <div class="form-group">
                    <label class="required" for="nombre">{{ trans('cruds.personal.fields.nombre') }}</label>
                    <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text"
                        name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                    @if ($errors->has('nombre'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nombre') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.personal.fields.nombre_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="codigo">{{ trans('cruds.personal.fields.codigo') }}</label>
                    <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" type="text"
                        name="codigo" id="codigo" value="{{ old('codigo', '') }}">
                    @if ($errors->has('codigo'))
                        <div class="invalid-feedback">
                            {{ $errors->first('codigo') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.personal.fields.codigo_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="rut">{{ trans('cruds.personal.fields.rut') }}</label>
                    <input class="form-control {{ $errors->has('rut') ? 'is-invalid' : '' }}" type="text" name="rut"
                        id="rut" value="{{ old('rut', '') }}" required>
                    @if ($errors->has('rut'))
                        <div class="invalid-feedback">
                            {{ $errors->first('rut') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.personal.fields.rut_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="email">{{ trans('cruds.personal.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text"
                        name="email" id="email" value="{{ old('email', '') }}">
                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.personal.fields.email_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="telefono">{{ trans('cruds.personal.fields.telefono') }}</label>
                    <input class="form-control {{ $errors->has('telefono') ? 'is-invalid' : '' }}" type="text"
                        name="telefono" id="telefono" value="{{ old('telefono', '') }}">
                    @if ($errors->has('telefono'))
                        <div class="invalid-feedback">
                            {{ $errors->first('telefono') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.personal.fields.telefono_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="cargo_id">{{ trans('cruds.personal.fields.cargo') }}</label>
                    <select class="form-control select2 {{ $errors->has('cargo') ? 'is-invalid' : '' }}" name="cargo_id"
                        id="cargo_id">
                        @foreach ($cargos as $id => $entry)
                            <option value="{{ $id }}" {{ old('cargo_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('cargo'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cargo') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.personal.fields.cargo_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="estado_id">{{ trans('cruds.personal.fields.estado') }}</label>
                    <select class="form-control select2 {{ $errors->has('estado') ? 'is-invalid' : '' }}" name="estado_id"
                        id="estado_id" required>
                        @foreach ($estados as $id => $entry)
                            <option value="{{ $id }}" {{ old('estado_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('estado'))
                        <div class="invalid-feedback">
                            {{ $errors->first('estado') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.personal.fields.estado_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required" for="entidad_id">{{ trans('cruds.personal.fields.entidad') }}</label>
                    <select class="form-control select2 {{ $errors->has('entidad') ? 'is-invalid' : '' }}"
                        name="entidad_id" id="entidad_id" required>
                        @foreach ($entidads as $id => $entry)
                            <option value="{{ $id }}" {{ old('entidad_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('entidad'))
                        <div class="invalid-feedback">
                            {{ $errors->first('entidad') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.personal.fields.entidad_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(function(stream) {
                    // Aquí puedes manejar el stream de la cámara
                    console.log("Acceso a la cámara concedido");
                })
                .catch(function(error) {
                    console.error("Error al acceder a la cámara:", error);
                });
        } else {
            console.error("getUserMedia no está disponible en este navegador.");
        }
        let camera_button = document.querySelector("#start-camera");
        let video = document.querySelector("#video");
        let click_button = document.querySelector("#click-photo");
        let canvas = document.querySelector("#canvas");

        camera_button.addEventListener('click', async function() {
            let stream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            });
            video.srcObject = stream;
        });

        click_button.addEventListener('click', function() {
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            let image_data_url = canvas.toDataURL('image/jpeg');

            // data url of the image
            let foto = document.getElementById('foto');
            foto.value = image_data_url;
            console.log(image_data_url);
        });
    </script>
@endsection
