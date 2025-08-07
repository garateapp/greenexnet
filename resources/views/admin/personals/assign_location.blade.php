@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Asignar Ubicación a Supervisor
        </div>

        <div class="card-body">
            <form action="{{ route('admin.personals.assignLocationStore') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="supervisor_id">Supervisor</label>
                    <select class="form-control select2" name="supervisor_id" id="supervisor_id" required>
                        <option value="">Seleccione un supervisor</option>
                        @foreach($supervisors as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="location_id">Ubicación Padre</label>
                    <select class="form-control select2" name="location_id" id="location_id" required>
                        <option value="">Seleccione una ubicación</option>
                        @foreach($parentLocations as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-success" type="submit">
                        Asignar Ubicación
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
