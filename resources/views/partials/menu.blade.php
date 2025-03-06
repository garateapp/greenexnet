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
                Packing
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                
                <li class="c-sidebar-nav-item">
                    <a href="{{ route('admin.hand-packs.index') }}"
                        class="c-sidebar-nav-link {{ request()->is('admin/hand-packs') || request()->is('admin/hand-packs/*') ? 'c-active' : '' }}">
                        <i class="fa-fw fas fa-boxes-open c-box">

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
                    
            </ul>
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
                            <a href="{{ route('admin.reporteria.compartivoliquidacionescx') }}"
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
        <li class="c-sidebar-nav-dropdown {{ request()->is('admin/embalajes*') ? 'c-show' : '' }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                Operaciones
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                @can('operaciones_fusionar_folios_access')
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.operaciones.fusionarFolios') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/operaciones') || request()->is('admin/operaciones/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-documents c-sidebar-nav-icon">

                            </i>
                            Fusionar
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
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
