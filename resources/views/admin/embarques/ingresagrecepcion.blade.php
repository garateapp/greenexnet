@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
    Ingreso Pallet List
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.embarques.ingresaPackingList") }}" enctype="multipart/form-data">
            @csrf

            <label for="id_adm_p_entidades_packing">Packing</label>
            <select name="id_adm_p_entidades_packing" id="id_adm_p_entidades_packing" class="form-control select2" required>
                <option value="">Selecciona packing</option>
                @foreach ($packings as $packing)
                    <option value="{{ $packing->id }}">
                        {{ $packing->nombre }}
                    </option>
                @endforeach
            </select>

            <label for="file">Selecciona archivo</label>
            <input type="file" name="file" id="file" required>
            <button type="submit">Subir Archivo</button>
        </form>
    </div>
</div>
@endsection
