<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.home') }}">
                                    {{ __('Dashboard') }}
                                </a>
                            </li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if(Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('frontend.profile.index') }}">{{ __('My profile') }}</a>

                                    @can('user_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.userManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('permission_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.permissions.index') }}">
                                            {{ trans('cruds.permission.title') }}
                                        </a>
                                    @endcan
                                    @can('role_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.roles.index') }}">
                                            {{ trans('cruds.role.title') }}
                                        </a>
                                    @endcan
                                    @can('user_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.users.index') }}">
                                            {{ trans('cruds.user.title') }}
                                        </a>
                                    @endcan
                                    @can('estado_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.estados.index') }}">
                                            {{ trans('cruds.estado.title') }}
                                        </a>
                                    @endcan
                                    @can('configuracion_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.configuracions.index') }}">
                                            {{ trans('cruds.configuracion.title') }}
                                        </a>
                                    @endcan
                                    @can('manifiesto_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.manifiesto.title') }}
                                        </a>
                                    @endcan
                                    @can('manifiest_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.manifiests.index') }}">
                                            {{ trans('cruds.manifiest.title') }}
                                        </a>
                                    @endcan
                                    @can('guium_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.guia.index') }}">
                                            {{ trans('cruds.guium.title') }}
                                        </a>
                                    @endcan
                                    @can('importacion_marcas_manifiesto_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.importacion-marcas-manifiestos.index') }}">
                                            {{ trans('cruds.importacionMarcasManifiesto.title') }}
                                        </a>
                                    @endcan
                                    @can('pre_carga_manifiesto_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.pre-carga-manifiestos.index') }}">
                                            {{ trans('cruds.preCargaManifiesto.title') }}
                                        </a>
                                    @endcan
                                    @can('hawb_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.hawbs.index') }}">
                                            {{ trans('cruds.hawb.title') }}
                                        </a>
                                    @endcan
                                    @can('adicionale_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.adicionales.index') }}">
                                            {{ trans('cruds.adicionale.title') }}
                                        </a>
                                    @endcan
                                    @can('aclaracionguium_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.aclaracionguia.index') }}">
                                            {{ trans('cruds.aclaracionguium.title') }}
                                        </a>
                                    @endcan
                                    @can('aclaracion_mawb_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.aclaracion-mawbs.index') }}">
                                            {{ trans('cruds.aclaracionMawb.title') }}
                                        </a>
                                    @endcan
                                    @can('modulo_dip_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.moduloDip.title') }}
                                        </a>
                                    @endcan
                                    @can('dip_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.dips.index') }}">
                                            {{ trans('cruds.dip.title') }}
                                        </a>
                                    @endcan
                                    @can('crear_batch_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.crear-batches.index') }}">
                                            {{ trans('cruds.crearBatch.title') }}
                                        </a>
                                    @endcan
                                    @can('hawb_batch_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.hawb-batches.index') }}">
                                            {{ trans('cruds.hawbBatch.title') }}
                                        </a>
                                    @endcan
                                    @can('correlativo_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.correlativos.index') }}">
                                            {{ trans('cruds.correlativo.title') }}
                                        </a>
                                    @endcan
                                    @can('arribo_efectivo_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.arriboEfectivo.title') }}
                                        </a>
                                    @endcan
                                    @can('ingreso_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.ingresos.index') }}">
                                            {{ trans('cruds.ingreso.title') }}
                                        </a>
                                    @endcan
                                    @can('auxiliare_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.auxiliare.title') }}
                                        </a>
                                    @endcan
                                    @can('airline_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.airlines.index') }}">
                                            {{ trans('cruds.airline.title') }}
                                        </a>
                                    @endcan
                                    @can('vuelo_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.vuelos.index') }}">
                                            {{ trans('cruds.vuelo.title') }}
                                        </a>
                                    @endcan
                                    @can('ciudad_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.ciudads.index') }}">
                                            {{ trans('cruds.ciudad.title') }}
                                        </a>
                                    @endcan
                                    @can('region_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.regions.index') }}">
                                            {{ trans('cruds.region.title') }}
                                        </a>
                                    @endcan
                                    @can('comuna_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.comunas.index') }}">
                                            {{ trans('cruds.comuna.title') }}
                                        </a>
                                    @endcan
                                    @can('status_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.statuses.index') }}">
                                            {{ trans('cruds.status.title') }}
                                        </a>
                                    @endcan
                                    @can('tipo_hawb_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tipo-hawbs.index') }}">
                                            {{ trans('cruds.tipoHawb.title') }}
                                        </a>
                                    @endcan
                                    @can('tipo_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tipos.index') }}">
                                            {{ trans('cruds.tipo.title') }}
                                        </a>
                                    @endcan
                                    @can('tipoequi_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tipoequis.index') }}">
                                            {{ trans('cruds.tipoequi.title') }}
                                        </a>
                                    @endcan
                                    @can('traduccione_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.traducciones.index') }}">
                                            {{ trans('cruds.traduccione.title') }}
                                        </a>
                                    @endcan
                                    @can('pai_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.pais.index') }}">
                                            {{ trans('cruds.pai.title') }}
                                        </a>
                                    @endcan
                                    @can('arancel_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.arancels.index') }}">
                                            {{ trans('cruds.arancel.title') }}
                                        </a>
                                    @endcan
                                    @can('tipo_bulto_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tipo-bultos.index') }}">
                                            {{ trans('cruds.tipoBulto.title') }}
                                        </a>
                                    @endcan
                                    @can('tipo_de_inspeccion_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tipo-de-inspeccions.index') }}">
                                            {{ trans('cruds.tipoDeInspeccion.title') }}
                                        </a>
                                    @endcan
                                    @can('fiscalizador_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.fiscalizadors.index') }}">
                                            {{ trans('cruds.fiscalizador.title') }}
                                        </a>
                                    @endcan
                                    @can('almacenistum_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.almacenista.index') }}">
                                            {{ trans('cruds.almacenistum.title') }}
                                        </a>
                                    @endcan
                                    @can('aduana_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.aduanas.index') }}">
                                            {{ trans('cruds.aduana.title') }}
                                        </a>
                                    @endcan
                                    @can('puerto_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.puertos.index') }}">
                                            {{ trans('cruds.puerto.title') }}
                                        </a>
                                    @endcan
                                    @can('regiman_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.regimen.index') }}">
                                            {{ trans('cruds.regiman.title') }}
                                        </a>
                                    @endcan
                                    @can('tipo_flete_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tipo-fletes.index') }}">
                                            {{ trans('cruds.tipoFlete.title') }}
                                        </a>
                                    @endcan
                                    @can('cliente_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.clientes.index') }}">
                                            {{ trans('cruds.cliente.title') }}
                                        </a>
                                    @endcan
                                    @can('entidade_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.entidade.title') }}
                                        </a>
                                    @endcan
                                    @can('consignatario_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.consignatarios.index') }}">
                                            {{ trans('cruds.consignatario.title') }}
                                        </a>
                                    @endcan
                                    @can('direccion_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.direccions.index') }}">
                                            {{ trans('cruds.direccion.title') }}
                                        </a>
                                    @endcan
                                    @can('dussy_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.dussy.title') }}
                                        </a>
                                    @endcan
                                    @can('dussi_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.dussis.index') }}">
                                            {{ trans('cruds.dussi.title') }}
                                        </a>
                                    @endcan
                                    @can('batch_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.batches.index') }}">
                                            {{ trans('cruds.batch.title') }}
                                        </a>
                                    @endcan
                                    @can('guias_dussi_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.guias-dussis.index') }}">
                                            {{ trans('cruds.guiasDussi.title') }}
                                        </a>
                                    @endcan
                                    @can('user_alert_access')
                                        <a class="dropdown-item" href="{{ route('frontend.user-alerts.index') }}">
                                            {{ trans('cruds.userAlert.title') }}
                                        </a>
                                    @endcan
                                    @can('faq_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.faqManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('faq_category_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.faq-categories.index') }}">
                                            {{ trans('cruds.faqCategory.title') }}
                                        </a>
                                    @endcan
                                    @can('faq_question_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.faq-questions.index') }}">
                                            {{ trans('cruds.faqQuestion.title') }}
                                        </a>
                                    @endcan
                                    @can('course_access')
                                        <a class="dropdown-item" href="{{ route('frontend.courses.index') }}">
                                            {{ trans('cruds.course.title') }}
                                        </a>
                                    @endcan
                                    @can('lesson_access')
                                        <a class="dropdown-item" href="{{ route('frontend.lessons.index') }}">
                                            {{ trans('cruds.lesson.title') }}
                                        </a>
                                    @endcan
                                    @can('test_access')
                                        <a class="dropdown-item" href="{{ route('frontend.tests.index') }}">
                                            {{ trans('cruds.test.title') }}
                                        </a>
                                    @endcan
                                    @can('question_access')
                                        <a class="dropdown-item" href="{{ route('frontend.questions.index') }}">
                                            {{ trans('cruds.question.title') }}
                                        </a>
                                    @endcan
                                    @can('question_option_access')
                                        <a class="dropdown-item" href="{{ route('frontend.question-options.index') }}">
                                            {{ trans('cruds.questionOption.title') }}
                                        </a>
                                    @endcan
                                    @can('test_result_access')
                                        <a class="dropdown-item" href="{{ route('frontend.test-results.index') }}">
                                            {{ trans('cruds.testResult.title') }}
                                        </a>
                                    @endcan
                                    @can('test_answer_access')
                                        <a class="dropdown-item" href="{{ route('frontend.test-answers.index') }}">
                                            {{ trans('cruds.testAnswer.title') }}
                                        </a>
                                    @endcan
                                    @can('task_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.taskManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('task_status_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.task-statuses.index') }}">
                                            {{ trans('cruds.taskStatus.title') }}
                                        </a>
                                    @endcan
                                    @can('task_tag_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.task-tags.index') }}">
                                            {{ trans('cruds.taskTag.title') }}
                                        </a>
                                    @endcan
                                    @can('task_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tasks.index') }}">
                                            {{ trans('cruds.task.title') }}
                                        </a>
                                    @endcan
                                    @can('inhumado_access')
                                        <a class="dropdown-item" href="{{ route('frontend.inhumados.index') }}">
                                            {{ trans('cruds.inhumado.title') }}
                                        </a>
                                    @endcan
                                    @can('saep_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.saep.title') }}
                                        </a>
                                    @endcan
                                    @can('ingreso_bulto_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.ingreso-bultos.index') }}">
                                            {{ trans('cruds.ingresoBulto.title') }}
                                        </a>
                                    @endcan
                                    @can('estados_saep_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.estados-saeps.index') }}">
                                            {{ trans('cruds.estadosSaep.title') }}
                                        </a>
                                    @endcan
                                    @can('motivo_saep_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.motivo-saeps.index') }}">
                                            {{ trans('cruds.motivoSaep.title') }}
                                        </a>
                                    @endcan
                                    @can('bodega_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.bodegas.index') }}">
                                            {{ trans('cruds.bodega.title') }}
                                        </a>
                                    @endcan
                                    @can('ingreso_cadena_custodium_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.ingreso-cadena-custodia.index') }}">
                                            {{ trans('cruds.ingresoCadenaCustodium.title') }}
                                        </a>
                                    @endcan
                                    @can('estado_abandono_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.estado-abandonos.index') }}">
                                            {{ trans('cruds.estadoAbandono.title') }}
                                        </a>
                                    @endcan
                                    @can('datos_caja_calidad_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.datosCajaCalidad.title') }}
                                        </a>
                                    @endcan
                                    @can('datos_caja_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.datos-cajas.index') }}">
                                            {{ trans('cruds.datosCaja.title') }}
                                        </a>
                                    @endcan

                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session('message'))
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        </div>
                    </div>
                </div>
            @endif
            @if($errors->count() > 0)
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <ul class="list-unstyled mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>
@yield('scripts')

</html>