@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <h1>Planificador de Personal</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Planificación Diaria
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.planificador-personal.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha:</label>
                    <input type="text" class="form-control" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="planificacionTable">
                        <thead>
                            <tr>
                                <th>Locación / Turno</th>
                                @foreach ($turnos as $turno)
                                    <th data-turno-id="{{ $turno->id }}">{{ $turno->nombre }}</th>
                                @endforeach
                                <th>Total por Locación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locaciones as $locacion)
                                <tr data-locacion-id="{{ $locacion->id }}">
                                    <td>{{ $locacion->nombre }}</td>
                                    @foreach ($turnos as $turno)
                                        <td>
                                            <input type="number"
                                                   name="planificacion[{{ $locacion->id }}][{{ $turno->id }}][cantidad_personal_planificada]"
                                                   class="form-control form-control-sm planificacion-input"
                                                   value="0"
                                                   min="0"
                                                   data-locacion-id="{{ $locacion->id }}"
                                                   data-turno-id="{{ $turno->id }}">
                                            <input type="hidden"
                                                   name="planificacion[{{ $locacion->id }}][{{ $turno->id }}][locacion_id]"
                                                   value="{{ $locacion->id }}">
                                            <input type="hidden"
                                                   name="planificacion[{{ $locacion->id }}][{{ $turno->id }}][turno_id]"
                                                   value="{{ $turno->id }}">
                                        </td>
                                    @endforeach
                                    <td><span class="total-locacion" id="total-locacion-{{ $locacion->id }}">0</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total por Turno</th>
                                @foreach ($turnos as $turno)
                                    <th><span class="total-turno" id="total-turno-{{ $turno->id }}">0</span></th>
                                @endforeach
                                <th><span id="total-general">0</span></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Planificación</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
        
    <script>
        $('#fecha').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false,
            showClear: true,
            showTodayButton: true,
            icons: {
                next: 'fa fa-chevron-right',
                previous: 'fa fa-chevron-left',
                today: 'fa fa-calendar-check-o',
                clear: 'fa fa-trash',
            }
        }).on('dp.change', function(e) {
            if (e.date) {
                loadPlanificacion(e.date.format('YYYY-MM-DD'));
            }
        });

        function loadPlanificacion(date) {
            $.ajax({
                url: '{{ route('admin.planificador-personal.data') }}',
                method: 'GET',
                data: { fecha: date },
                success: function(response) {
                    $('input[name^="planificacion"]').val(0);

                    response.forEach(function(plan) {
                        const inputName = `planificacion[${plan.locacion_id}][${plan.turno_id}][cantidad_personal_planificada]`;
                        $(`input[name="${inputName}"]`).val(plan.cantidad_personal_planificada);
                    });
                    updateTotals(); // Call updateTotals after loading data
                },
                error: function(xhr, status, error) {
                    console.error("Error loading planning data:", error);
                    $('input[name^="planificacion"]').val(0);
                    updateTotals(); // Call updateTotals even on error to reset totals
                }
            });
        }

        function updateTotals() {
            let grandTotal = 0;
            let columnTotals = {};

            // Initialize column totals
            $('#planificacionTable thead th[data-turno-id]').each(function() {
                const turnoId = $(this).data('turno-id');
                columnTotals[turnoId] = 0;
            });

            // Update Row Totals (Total por Locación) and accumulate for column totals
            $('#planificacionTable tbody tr').each(function() {
                let rowTotal = 0;
                $(this).find('.planificacion-input').each(function() {
                    const value = parseInt($(this).val()) || 0;
                    const locacionId = $(this).data('locacion-id');
                    const turnoId = $(this).data('turno-id');

                    rowTotal += value;
                    columnTotals[turnoId] += value;
                });
                $(this).find('.total-locacion').text(rowTotal);
                grandTotal += rowTotal;
            });

            // Update Column Totals (Total por Turno)
            for (const turnoId in columnTotals) {
                $('#total-turno-' + turnoId).text(columnTotals[turnoId]);
            }

            // Update Grand Total
            $('#total-general').text(grandTotal);
        }

        // Attach event listener to input fields
        $(document).on('change', '.planificacion-input', function() {
            updateTotals();
        });

        const initialDate = document.getElementById('fecha').value;
        if (initialDate) {
            loadPlanificacion(initialDate);
        }
    </script>
@endsection