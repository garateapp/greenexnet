@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Confirmar Asistencia
        </div>

        <div class="card-body">
            <form id="attendanceForm">
                @csrf
                <div class="form-group">
                    <label for="person_name">Nombre del Personal</label>
                    <input type="text" class="form-control" id="person_name" value="{{ $person->nombre ?? 'N/A' }}" readonly>
                    <input type="hidden" name="person_id" id="person_id" value="{{ $person->id ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="person_rut">RUT del Personal</label>
                    <input type="text" class="form-control" id="person_rut" value="{{ $person->rut ?? 'N/A' }}" readonly>
                </div>

                <div class="form-group">
                    <label for="location">Ubicación</label>
                    <input type="text" class="form-control" name="location" id="location" placeholder="Ingrese la ubicación" required>
                </div>

                <div class="form-group">
                    <label for="timestamp">Fecha y Hora</label>
                    <input type="text" class="form-control" id="timestamp" value="{{ now()->format('d-m-Y H:i:s') }}" readonly>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success" id="confirmAttendanceBtn">Confirmar Asistencia</button>
                </div>
            </form>

            <div id="responseMessage" class="mt-3"></div>
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('#attendanceForm').on('submit', function(e) {
            e.preventDefault();

            let personId = $('#person_id').val();
            let location = $('#location').val();
            let _token = $('input[name="_token"]').val();

            if (!personId || !location) {
                $('#responseMessage').html('<div class="alert alert-danger">Por favor, complete todos los campos.</div>');
                return;
            }

            $.ajax({
                url: "{{ route('admin.attendance.store') }}", // This route needs to be defined
                type: "POST",
                data: {
                    person_id: personId,
                    location: location,
                    _token: _token
                },
                success: function(response) {
                    if (response.success) {
                        $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                        $('#attendanceForm').hide(); // Hide form on success
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
    });
</script>
@endsection
