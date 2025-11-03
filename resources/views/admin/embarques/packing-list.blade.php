@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>Packing List</span>
            <div class="d-flex gap-2">
                @php($exportParams = array_filter($filters))
                <a href="{{ route('admin.embarques.packingListExport', $exportParams) }}" class="btn btn-success btn-sm">
                    Exportar a Excel
                </a>
                <a href="{{ route('admin.embarques.packingList') }}" class="btn btn-secondary btn-sm">
                    Limpiar filtros
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.embarques.packingList') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">Destinatario</label>
                        <select name="destinatario" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($filterOptions['destinatarios'] as $option)
                                <option value="{{ $option }}" @selected($filters['destinatario'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">Embalaje</label>
                        <select name="embalaje" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($filterOptions['embalajes'] as $option)
                                <option value="{{ $option }}" @selected($filters['embalaje'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">País destino</label>
                        <select name="pais_destino" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($filterOptions['paises'] as $option)
                                <option value="{{ $option }}" @selected($filters['pais_destino'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">Nave</label>
                        <select name="nave" class="form-control">
                            <option value="">Todas</option>
                            @foreach ($filterOptions['naves'] as $option)
                                <option value="{{ $option }}" @selected($filters['nave'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">Contenedor</label>
                        <select name="contenedor" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($filterOptions['contenedores'] as $option)
                                <option value="{{ $option }}" @selected($filters['contenedor'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            Aplicar filtros
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Transporte</th>
                            <th>Fecha despacho</th>
                            <th>N° embarque</th>
                            <th>Destinatario</th>
                            <th>Packing origen</th>

                            <th>Etiqueta</th>
                            <th>Tipo embalaje</th>
                            <th>Peso std embalaje</th>
                            <th>Especie</th>
                            <th>Variedad</th>
                            <th>Categoría</th>

                            <th>Calibre</th>
                            <th>Cantidad</th>
                            <th>Contenedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($embarques as $embarque)
                            <tr>
                                <td>{{ $embarque->transporte }}</td>
                                <td>{{ optional($embarque->fecha_despacho)->format('d-m-Y') }}</td>
                                <td>{{ $embarque->num_embarque }}</td>
                                <td>
                                    <div>{{ $embarque->c_destinatario }}</div>
                                    <small class="text-muted">{{ $embarque->n_cliente }}</small>
                                </td>
                                <td>{{ $embarque->c_packing_origen }}</td>

                                <td>{{ $embarque->etiqueta }}</td>
                                <td>{{ $embarque->t_embalaje }}</td>
                                <td>{{ number_format((float) $embarque->peso_std_embalaje, 2, ',', '.') }}</td>
                                <td>{{ $embarque->especie }}</td>
                                <td>{{ $embarque->variedad }}</td>
                                <td>{{ $embarque->n_categoria }}</td>

                                <td>{{ $embarque->n_calibre }}</td>
                                <td>{{ number_format((float) $embarque->cantidad, 0, ',', '.') }}</td>
                                <td>{{ $embarque->num_contenedor }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="19" class="text-center text-muted">No se encontraron registros con los filtros aplicados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
