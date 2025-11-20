<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            <img src="{{ asset('img/logo.webp') }}" style="width: 170px !important;height: 60px !important;" />
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li>
            <select class="searchable-field form-control">

            </select>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.home') }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.control-access.dashboard') }}"
                class="c-sidebar-nav-link {{ request()->is('admin/control-access/dashboard') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-user-clock"></i>
                Control Acceso
            </a>
        </li>
        @can('control_panel')
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.liq-cx-cabeceras.selprods') }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-chart-pie">

                </i>
               Graficos Liquidaciones
            </a>

        </li>

        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.liq-cx-cabeceras.comparativoliquidaciones') }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-chart-bar">

                </i>
               Comparativo Liquidaciones
            </a>
        @endcan
        @can('user_management_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/permissions*') ? 'c-show' : '' }} {{ request()->is('admin/roles*') ? 'c-show' : '' }} {{ request()->is('admin/users*') ? 'c-show' : '' }} {{ request()->is('admin/estados*') ? 'c-show' : '' }} {{ request()->is('admin/audit-logs*') ? 'c-show' : '' }} {{ request()->is('admin/configuracions*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-user-shield c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.permissions.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.roles.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('estado_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.estados.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/estados') || request()->is('admin/estados/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.estado.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.audit-logs.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan

        @can('datos_caja_calidad_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/datos-cajas*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-prescription-bottle c-sidebar-nav-icon">

                    </i>
                    Calidad
                </a>
                <ul class="c-sidebar-nav-dropdown-items">

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.datos-cajas.index') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/datos-cajas') || request()->is('admin/datos-cajas/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-qrcode c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.datosCaja.title') }}
                        </a>
                    </li>
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.recibe-masters.index') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/recibe-masters') || request()->is('admin/recibe-masters/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-flask c-sidebar-nav-icon">

                            </i>
                            PT&I
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @can("packing")
        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/hand-packs*") ? "c-show" : "" }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw fas fa-boxes c-sidebar-nav-icon">

                </i>
                Packing - Producción
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a href="{{ route('admin.planificador-personal.index') }}"
                        class="c-sidebar-nav-link {{ request()->is('admin/planificador-personal') || request()->is('admin/planificador-personal/*') ? 'c-active' : '' }}">
                        <i class="fa-fw fas fa-clipboard-list c-sidebar-nav-icon">

                        </i>
                        Planificador de Personal
                    </a>
                </li>
                 <li class="c-sidebar-nav-item">
                    <a href="{{ route('admin.personals.assignLocationForm') }}"
                        class="c-sidebar-nav-link {{ request()->is('admin/personals/assign-location') ? 'c-active' : '' }}">
                        <i class="fa-fw fas fa-map-marker-alt c-sidebar-nav-icon">

                        </i>
                        Configurar Ubicación
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a href="{{ route('admin.hand-packs.index') }}"
                        class="c-sidebar-nav-link {{ request()->is('admin/hand-packs') || request()->is('admin/hand-packs/*') ? 'c-active' : '' }}">
                        <i class="fa-fw fas fa-box-open c-box">

                        </i>
                        HandPack
                    </a>
                </li>
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.hand-packs.lector') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/hand-packs') || request()->is('admin/hand-packs/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-hand-paper c-box">

                            </i>
                            Lector HandPack
                        </a>
                    </li>
                    <li>
                         <a href="{{ route('admin.attendance.reportIndex') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/hand-packs') || request()->is('admin/hand-packs/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-file c-box">

                            </i>
                            Reporte Asistencia
                        </a>
                    </li>
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.packing.detenciones') }}"
                           class="c-sidebar-nav-link {{ request()->is('admin/packing/detenciones-lineas') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-chart-area c-box">

                            </i>
                            Reporte Detenciones
                        </a>
                    </li>

            </ul>
        </li>
        @endcan
        @can("firma_access")
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.firma.index') }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-pencil-alt">

                </i>
               Pie de Firma
            </a>

        </li>
        @endcan
        @can('greenex_net_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/entidads*') ? 'c-show' : '' }} {{ request()->is('admin/areas*') ? 'c-show' : '' }} {{ request()->is('admin/locacions*') ? 'c-show' : '' }} {{ request()->is('admin/turnos*') ? 'c-show' : '' }} {{ request()->is('admin/frecuencia-turnos*') ? 'c-show' : '' }} {{ request()->is('admin/cargos*') ? 'c-show' : '' }} {{ request()->is('admin/personals*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    RRHH
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('entidad_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.entidads.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/entidads') || request()->is('admin/entidads/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.entidad.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('area_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.areas.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/areas') || request()->is('admin/areas/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-autoprefixer c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.area.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('locacion_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.locacions.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/locacions') || request()->is('admin/locacions/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-map-pin c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.locacion.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('turno_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.turnos.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/turnos') || request()->is('admin/turnos/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-clock c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.turno.title') }}
                            </a>
                        </li>
                    @endcan

                    @can('frecuencia_turno_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.frecuencia-turnos.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/frecuencia-turnos') || request()->is('admin/frecuencia-turnos/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-calendar-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.frecuenciaTurno.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('asistencium_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.asistencia.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/asistencia') || request()->is('admin/asistencia/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.asistencium.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('cargo_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.cargos.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/cargos') || request()->is('admin/cargos/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-user-md c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.cargo.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('personal_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.personals.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/personals') || request()->is('admin/personals/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.personal.title') }}
                            </a>
                        </li>

                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.personals.cuadratura') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/personals') || request()->is('admin/personals/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-clock c-sidebar-nav-icon">

                                </i>
                                Cuadratura de Asistencia
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.personals.tratoembalaje') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/personals') || request()->is('admin/personals/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-clock c-sidebar-nav-icon">

                                </i>
                                Trato Embalaje
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.personals.tratoContratista') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/personals') || request()->is('admin/personals/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-handshake c-sidebar-nav-icon">

                                </i>
                                Trato HandPack
                            </a>
                        </li>

                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.attendance.reportIndex') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/attendance/report') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-chart-line c-sidebar-nav-icon">

                                </i>
                                Reporte de Asistencia
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        {{-- @can('turnos_frecuencium_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.turnos-frecuencia.index') }}"
                    class="c-sidebar-nav-link {{ request()->is('admin/turnos-frecuencia') || request()->is('admin/turnos-frecuencia/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-band-aid c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.turnosFrecuencium.title') }}
                </a>
            </li>
        @endcan --}}
        @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}"
                        href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        @can('access_reporteria')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/reporteria*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-chart-bar c-sidebar-nav-icon">

                    </i>
                    Reportería
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('reporteria_access_stock_inventario')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.reporteria.obtenerDatosReporte') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/reporteria') || request()->is('admin/reporteria/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-chart-pie c-sidebar-nav-icon">

                                </i>
                                Stock Inventario
                            </a>
                        </li>
                    @endcan
                    @can('reporteria_access_transito')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.reporteria.Transito') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/reporteria') || request()->is('admin/reporteria/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-shipping-fast c-sidebar-nav-icon">

                                </i>
                                Tránsito
                            </a>
                        </li>
                    @endcan
                    @can('reporteria_access_embarques')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.reporteria.embarques') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/reporteria') || request()->is('admin/reporteria/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-ship c-sidebar-nav-icon">

                                </i>
                                Embarques
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.reporteria.detalleembarque') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/reporteria') || request()->is('admin/reporteria/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-ship c-sidebar-nav-icon">

                                </i>
                                Detalle Embarques
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.reporteria.detallecajas') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/reporteria') || request()->is('admin/reporteria/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-box-open c-sidebar-nav-icon">

                                </i>
                                Detalle Cajas
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.reporteria.liquidacionesventa') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/reporteria') || request()->is('admin/reporteria/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-box-open c-sidebar-nav-icon">

                                </i>
                                Liquidaciones Venta
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.home') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/reporteria') || request()->is('admin/reporteria/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-box-open c-sidebar-nav-icon">
                                </i>
                                Compartivo Liquidaciones CX
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('bi_report_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.bi-reports.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/bi-reports') || request()->is('admin/bi-reports/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-chart-line c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.biReport.title') }}
                </a>
            </li>


        @endcan
        @can("comex_access")
        <li class="c-sidebar-nav-dropdown {{ request()->is('admin/embalajes*') ? 'c-show' : '' }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw fas fa-coins c-sidebar-nav-icon"></i>
                COMEX
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                @can('embarque_access')
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.embarques.index') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/embarques') || request()->is('admin/embarques/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.embarque.title') }}
                        </a>
                    </li>
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.embarques.packingList') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/embarques/packing-list') || request()->is('admin/embarques/packing-list/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-box-open c-sidebar-nav-icon">

                            </i>
                            Packing List
                        </a>
                    </li>
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.embarques.ingresagrecepcion') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/detalle-embarques') || request()->is('admin/detalle-embarques/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                            </i>
                            Ingresa Guía de Recepción
                        </a>
                    </li>
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.comex.capturador') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/comex') || request()->is('admin/comex/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-file-code c-sidebar-nav-icon">
                            </i>
                            Capturador de Liquidaciones
                        </a>
                    </li>
                    @can('liq_cx_cabecera_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.liq-cx-cabeceras.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/liq-cx-cabeceras') || request()->is('admin/liq-cx-cabeceras/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-address-card c-sidebar-nav-icon">

                                </i>
                                Liquidaciones
                            </a>
                        </li>
                    @endcan
                    @can('costos_origen_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.costosorigen.costosorigen') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/costosorigen') || request()->is('admin/costosorigen/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-address-card c-sidebar-nav-icon">

                                </i>
                                Costos Origen
                            </a>
                        </li>
                    @endcan
                    {{-- @can('liquidaciones_cx_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.liquidaciones-cxes.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/liquidaciones-cxes') || request()->is('admin/liquidaciones-cxes/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-balance-scale c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.liquidacionesCx.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('liq_costo_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.liq-costos.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/liq-costos') || request()->is('admin/liq-costos/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.liqCosto.title') }}
                            </a>
                        </li>
                    @endcan --}}
                @endcan

            </ul>
        </li>
        @endcan
        @can('operacione_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/materials*") ? "c-show" : "" }} {{ request()->is("admin/material-productos*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fab fa-algolia c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.operacione.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('material_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.materials.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/materials") || request()->is("admin/materials/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-toolbox c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.material.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('material_producto_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.material-productos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/material-productos") || request()->is("admin/material-productos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.materialProducto.title') }}
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.tarjado.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/material-productos") || request()->is("admin/material-productos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                Estado Tarjado
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('instructivo_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/base-recibidors*") ? "c-show" : "" }} {{ request()->is("admin/base-contactos*") ? "c-show" : "" }} {{ request()->is("admin/agente-aduanas*") ? "c-show" : "" }} {{ request()->is("admin/puerto-correos*") ? "c-show" : "" }} {{ request()->is("admin/embarcadors*") ? "c-show" : "" }} {{ request()->is("admin/chofers*") ? "c-show" : "" }} {{ request()->is("admin/planta-cargas*") ? "c-show" : "" }} {{ request()->is("admin/peso-embalajes*") ? "c-show" : "" }} {{ request()->is("admin/navieras*") ? "c-show" : "" }} {{ request()->is("admin/condpagos*") ? "c-show" : "" }} {{ request()->is("admin/correoalso-airs*") ? "c-show" : "" }} {{ request()->is("admin/instructivo-embarques*") ? "c-show" : "" }} {{ request()->is("admin/tipofletes*") ? "c-show" : "" }} {{ request()->is("admin/emision-bls*") ? "c-show" : "" }} {{ request()->is("admin/forma-pagos*") ? "c-show" : "" }} {{ request()->is("admin/mod-venta*") ? "c-show" : "" }} {{ request()->is("admin/clausula-venta*") ? "c-show" : "" }} {{ request()->is("admin/monedas*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.instructivo.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('instructivo_embarque_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.instructivo-embarques.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/instructivo-embarques") || request()->is("admin/instructivo-embarques/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-info c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.instructivoEmbarque.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('base_recibidor_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.base-recibidors.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/base-recibidors") || request()->is("admin/base-recibidors/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-align-justify c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.baseRecibidor.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('base_contacto_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.base-contactos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/base-contactos") || request()->is("admin/base-contactos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.baseContacto.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('agente_aduana_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.agente-aduanas.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/agente-aduanas") || request()->is("admin/agente-aduanas/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-adn c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.agenteAduana.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('puerto_correo_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.puerto-correos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/puerto-correos") || request()->is("admin/puerto-correos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.puertoCorreo.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('embarcador_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.embarcadors.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/embarcadors") || request()->is("admin/embarcadors/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.embarcador.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('chofer_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.chofers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/chofers") || request()->is("admin/chofers/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.chofer.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('planta_carga_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.planta-cargas.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/planta-cargas") || request()->is("admin/planta-cargas/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.plantaCarga.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('peso_embalaje_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.peso-embalajes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/peso-embalajes") || request()->is("admin/peso-embalajes/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-balance-scale c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.pesoEmbalaje.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('naviera_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.navieras.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/navieras") || request()->is("admin/navieras/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-ship c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.naviera.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('condpago_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.condpagos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/condpagos") || request()->is("admin/condpagos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-invoice-dollar c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.condpago.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('correoalso_air_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.correoalso-airs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/correoalso-airs") || request()->is("admin/correoalso-airs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.correoalsoAir.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('tipoflete_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.tipofletes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/tipofletes") || request()->is("admin/tipofletes/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-truck-moving c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.tipoflete.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('emision_bl_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.emision-bls.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/emision-bls") || request()->is("admin/emision-bls/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.emisionBl.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('forma_pago_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.forma-pagos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/forma-pagos") || request()->is("admin/forma-pagos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hand-holding-usd c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.formaPago.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('mod_ventum_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.mod-venta.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/mod-venta") || request()->is("admin/mod-venta/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-credit-card c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.modVentum.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('clausula_ventum_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.clausula-venta.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/clausula-venta") || request()->is("admin/clausula-venta/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-ruble-sign c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.clausulaVentum.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('moneda_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.monedas.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/monedas") || request()->is("admin/monedas/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-coins c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.moneda.title') }}
                            </a>
                        </li>
                    @endcan
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.also-notifies.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/also-notifies") || request()->is("admin/also-notifies/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-bell c-sidebar-nav-icon">

                            </i>
                            Also Notify
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @can('confeccion_liquidacion_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/grupos*") ? "c-show" : "" }} {{ request()->is("admin/productors*") ? "c-show" : "" }} {{ request()->is("admin/conjuntos*") ? "c-show" : "" }} {{ request()->is("admin/valor-fletes*") ? "c-show" : "" }} {{ request()->is("admin/valor-dolars*") ? "c-show" : "" }} {{ request()->is("admin/valor-envases*") ? "c-show" : "" }} {{ request()->is("admin/anticipos*") ? "c-show" : "" }} {{ request()->is("admin/interes-anticipos*") ? "c-show" : "" }} {{ request()->is("admin/recepcions*") ? "c-show" : "" }} {{ request()->is("admin/procesos*") ? "c-show" : "" }} {{ request()->is("admin/multiresiduos*") ? "c-show" : "" }} {{ request()->is("admin/bonificacions*") ? "c-show" : "" }} {{ request()->is("admin/otro-cobros*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fab fa-asymmetrik c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.confeccionLiquidacion.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.constructorliquidacion.selector") }}" class="c-sidebar-nav-link {{ request()->is("admin/recepcions") || request()->is("admin/recepcions/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-spinner c-sidebar-nav-icon">

                            </i>
                           Generar Liquidación
                        </a>
                    </li>
                    @can('grupo_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.grupos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/grupos") || request()->is("admin/grupos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.grupo.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('productor_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.productors.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/productors") || request()->is("admin/productors/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-tree c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.productor.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('conjunto_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.conjuntos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/conjuntos") || request()->is("admin/conjuntos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-circle c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.conjunto.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('analisi_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.analisis.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/analisis") || request()->is("admin/analisis/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-lightbulb c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.analisi.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('valor_flete_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.valor-fletes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/valor-fletes") || request()->is("admin/valor-fletes/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-truck-loading c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.valorFlete.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('valor_dolar_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.valor-dolars.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/valor-dolars") || request()->is("admin/valor-dolars/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-dollar-sign c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.valorDolar.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('valor_envase_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.valor-envases.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/valor-envases") || request()->is("admin/valor-envases/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-box-open c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.valorEnvase.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('anticipo_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.anticipos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/anticipos") || request()->is("admin/anticipos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hand-holding-usd c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.anticipo.title') }}
                            </a>
                        </li>

                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.interes-anticipos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/interes-anticipos") || request()->is("admin/interes-anticipos/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-money-bill-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.interesAnticipo.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('recepcion_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.recepcions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/recepcions") || request()->is("admin/recepcions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-spinner c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.recepcion.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('proceso_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.procesos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/procesos") || request()->is("admin/procesos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.proceso.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('multiresiduo_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.multiresiduos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/multiresiduos") || request()->is("admin/multiresiduos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-trash-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.multiresiduo.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('bonificacion_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.bonificacions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/bonificacions") || request()->is("admin/bonificacions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-dollar-sign c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.bonificacion.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('otro_cobro_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.otro-cobros.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/otro-cobros") || request()->is("admin/otro-cobros/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hand-holding-usd c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.otroCobro.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('otroscargo_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.otroscargos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/otroscargos") || request()->is("admin/otroscargos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-money-bill-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.otroscargo.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('maestros_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/embalajes*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-table c-sidebar-nav-icon">

                    </i>
                    Maestros
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('clientes_comex_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.clientes-comexes.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/clientes-comexes') || request()->is('admin/clientes-comexes/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-wallet c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.clientesComex.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('metas_cliente_comex_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.metas-cliente-comexes.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/metas-cliente-comexes') || request()->is('admin/metas-cliente-comexes/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.metasClienteComex.title') }}
                            </a>
                        </li>
                    @endcan

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.embalajes.index') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/embalajes') || request()->is('admin/embalajes/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-box c-sidebar-nav-icon">

                            </i>
                            Embalajes
                        </a>
                    </li>
                    @can('proveedor_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.proveedors.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/proveedors') || request()->is('admin/proveedors/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-user-tie c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.proveedor.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('country_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.countries.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/countries') || request()->is('admin/countries/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-flag c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.country.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('puerto_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.puertos.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/puertos') || request()->is('admin/puertos/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-swimming-pool c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.puerto.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('familium_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.familia.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/familia') || request()->is('admin/familia/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-apple c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.familium.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('especy_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.especies.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/especies') || request()->is('admin/especies/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-blackberry c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.especy.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('variedad_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.variedads.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/variedads') || request()->is('admin/variedads/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-affiliatetheme c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.variedad.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('categorium_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.categoria.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/categoria') || request()->is('admin/categoria/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-accusoft c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.categorium.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('etiquetum_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.etiqueta.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/etiqueta') || request()->is('admin/etiqueta/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-adversal c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.etiquetum.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('etiquetas_x_especy_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.etiquetas-x-especies.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/etiquetas-x-especies') || request()->is('admin/etiquetas-x-especies/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.etiquetasXEspecy.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('nafe_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.naves.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/naves') || request()->is('admin/naves/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-ship c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.nafe.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('item_embalaje_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.item-embalajes.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/item-embalajes') || request()->is('admin/item-embalajes/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-box-open c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.itemEmbalaje.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('costo_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.costos.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/costos') || request()->is('admin/costos/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-hand-holding-usd c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.costo.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('configuracion_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.configuracions.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/configuracions') || request()->is('admin/configuracions/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.configuracion.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('diccionario_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.diccionarios.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/diccionarios') || request()->is('admin/diccionarios/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-book c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.diccionario.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('capturador_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.capturadors.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/capturadors') || request()->is('admin/capturadors/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.capturador.title') }}
                            </a>
                        </li>
                        @can('capturador_estructura_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route('admin.capturador-estructuras.index') }}"
                                    class="c-sidebar-nav-link {{ request()->is('admin/capturador-estructuras') || request()->is('admin/capturador-estructuras/*') ? 'c-active' : '' }}">
                                    <i class="fa-fw fas fa-align-left c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.capturadorEstructura.title') }}
                                </a>
                            </li>
                        @endcan
                    @endcan
                    @can('tipos_seccion_conversor_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.tipos-seccion-conversors.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/tipos-seccion-conversors') || request()->is('admin/tipos-seccion-conversors/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.tiposSeccionConversor.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('funcione_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.funciones.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/funciones') || request()->is('admin/funciones/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-thermometer-full c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.funcione.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('modulo_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.modulos.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/modulos') || request()->is('admin/modulos/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-affiliatetheme c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.modulo.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link"
                onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
