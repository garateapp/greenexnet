@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Confirmar Asistencia
        </div>

        <div class="card-body">
            @if(isset($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @endif

            @if(isset($person) && $person)
                <form id="attendanceForm">
                    @csrf
                    <input type="hidden" name="person_id" id="person_id" value="{{ $person->id ?? '' }}">
                    <input type="hidden" name="entry_type" id="entry_type" value="{{ $source ?? 'automatico' }}">
                    <input type="hidden" name="mode" id="form_mode" value="{{ $packingMode ?? 'default' }}">

                    <div class="form-group">
                        <label for="person_name">Nombre del Personal</label>
                        <input type="text" class="form-control" id="person_name" value="{{ $person->nombre ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="person_rut">RUT del Personal</label>
                        <input type="text" class="form-control" id="person_rut" value="{{ $person->rut ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="location">
                            {{ $packingMode === 'packing' ? 'Línea de Embalaje' : 'Ubicación' }}
                        </label>
                        <input type="text"
                               class="form-control"
                               name="location"
                               id="location"
                               value="{{ $supervisorLocation ?? '' }}"
                               readonly>
                    </div>

                    <div class="form-group">
                        <label for="timestamp">
                            @if ($packingMode === 'packing')
                                {{ $nextAction === 'entrada' ? 'Registrar Entrada (Fecha y Hora)' : 'Registrar Salida (Fecha y Hora)' }}
                            @else
                                Fecha y Hora
                            @endif
                        </label>
                        <input type="text" class="form-control" id="timestamp" value="{{ now()->format('d-m-Y H:i:s') }}" readonly>
                    </div>

                    <div class="form-group">
                        <button type="submit"
                                class="btn {{ $packingMode === 'packing' ? ($nextAction === 'entrada' ? 'btn-primary' : 'btn-warning') : 'btn-success' }}"
                                id="confirmAttendanceBtn">
                            @if ($packingMode === 'packing')
                                {{ $nextAction === 'entrada' ? 'Registrar Entrada' : 'Registrar Salida' }}
                            @else
                                Confirmar Asistencia
                            @endif
                        </button>
                    </div>
                </form>
                <div id="responseMessage" class="mt-3"></div>
            @else
                <p>No se ha especificado una persona. Por favor, ingrese el RUT para buscar.</p>
                <form method="POST" action="{{ route('admin.attendance.find_person') }}">
                    @csrf
                    <div class="form-group">
                        <label for="rut">RUT del Personal</label>
                        <input type="text" class="form-control" name="rut" id="rut" required placeholder="Ej: 12345678-9">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Buscar Persona</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        if ($('#attendanceForm').length) {
            $('#attendanceForm').on('submit', function(e) {
                e.preventDefault();

                let personId = $('#person_id').val();
                let location = $('#location').val();
                let entryType = $('#entry_type').val();
                let mode = $('#form_mode').val();
                let _token = $('input[name="_token"]').val();
                let fecha_hora=date('Y-m-d H:i:s');

                if (!personId || !location) {
                    $('#responseMessage').html('<div class="alert alert-danger">Por favor, complete todos los campos.</div>');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.attendance.store') }}",
                    type: "POST",
                    data: {
                        person_id: personId,
                        location: location,
                        entry_type: entryType,
                        mode: mode,
                        fecha:fecha_hora,
                        _token: _token
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                            $('#attendanceForm').hide();
                            setTimeout(function() {
                                window.close();
                            }, 3000);
                        } else {
                            $('#responseMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error al confirmar asistencia.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('#responseMessage').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                        console.error(xhr.responseText);
                    }
                });
            });
        }
    });
</script>
@endsection
