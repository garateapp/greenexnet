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
                    <i class="fa-fw fas fa-box-open c-sidebar-nav-icon">

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
        @can('greenex_net_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/entidads*') ? 'c-show' : '' }} {{ request()->is('admin/areas*') ? 'c-show' : '' }} {{ request()->is('admin/locacions*') ? 'c-show' : '' }} {{ request()->is('admin/turnos*') ? 'c-show' : '' }} {{ request()->is('admin/frecuencia-turnos*') ? 'c-show' : '' }} {{ request()->is('admin/cargos*') ? 'c-show' : '' }} {{ request()->is('admin/personals*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.greenexNet.title') }}
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

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.reporteria.obtenerDatosReporte') }}"
                            class="c-sidebar-nav-link {{ request()->is('admin/reporteria') || request()->is('admin/reporteria/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-chart-pie c-sidebar-nav-icon">

                            </i>
                            Stock Inventario
                        </a>
                    </li>

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
