@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Registro de Entradas y Salidas - Líneas de Embalaje</span>
            <a href="#" id="exportButton" class="btn btn-success btn-sm">
                Exportar CSV
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="start_date">Fecha inicio</label>
                    <input type="date" id="start_date" class="form-control" value="{{ $defaultStart }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date">Fecha fin</label>
                    <input type="date" id="end_date" class="form-control" value="{{ $defaultEnd }}">
                </div>
                <div class="col-md-3">
                    <label for="name_search">Nombre</label>
                    <input type="text" id="name_search" class="form-control" placeholder="Buscar por nombre">
                </div>
                <div class="col-md-3">
                    <label for="rut_search">RUT</label>
                    <input type="text" id="rut_search" class="form-control" placeholder="Buscar por RUT">
                </div>
            </div>
            <div class="mb-3">
                <button class="btn btn-primary btn-sm" id="applyFilters">Aplicar filtros</button>
                <button class="btn btn-secondary btn-sm" id="clearFilters">Limpiar</button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="packingAttendanceTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nombre</th>
                            <th>RUT</th>
                            <th>Ubicación</th>
                            <th>Fecha Salida</th>
                            <th>Fecha Entrada</th>
                            <th>Minutos</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        const table = $('#packingAttendanceTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('admin.packing-line-attendances.list') }}",
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.search = $('#name_search').val();
                    d.rut = $('#rut_search').val();
                }
            },
            columns: [
                { data: 'personal_name', name: 'personal_name' },
                { data: 'personal_rut', name: 'personal_rut' },
                { data: 'location', name: 'location' },
                { data: 'fecha_hora_salida', name: 'fecha_hora_salida' },
                { data: 'fecha_hora_entrada', name: 'fecha_hora_entrada' },
                { data: 'minutos', name: 'minutos' },
            ]
        });

        $('#applyFilters').on('click', function () {
            table.ajax.reload();
        });

        $('#clearFilters').on('click', function () {
            $('#start_date').val('{{ $defaultStart }}');
            $('#end_date').val('{{ $defaultEnd }}');
            $('#name_search').val('');
            $('#rut_search').val('');
            table.ajax.reload();
        });

        $('#exportButton').on('click', function (e) {
            e.preventDefault();
            const params = new URLSearchParams({
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                search: $('#name_search').val(),
                rut: $('#rut_search').val(),
            });
            window.location.href = "{{ route('admin.packing-line-attendances.export') }}?" + params.toString();
        });
    });
</script>
@endsection
