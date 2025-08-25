@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.biReport.title_singular') }} {{ trans('global.list') }}
        @can('manage_bi_reports')
            <a class="btn btn-success float-right" href="{{ route('admin.bi-reports.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.biReport.title_singular') }}
            </a>
        @endcan
    </div>

    <div class="card-body">
        <div class="row justify-content-center"> {{-- Center the cards --}}
            @php
                $icons = [
                    'fas fa-chart-pie', 'fas fa-chart-bar', 'fas fa-chart-line', 'fas fa-chart-area',
                    'fas fa-globe', 'fas fa-database', 'fas fa-cogs', 'fas fa-tachometer-alt',
                    'fas fa-project-diagram', 'fas fa-lightbulb', 'fas fa-cloud', 'fas fa-code',
                    'fas fa-flask', 'fas fa-rocket', 'fas fa-puzzle-piece', 'fas fa-fingerprint',
                    'fas fa-brain', 'fas fa-robot', 'fas fa-microchip', 'fas fa-server'
                ];
            @endphp
            @forelse($biReports as $biReport)
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4 d-flex align-items-stretch"> {{-- Adjust column sizes for better grid --}}
                    <div class="card h-100 shadow-sm text-center d-flex flex-column justify-content-between">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <a href="{{ route('admin.bi-reports.viewExternal', $biReport->id) }}" target="_blank" class="btn btn-primary btn-square-icon rounded-circle mb-3 d-flex align-items-center justify-content-center" style="text-align: center">
                                <i class="fas fa-file"  style="margin-top: -18px; margin-right: 10px;"></i>
                            </a>
                            <h5 class="card-title text-primary mt-2">{{ $biReport->name ?? 'Reporte sin nombre' }}</h5>
                        </div>
                        @can('manage_bi_reports')
                            <div class="card-footer bg-transparent border-top-0 pt-0 pb-2">
                                <div class="d-flex justify-content-center"> {{-- Center action buttons --}}
                                    <a class="btn btn-xs btn-outline-primary mr-1" href="{{ route('admin.bi-reports.edit', $biReport->id) }}" style="text-align: center">
                                        <i class="fas fa-edit"></i> {{-- trans('global.edit') --}}
                                    </a>
                                    <form action="{{ route('admin.bi-reports.destroy', $biReport->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-outline-danger ml-1">
                                            <i class="fas fa-trash"></i> {{-- trans('global.delete') --}}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        No hay reportes de BI disponibles.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .btn-square-icon {
        width: 80px; /* Adjust size as needed */
        height: 80px; /* Adjust size as needed */
        padding: 0;
        font-size: 2rem; /* Adjust icon size */
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%; /* Make it circular */
        background-color: #a9dd94; /* Primary color from your theme */
        border-color: #a9dd94;
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        transition: all 0.3s ease;
    }
    .btn-square-icon:hover {
        background-color: #81b940; /* Darker shade on hover */
        border-color: #81b940;
        color: white;
        transform: translateY(-3px) scale(1.05); /* Lift and slightly enlarge on hover */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
    }
    .card-title {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .card-body {
        padding-bottom: 0.5rem; /* Reduce padding below title */
    }
    .card-footer {
        padding-top: 0.5rem; /* Adjust padding above buttons */
    }
</style>
@endsection
@section('scripts')
@parent
@endsection
