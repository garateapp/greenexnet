@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Asignar Ubicacion a Supervisor
        </div>

        <div class="card-body">
            <form action="{{ route('admin.personals.assignLocationStore') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="supervisor_id">Supervisor</label>
                    <select class="form-control select2" name="supervisor_id" id="supervisor_id" required>
                        <option value="">Seleccione un supervisor</option>
                        @foreach($supervisors as $id => $name)
                            <option value="{{ $id }}" {{ (old('supervisor_id') ?? ($selectedSupervisor->id ?? '')) == $id ? 'selected' : '' }}>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="location_id">Ubicacion Padre</label>
                    <select class="form-control select2" name="location_id" id="location_id" required>
                        <option value="">Seleccione una ubicacion</option>
                        @foreach($parentLocations as $id => $name)
                            <option value="{{ $id }}" {{ (old('location_id') ?? ($selectedLocation ?? '')) == $id ? 'selected' : '' }}>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-success" type="submit">
                        Asignar Ubicacion
                    </button>
                    <a href="{{ route('admin.attendance.offline') }}" target="_blank" class="btn btn-secondary ml-2">
                        Trabajar sin Conexion
                    </a>
                </div>
            </form>

            <hr>

            <div class="card">
                <div class="card-header">
                    Asignaciones Existentes
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-assigned-locations">
                            <thead>
                                <tr>
                                    <th>Supervisor</th>
                                    <th>Ubicacion Asignada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignedSupervisors as $supervisor)
                                    <tr>
                                        <td>{{ $supervisor->nombre ?? '' }}</td>
                                        <td>{{ $supervisor->assignedLocation->nombre ?? 'N/A' }}</td>
                                        <td>
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.personals.assignLocationForm', ['id' => $supervisor->id]) }}">
                                                Editar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('.datatable-assigned-locations').DataTable({
            order: [[ 0, 'asc' ]],
            pageLength: 10,
        });
    });
</script>
@endsection
