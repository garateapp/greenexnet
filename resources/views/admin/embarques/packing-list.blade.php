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
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.embarques.packingList') }}" class="mb-4" id="packingListFilters">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">Destinatario</label>
                        <select name="destinatario" id="destinatario" class="form-control select2 js-packing-filter"
                            data-filter-key="destinatario">
                            <option value="">Todos</option>
                            @foreach ($filterOptions['destinatarios'] as $option)
                                <option value="{{ $option }}" @selected($filters['destinatario'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">Embalaje</label>
                        <select name="embalaje" id="embalaje" class="form-control select2 js-packing-filter"
                            data-filter-key="embalaje">
                            <option value="">Todos</option>
                            @foreach ($filterOptions['embalajes'] as $option)
                                <option value="{{ $option }}" @selected($filters['embalaje'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">País destino</label>
                        <select name="pais_destino" id="pais_destino"
                            class="form-control select2 js-packing-filter" data-filter-key="pais_destino">
                            <option value="">Todos</option>
                            @foreach ($filterOptions['paises'] as $option)
                                <option value="{{ $option }}" @selected($filters['pais_destino'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">Nave</label>
                        <select name="nave" id="nave" class="form-control select2 js-packing-filter" data-filter-key="nave">
                            <option value="">Todas</option>
                            @foreach ($filterOptions['naves'] as $option)
                                <option value="{{ $option }}" @selected($filters['nave'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">Contenedor</label>
                        <select name="contenedor" id="contenedor" class="form-control select2 js-packing-filter"
                            data-filter-key="contenedor">
                            <option value="">Todos</option>
                            @foreach ($filterOptions['contenedores'] as $option)
                                <option value="{{ $option }}" @selected($filters['contenedor'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold">Número de embarque</label>
                        <select name="num_embarque" id="num_embarque" class="form-control select2 js-packing-filter"
                            data-filter-key="num_embarque">
                            <option value="">Todos</option>
                            @foreach ($filterOptions['numeros_embarque'] as $option)
                                <option value="{{ $option }}" @selected($filters['num_embarque'] === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="resetFiltersButton">
                            Limpiar filtros
                        </button>
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

@section('scripts')
    <script>
        $(function() {
            const dependencyData = @json($filterDependencies);
            const filterKeys = ['destinatario', 'embalaje', 'pais_destino', 'nave', 'contenedor', 'num_embarque'];
            const selectors = filterKeys.reduce((acc, key) => {
                acc[key] = $('[data-filter-key="' + key + '"]');
                return acc;
            }, {});
            const placeholders = {};
            const $filtersForm = $('#packingListFilters');

            Object.entries(selectors).forEach(([key, $select]) => {
                const placeholderText = ($select.find('option[value=""]').first().text() || 'Todos').trim();
                placeholders[key] = placeholderText;
                $select.select2({
                    width: '100%',
                    placeholder: placeholderText,
                    allowClear: true
                });
            });

            function buildActiveSelections(excludeKey) {
                const selections = {};
                filterKeys.forEach(key => {
                    if (key === excludeKey) {
                        return;
                    }
                    const value = selectors[key].val();
                    if (value) {
                        selections[key] = value;
                    }
                });
                return selections;
            }

            function computeOptions(targetKey) {
                const activeSelections = buildActiveSelections(targetKey);
                const values = new Set();

                dependencyData.forEach(item => {
                    let matches = true;

                    for (const [key, value] of Object.entries(activeSelections)) {
                        const datasetValue = item[key];
                        const normalizedDatasetValue = datasetValue === null || datasetValue === undefined ?
                            null : datasetValue.toString();
                        if (normalizedDatasetValue !== value) {
                            matches = false;
                            break;
                        }
                    }

                    const itemValue = item[targetKey];
                    const normalizedValue = itemValue === null || itemValue === undefined ? null : itemValue
                        .toString();

                    if (matches && normalizedValue !== null && normalizedValue !== '') {
                        values.add(normalizedValue);
                    }
                });

                return Array.from(values).sort((a, b) => a.toString()
                    .localeCompare(b.toString(), undefined, {
                        numeric: true,
                        sensitivity: 'base'
                    }));
            }

            function refreshSelectOptions(changedKey) {
                if (!dependencyData.length) {
                    return;
                }

                filterKeys.forEach(key => {
                    const $select = selectors[key];
                    const currentValue = $select.val();
                    const options = computeOptions(key);

                    const shouldKeep = currentValue && options.includes(currentValue);

                    $select.find('option').filter(function() {
                        return $(this).val() !== '';
                    }).remove();

                    options.forEach(value => {
                        $select.append(new Option(value, value, false, false));
                    });

                    if (shouldKeep) {
                        $select.val(currentValue);
                    } else {
                        $select.val('');
                    }

                    $select.trigger('change.select2');
                });
            }

            refreshSelectOptions();

            filterKeys.forEach(key => {
                selectors[key].on('change', function() {
                    refreshSelectOptions(key);
                });
            });

            $('#resetFiltersButton').on('click', function() {
                filterKeys.forEach(key => {
                    selectors[key].val('').trigger('change.select2');
                });

                if ($filtersForm.length) {
                    $filtersForm.trigger('submit');
                }
            });
        });
    </script>
@endsection
