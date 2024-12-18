@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
    Ingreso Pallet List
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.embarques.ingresaPackingList") }}" enctype="multipart/form-data">
            @csrf

            <label for="file">Selecciona archivo</label>
            <input type="file" name="file" id="file" required>
            <button type="submit">Subir Archivo</button>
        </form>
    </div>
</div>
@endsection
