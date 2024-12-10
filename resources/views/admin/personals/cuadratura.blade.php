@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Reloj Control
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.personals.ejecutaCuadratura") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="fecha">Fecha a Cuadrar</label>
                <input type="text" id="fecha" class="form-control date" name="fecha" value=""/>
            </div>
            <label for="file">Selecciona archivo</label>
            <input type="file" name="file" id="file" required>
            <button type="submit">Subir Archivo</button>
        </form>
    </div>
</div>
@endsection
