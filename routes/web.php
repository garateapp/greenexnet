<?php

//use Illuminate\Routing\Route;
use App\Http\Controllers\Admin\ReporteriaController;
//use Illuminate\Routing\Route;

Route::view('/', '/welcome');
Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('datos-cajas/getAttendanceData', 'DatosCajaController@getAttendanceData')->name('datos-cajas.getAttendanceData');
    Route::get('datos-cajas/daily', 'DatosCajaController@getDailyAttendanceData')->name('datos-cajas.getAttendanceData');
    Route::get('/datos-cajas/by-turn', 'DatosCajaController@getAttendanceByTurn')->name('datos-cajas.getAttendanceByTurn');
    Route::get('/datos-cajas/getAttendanceData', 'DatosCajaController@getAttendanceData')->name('datos-cajas.getAttendanceData');
    Route::get('/datos-cajas/getScatterPlotData', 'DatosCajaController@getScatterPlotData')->name('datos-cajas.getScatterPlotData');
    //Route::get('attendance-data', 'HomeController@getAttendanceData')->name('getAttendanceData');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Estados
    Route::delete('estados/destroy', 'EstadosController@massDestroy')->name('estados.massDestroy');
    Route::post('estados/media', 'EstadosController@storeMedia')->name('estados.storeMedia');
    Route::post('estados/ckmedia', 'EstadosController@storeCKEditorImages')->name('estados.storeCKEditorImages');
    Route::resource('estados', 'EstadosController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);



    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);



    // Datos Caja
    Route::delete('datos-cajas/destroy', 'DatosCajaController@massDestroy')->name('datos-cajas.massDestroy');
    Route::post('datos-cajas/parse-csv-import', 'DatosCajaController@parseCsvImport')->name('datos-cajas.parseCsvImport');
    Route::post('datos-cajas/process-csv-import', 'DatosCajaController@processCsvImport')->name('datos-cajas.processCsvImport');
    Route::post('datos-cajas/buscaDatosCaja', 'DatosCajaController@buscaDatosCaja')->name('datos-cajas.buscaDatosCaja');
    Route::resource('datos-cajas', 'DatosCajaController');


    // Entidad
    Route::delete('entidads/destroy', 'EntidadController@massDestroy')->name('entidads.massDestroy');
    Route::post('entidads/parse-csv-import', 'EntidadController@parseCsvImport')->name('entidads.parseCsvImport');
    Route::post('entidads/process-csv-import', 'EntidadController@processCsvImport')->name('entidads.processCsvImport');
    Route::resource('entidads', 'EntidadController');

    // Area
    Route::delete('areas/destroy', 'AreaController@massDestroy')->name('areas.massDestroy');
    Route::post('areas/parse-csv-import', 'AreaController@parseCsvImport')->name('areas.parseCsvImport');
    Route::post('areas/process-csv-import', 'AreaController@processCsvImport')->name('areas.processCsvImport');
    Route::resource('areas', 'AreaController');

    // Locacion
    Route::delete('locacions/destroy', 'LocacionController@massDestroy')->name('locacions.massDestroy');
    Route::post('locacions/parse-csv-import', 'LocacionController@parseCsvImport')->name('locacions.parseCsvImport');
    Route::post('locacions/process-csv-import', 'LocacionController@processCsvImport')->name('locacions.processCsvImport');
    Route::resource('locacions', 'LocacionController');

    // Turno
    Route::delete('turnos/destroy', 'TurnoController@massDestroy')->name('turnos.massDestroy');
    Route::post('turnos/parse-csv-import', 'TurnoController@parseCsvImport')->name('turnos.parseCsvImport');
    Route::post('turnos/process-csv-import', 'TurnoController@processCsvImport')->name('turnos.processCsvImport');
    Route::resource('turnos', 'TurnoController');

    Route::get('turnos/getdatoturno', 'TurnoController@getdatoturno')->name('turnos.getdatoturno');


    // Frecuencia Turno
    Route::delete('frecuencia-turnos/destroy', 'FrecuenciaTurnoController@massDestroy')->name('frecuencia-turnos.massDestroy');
    Route::post('frecuencia-turnos/parse-csv-import', 'FrecuenciaTurnoController@parseCsvImport')->name('frecuencia-turnos.parseCsvImport');
    Route::post('frecuencia-turnos/process-csv-import', 'FrecuenciaTurnoController@processCsvImport')->name('frecuencia-turnos.processCsvImport');
    Route::resource('frecuencia-turnos', 'FrecuenciaTurnoController');

    // Cargo
    Route::delete('cargos/destroy', 'CargoController@massDestroy')->name('cargos.massDestroy');
    Route::post('cargos/parse-csv-import', 'CargoController@parseCsvImport')->name('cargos.parseCsvImport');
    Route::post('cargos/process-csv-import', 'CargoController@processCsvImport')->name('cargos.processCsvImport');
    Route::resource('cargos', 'CargoController');

    // Personal
    Route::delete('personals/destroy', 'PersonalController@massDestroy')->name('personals.massDestroy');
    Route::post('personals/parse-csv-import', 'PersonalController@parseCsvImport')->name('personals.parseCsvImport');
    Route::post('personals/process-csv-import', 'PersonalController@processCsvImport')->name('personals.processCsvImport');
    Route::get('personals/cuadratura', 'PersonalController@cuadratura')->name('personals.cuadratura');
    Route::post('personals/ejecutaCuadratura', 'PersonalController@ejecutaCuadratura')->name('personals.ejecutaCuadratura');
    Route::resource('personals', 'PersonalController');


    // Turnos Frecuencia
    Route::delete('turnos-frecuencia/destroy', 'TurnosFrecuenciaController@massDestroy')->name('turnos-frecuencia.massDestroy');
    Route::post('turnos-frecuencia/parse-csv-import', 'TurnosFrecuenciaController@parseCsvImport')->name('turnos-frecuencia.parseCsvImport');
    Route::post('turnos-frecuencia/process-csv-import', 'TurnosFrecuenciaController@processCsvImport')->name('turnos-frecuencia.processCsvImport');
    Route::resource('turnos-frecuencia', 'TurnosFrecuenciaController');

    // Asistencia
    Route::delete('asistencia/destroy', 'AsistenciaController@massDestroy')->name('asistencia.massDestroy');
    Route::post('asistencia/parse-csv-import', 'AsistenciaController@parseCsvImport')->name('asistencia.parseCsvImport');
    Route::post('asistencia/process-csv-import', 'AsistenciaController@processCsvImport')->name('asistencia.processCsvImport');
    Route::resource('asistencia', 'AsistenciaController');
    Route::post('asistencia/cargaUbicaciones', 'AsistenciaController@cargaUbicaciones')->name('asistencia.cargaUbicaciones');
    Route::post('asistencia/cargaDatosTurno', 'AsistenciaController@cargaDatosTurno')->name('asistencia.cargaDatosTurno');


    // Recibe Master
    Route::delete('recibe-masters/destroy', 'RecibeMasterController@massDestroy')->name('recibe-masters.massDestroy');
    Route::post('recibe-masters/parse-csv-import', 'RecibeMasterController@parseCsvImport')->name('recibe-masters.parseCsvImport');
    Route::post('recibe-masters/process-csv-import', 'RecibeMasterController@processCsvImport')->name('recibe-masters.processCsvImport');
    Route::get('recibe-masters/AccesoSis', 'RecibeMasterController@AccesoSis')->name('recibe-masters.AccesoSis');
    Route::get('recibe-masters/ObtieneLotes', 'RecibeMasterController@ObtieneLotes')->name('recibe-masters.ObtieneLotes');
    Route::get('recibe-masters/CapturarLote', 'RecibeMasterController@CapturarLote')->name('recibe-masters.CapturarLote');
    Route::resource('recibe-masters', 'RecibeMasterController');
    Route::post('recibe-masters/ObtieneDatosRecepcion', 'RecibeMasterController@ObtieneDatosRecepcion')->name('recibe-masters.ObtieneDatosRecepcion');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

    //Reportería
    Route::get('reporteria/obtieneDatosStockInventario', "ReporteriaController@obtieneDatosStockInventario")->name("reporteria.obtieneDatosStockInventario");
    Route::get('reporteria/obtenerDatosReporte', "ReporteriaController@obtenerDatosReporte")->name("reporteria.obtenerDatosReporte");
    Route::get('reporteria/obtieneRecepcionDatosRecepcion', "ReporteriaController@obtieneRecepcionDatosRecepcion")->name("reporteria.obtieneRecepcionDatosRecepcion");
    Route::post('reporteria/obtieneDatosRecepcionProductor', "ReporteriaController@obtieneDatosRecepcionProductor")->name("reporteria.obtieneDatosRecepcionProductor");
    Route::get('reporteria/obtienePesoxDia', "ReporteriaController@obtienePesoxDia")->name("reporteria.obtienePesoxDia");
    Route::get('reporteria/StockInventarioxSemana', "ReporteriaController@StockInventarioxSemana")->name("reporteria.StockInventarioxSemana");
    Route::get('reporteria/obtieneTransito', "ReporteriaController@obtieneTransito")->name("reporteria.obtieneTransito");
    Route::get('reporteria/Transito', "ReporteriaController@Transito")->name("reporteria.Transito");
    Route::post('reporteria/obtieneDetallesTransito', "ReporteriaController@obtieneDetallesTransito")->name("reporteria.obtieneDetallesTransito");
    Route::post('reporteria/obtieneDetallesTransitoCalibre', "ReporteriaController@obtieneDetallesTransitoCalibre")->name("reporteria.obtieneDetallesTransitoCalibre");
    Route::get('reporteria/embarques', "ReporteriaController@embarques")->name("reporteria.embarques");
    Route::get('reporteria/obtieneEmbarques',"ReporteriaController@obtieneEmbarques")->name("reporteria.obtieneEmbarques");
    Route::get('reporteria/ObjetivosEnvios',"ReporteriaController@ObjetivosEnvios")->name("reporteria.ObjetivosEnvios");
    Route::get('reporteria/ObjetivosEnviosAereos',"ReporteriaController@ObjetivosEnviosAereos")->name("reporteria.ObjetivosEnviosAereos");
    Route::get('reporteria/ObjetivosEnviosTerrestre',"ReporteriaController@ObjetivosEnviosTerrestre")->name("reporteria.ObjetivosEnviosTerrestre");
    Route::get('reporteria/ObtieneEmbarquesyPackingList',"ReporteriaController@ObtieneEmbarquesyPackingList")->name("reporteria.ObtieneEmbarquesyPackingList");
    Route::get('reporteria/detalleembarque',"ReporteriaController@detalleembarque")->name("reporteria.detalleembarque");
    Route::get('reporteria/getPAckingList',"ReporteriaController@getPackingList")->name("reporteria.getPackingList");
    Route::get('reporteria/getClientesComex',"ReporteriaController@getClientesComex")->name("reporteria.getClientesComex");
    Route::get('reporteria/getCantRegistros',"ReporteriaController@getCantRegistros")->name("reporteria.getCantRegistros");
    Route::get('reporteria/getMinMaxCajas', 'ReporteriaController@getMinMaxCajas')->name('reporteria.getMinMaxCajas');
    Route::get('reporteria/detallecajas', 'ReporteriaController@detallecajas')->name('reporteria.detallecajas');
    Route::post('reporteria/SyncDatosCajas', 'ReporteriaController@SyncDatosCajas')->name('reporteria.SyncDatosCajas');
    Route::get('reporteria/SyncDatosCajas2', 'ReporteriaController@SyncDatosCajas2')->name('reporteria.SyncDatosCajas2');
    // Embalajes
    Route::delete('embalajes/destroy', 'EmbalajesController@massDestroy')->name('embalajes.massDestroy');
    Route::post('embalajes/parse-csv-import', 'EmbalajesController@parseCsvImport')->name('embalajes.parseCsvImport');
    Route::post('embalajes/process-csv-import', 'EmbalajesController@processCsvImport')->name('embalajes.processCsvImport');

    Route::resource('embalajes', 'EmbalajesController');

    // Clientes Comex
    Route::delete('clientes-comexes/destroy', 'ClientesComexController@massDestroy')->name('clientes-comexes.massDestroy');
    Route::post('clientes-comexes/parse-csv-import', 'ClientesComexController@parseCsvImport')->name('clientes-comexes.parseCsvImport');
    Route::post('clientes-comexes/process-csv-import', 'ClientesComexController@processCsvImport')->name('clientes-comexes.processCsvImport');
    Route::resource('clientes-comexes', 'ClientesComexController');

     // Metas Cliente Comex
     Route::delete('metas-cliente-comexes/destroy', 'MetasClienteComexController@massDestroy')->name('metas-cliente-comexes.massDestroy');
     Route::post('metas-cliente-comexes/parse-csv-import', 'MetasClienteComexController@parseCsvImport')->name('metas-cliente-comexes.parseCsvImport');
     Route::post('metas-cliente-comexes/process-csv-import', 'MetasClienteComexController@processCsvImport')->name('metas-cliente-comexes.processCsvImport');
     Route::resource('metas-cliente-comexes', 'MetasClienteComexController');

    //getDatosgenerales

    Route::get('reporteria/getDatosgenerales', 'ReporteriaController@getDatosgenerales')->name('reporteria.getDatosgenerales');
    Route::get('reporteria/getSabana', 'ReporteriaController@getSabana')->name('reporteria.getSabana');
    //Route::get('/reporteria', [ReporteriaController::class, 'getDatosgenerales']);

    //Operaciones
    Route::get('operaciones/fusionarFolios', 'OperacionesController@fusionarFolios')->name('operaciones.fusionarFolios');


    // Embarques
    Route::delete('embarques/destroy', 'EmbarquesController@massDestroy')->name('embarques.massDestroy');
    Route::post('embarques/parse-csv-import', 'EmbarquesController@parseCsvImport')->name('embarques.parseCsvImport');
    Route::post('embarques/process-csv-import', 'EmbarquesController@processCsvImport')->name('embarques.processCsvImport');
    Route::get('embarques/ImportarEmbarques', 'EmbarquesController@ImportarEmbarques')->name('embarques.ImportarEmbarques');
    Route::post('embarques/GuardarEmbarques', 'EmbarquesController@GuardarEmbarques')->name('embarques.GuardarEmbarques');
    Route::post('embarques/ActualizaSistemaFX', 'EmbarquesController@ActualizaSistemaFX')->name('embarques.ActualizaSistemaFX');
    Route::get('embarques/enviarMail', 'EmbarquesController@enviarMail')->name('embarques.enviarMail');
    Route::post('embarques/ingresaPackingList','EmbarquesController@ingresaPackingList')->name('embarques.ingresaPackingList');
    Route::get('embarques/ingresagrecepcion', 'EmbarquesController@ingresagrecepcion')->name('embarques.ingresagrecepcion');



    Route::resource('embarques', 'EmbarquesController');


    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Estados
    Route::delete('estados/destroy', 'EstadosController@massDestroy')->name('estados.massDestroy');
    Route::post('estados/media', 'EstadosController@storeMedia')->name('estados.storeMedia');
    Route::post('estados/ckmedia', 'EstadosController@storeCKEditorImages')->name('estados.storeCKEditorImages');
    Route::resource('estados', 'EstadosController');

    // Airline
    Route::delete('airlines/destroy', 'AirlineController@massDestroy')->name('airlines.massDestroy');
    Route::resource('airlines', 'AirlineController');

    // Vuelo
    Route::delete('vuelos/destroy', 'VueloController@massDestroy')->name('vuelos.massDestroy');
    Route::resource('vuelos', 'VueloController');

    // Ciudad
    Route::delete('ciudads/destroy', 'CiudadController@massDestroy')->name('ciudads.massDestroy');
    Route::resource('ciudads', 'CiudadController');

    // Region
    Route::delete('regions/destroy', 'RegionController@massDestroy')->name('regions.massDestroy');
    Route::resource('regions', 'RegionController');

    // Comuna
    Route::delete('comunas/destroy', 'ComunaController@massDestroy')->name('comunas.massDestroy');
    Route::resource('comunas', 'ComunaController');

    // Status
    Route::delete('statuses/destroy', 'StatusController@massDestroy')->name('statuses.massDestroy');
    Route::resource('statuses', 'StatusController');

    // Tipo Hawb
    Route::delete('tipo-hawbs/destroy', 'TipoHawbController@massDestroy')->name('tipo-hawbs.massDestroy');
    Route::resource('tipo-hawbs', 'TipoHawbController');

    // Manifiest
    Route::delete('manifiests/destroy', 'ManifiestController@massDestroy')->name('manifiests.massDestroy');
    Route::resource('manifiests', 'ManifiestController');

    // Guias
    Route::delete('guia/destroy', 'GuiasController@massDestroy')->name('guia.massDestroy');
    Route::resource('guia', 'GuiasController');

    // Importacion Marcas Manifiesto
    Route::delete('importacion-marcas-manifiestos/destroy', 'ImportacionMarcasManifiestoController@massDestroy')->name('importacion-marcas-manifiestos.massDestroy');
    Route::resource('importacion-marcas-manifiestos', 'ImportacionMarcasManifiestoController');

    // Tipo
    Route::delete('tipos/destroy', 'TipoController@massDestroy')->name('tipos.massDestroy');
    Route::resource('tipos', 'TipoController');

    // Tipoequis
    Route::delete('tipoequis/destroy', 'TipoequisController@massDestroy')->name('tipoequis.massDestroy');
    Route::resource('tipoequis', 'TipoequisController');

    // Pre Carga Manifiesto
    Route::delete('pre-carga-manifiestos/destroy', 'PreCargaManifiestoController@massDestroy')->name('pre-carga-manifiestos.massDestroy');
    Route::resource('pre-carga-manifiestos', 'PreCargaManifiestoController');

    // Configuracion
    Route::delete('configuracions/destroy', 'ConfiguracionController@massDestroy')->name('configuracions.massDestroy');
    Route::resource('configuracions', 'ConfiguracionController');

    // Traducciones
    Route::delete('traducciones/destroy', 'TraduccionesController@massDestroy')->name('traducciones.massDestroy');
    Route::resource('traducciones', 'TraduccionesController');

    // Hawb
    Route::delete('hawbs/destroy', 'HawbController@massDestroy')->name('hawbs.massDestroy');
    Route::resource('hawbs', 'HawbController');

    // Dips
    Route::delete('dips/destroy', 'DipsController@massDestroy')->name('dips.massDestroy');
    Route::resource('dips', 'DipsController');

    // Pais
    Route::delete('pais/destroy', 'PaisController@massDestroy')->name('pais.massDestroy');
    Route::resource('pais', 'PaisController');

    // Arancel
    Route::delete('arancels/destroy', 'ArancelController@massDestroy')->name('arancels.massDestroy');
    Route::resource('arancels', 'ArancelController');

    // Tipo Bulto
    Route::delete('tipo-bultos/destroy', 'TipoBultoController@massDestroy')->name('tipo-bultos.massDestroy');
    Route::resource('tipo-bultos', 'TipoBultoController');

    // Tipo De Inspeccion
    Route::delete('tipo-de-inspeccions/destroy', 'TipoDeInspeccionController@massDestroy')->name('tipo-de-inspeccions.massDestroy');
    Route::resource('tipo-de-inspeccions', 'TipoDeInspeccionController');

    // Fiscalizador
    Route::delete('fiscalizadors/destroy', 'FiscalizadorController@massDestroy')->name('fiscalizadors.massDestroy');
    Route::resource('fiscalizadors', 'FiscalizadorController');

    // Almacenista
    Route::delete('almacenista/destroy', 'AlmacenistaController@massDestroy')->name('almacenista.massDestroy');
    Route::resource('almacenista', 'AlmacenistaController');

    // Aduana
    Route::delete('aduanas/destroy', 'AduanaController@massDestroy')->name('aduanas.massDestroy');
    Route::resource('aduanas', 'AduanaController');

    // Puerto
    Route::delete('puertos/destroy', 'PuertoController@massDestroy')->name('puertos.massDestroy');
    Route::resource('puertos', 'PuertoController');

    // Regimen
    Route::delete('regimen/destroy', 'RegimenController@massDestroy')->name('regimen.massDestroy');
    Route::resource('regimen', 'RegimenController');

    // Consignatario
    Route::delete('consignatarios/destroy', 'ConsignatarioController@massDestroy')->name('consignatarios.massDestroy');
    Route::resource('consignatarios', 'ConsignatarioController');

    // Adicionales
    Route::delete('adicionales/destroy', 'AdicionalesController@massDestroy')->name('adicionales.massDestroy');
    Route::resource('adicionales', 'AdicionalesController');

    // Tipo Flete
    Route::delete('tipo-fletes/destroy', 'TipoFleteController@massDestroy')->name('tipo-fletes.massDestroy');
    Route::resource('tipo-fletes', 'TipoFleteController');

    // Buscar Guias Aclaradas
    Route::delete('buscar-guias-aclaradas/destroy', 'BuscarGuiasAclaradasController@massDestroy')->name('buscar-guias-aclaradas.massDestroy');
    Route::resource('buscar-guias-aclaradas', 'BuscarGuiasAclaradasController');

    // Consulta Arancelaria
    Route::delete('consulta-arancelaria/destroy', 'ConsultaArancelariaController@massDestroy')->name('consulta-arancelaria.massDestroy');
    Route::resource('consulta-arancelaria', 'ConsultaArancelariaController');

    // Impresion Dips
    Route::delete('impresion-dips/destroy', 'ImpresionDipsController@massDestroy')->name('impresion-dips.massDestroy');
    Route::resource('impresion-dips', 'ImpresionDipsController');

    // Multi Impresion Dips
    Route::delete('multi-impresion-dips/destroy', 'MultiImpresionDipsController@massDestroy')->name('multi-impresion-dips.massDestroy');
    Route::resource('multi-impresion-dips', 'MultiImpresionDipsController');

    // Dips Aprobadas
    Route::delete('dips-aprobadas/destroy', 'DipsAprobadasController@massDestroy')->name('dips-aprobadas.massDestroy');
    Route::resource('dips-aprobadas', 'DipsAprobadasController');

    // Buscar Batch
    Route::delete('buscar-batches/destroy', 'BuscarBatchController@massDestroy')->name('buscar-batches.massDestroy');
    Route::resource('buscar-batches', 'BuscarBatchController');

    // Guias En Batch
    Route::delete('guias-en-batches/destroy', 'GuiasEnBatchController@massDestroy')->name('guias-en-batches.massDestroy');
    Route::resource('guias-en-batches', 'GuiasEnBatchController');

    // Crear Batch
    Route::delete('crear-batches/destroy', 'CrearBatchController@massDestroy')->name('crear-batches.massDestroy');
    Route::resource('crear-batches', 'CrearBatchController');

    // Hawb Batch
    Route::delete('hawb-batches/destroy', 'HawbBatchController@massDestroy')->name('hawb-batches.massDestroy');
    Route::resource('hawb-batches', 'HawbBatchController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Faq Category
    Route::delete('faq-categories/destroy', 'FaqCategoryController@massDestroy')->name('faq-categories.massDestroy');
    Route::resource('faq-categories', 'FaqCategoryController');

    // Faq Question
    Route::delete('faq-questions/destroy', 'FaqQuestionController@massDestroy')->name('faq-questions.massDestroy');
    Route::resource('faq-questions', 'FaqQuestionController');

    // Courses
    Route::delete('courses/destroy', 'CoursesController@massDestroy')->name('courses.massDestroy');
    Route::post('courses/media', 'CoursesController@storeMedia')->name('courses.storeMedia');
    Route::post('courses/ckmedia', 'CoursesController@storeCKEditorImages')->name('courses.storeCKEditorImages');
    Route::resource('courses', 'CoursesController');

    // Lessons
    Route::delete('lessons/destroy', 'LessonsController@massDestroy')->name('lessons.massDestroy');
    Route::post('lessons/media', 'LessonsController@storeMedia')->name('lessons.storeMedia');
    Route::post('lessons/ckmedia', 'LessonsController@storeCKEditorImages')->name('lessons.storeCKEditorImages');
    Route::resource('lessons', 'LessonsController');

    // Tests
    Route::delete('tests/destroy', 'TestsController@massDestroy')->name('tests.massDestroy');
    Route::resource('tests', 'TestsController');

    // Questions
    Route::delete('questions/destroy', 'QuestionsController@massDestroy')->name('questions.massDestroy');
    Route::post('questions/media', 'QuestionsController@storeMedia')->name('questions.storeMedia');
    Route::post('questions/ckmedia', 'QuestionsController@storeCKEditorImages')->name('questions.storeCKEditorImages');
    Route::resource('questions', 'QuestionsController');

    // Question Options
    Route::delete('question-options/destroy', 'QuestionOptionsController@massDestroy')->name('question-options.massDestroy');
    Route::resource('question-options', 'QuestionOptionsController');

    // Test Results
    Route::delete('test-results/destroy', 'TestResultsController@massDestroy')->name('test-results.massDestroy');
    Route::resource('test-results', 'TestResultsController');

    // Test Answers
    Route::delete('test-answers/destroy', 'TestAnswersController@massDestroy')->name('test-answers.massDestroy');
    Route::resource('test-answers', 'TestAnswersController');

    // Task Status
    Route::delete('task-statuses/destroy', 'TaskStatusController@massDestroy')->name('task-statuses.massDestroy');
    Route::resource('task-statuses', 'TaskStatusController');

    // Task Tag
    Route::delete('task-tags/destroy', 'TaskTagController@massDestroy')->name('task-tags.massDestroy');
    Route::resource('task-tags', 'TaskTagController');

    // Task
    Route::delete('tasks/destroy', 'TaskController@massDestroy')->name('tasks.massDestroy');
    Route::post('tasks/media', 'TaskController@storeMedia')->name('tasks.storeMedia');
    Route::post('tasks/ckmedia', 'TaskController@storeCKEditorImages')->name('tasks.storeCKEditorImages');
    Route::resource('tasks', 'TaskController');

    // Tasks Calendar
    Route::resource('tasks-calendars', 'TasksCalendarController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Inhumados
    Route::delete('inhumados/destroy', 'InhumadosController@massDestroy')->name('inhumados.massDestroy');
    Route::resource('inhumados', 'InhumadosController');

    // Cliente
    Route::delete('clientes/destroy', 'ClienteController@massDestroy')->name('clientes.massDestroy');
    Route::resource('clientes', 'ClienteController');

    // Dussi
    Route::delete('dussis/destroy', 'DussiController@massDestroy')->name('dussis.massDestroy');
    Route::resource('dussis', 'DussiController');

    // Batch
    Route::delete('batches/destroy', 'BatchController@massDestroy')->name('batches.massDestroy');
    Route::resource('batches', 'BatchController');

    // Aclaracionguia
    Route::delete('aclaracionguia/destroy', 'AclaracionguiaController@massDestroy')->name('aclaracionguia.massDestroy');
    Route::resource('aclaracionguia', 'AclaracionguiaController');

    // Ingresos
    Route::delete('ingresos/destroy', 'IngresosController@massDestroy')->name('ingresos.massDestroy');
    Route::resource('ingresos', 'IngresosController');

    // Guias Dussi
    Route::delete('guias-dussis/destroy', 'GuiasDussiController@massDestroy')->name('guias-dussis.massDestroy');
    Route::resource('guias-dussis', 'GuiasDussiController');

    // Ingreso Bulto
    Route::delete('ingreso-bultos/destroy', 'IngresoBultoController@massDestroy')->name('ingreso-bultos.massDestroy');
    Route::resource('ingreso-bultos', 'IngresoBultoController');

    // Estados Saep
    Route::delete('estados-saeps/destroy', 'EstadosSaepController@massDestroy')->name('estados-saeps.massDestroy');
    Route::resource('estados-saeps', 'EstadosSaepController');

    // Motivo Saep
    Route::delete('motivo-saeps/destroy', 'MotivoSaepController@massDestroy')->name('motivo-saeps.massDestroy');
    Route::resource('motivo-saeps', 'MotivoSaepController');

    // Bodega
    Route::delete('bodegas/destroy', 'BodegaController@massDestroy')->name('bodegas.massDestroy');
    Route::resource('bodegas', 'BodegaController');

    // Ingreso Cadena Custodia
    Route::delete('ingreso-cadena-custodia/destroy', 'IngresoCadenaCustodiaController@massDestroy')->name('ingreso-cadena-custodia.massDestroy');
    Route::resource('ingreso-cadena-custodia', 'IngresoCadenaCustodiaController');

    // Estado Abandono
    Route::delete('estado-abandonos/destroy', 'EstadoAbandonoController@massDestroy')->name('estado-abandonos.massDestroy');
    Route::resource('estado-abandonos', 'EstadoAbandonoController');

    // Correlativo
    Route::delete('correlativos/destroy', 'CorrelativoController@massDestroy')->name('correlativos.massDestroy');
    Route::resource('correlativos', 'CorrelativoController');

    // Datos Caja
    Route::delete('datos-cajas/destroy', 'DatosCajaController@massDestroy')->name('datos-cajas.massDestroy');
    Route::resource('datos-cajas', 'DatosCajaController');

    // Entidad
    Route::delete('entidads/destroy', 'EntidadController@massDestroy')->name('entidads.massDestroy');
    Route::resource('entidads', 'EntidadController');

    // Area
    Route::delete('areas/destroy', 'AreaController@massDestroy')->name('areas.massDestroy');
    Route::resource('areas', 'AreaController');

    // Locacion
    Route::delete('locacions/destroy', 'LocacionController@massDestroy')->name('locacions.massDestroy');
    Route::resource('locacions', 'LocacionController');

    // Turno
    Route::delete('turnos/destroy', 'TurnoController@massDestroy')->name('turnos.massDestroy');
    Route::resource('turnos', 'TurnoController');

    // Frecuencia Turno
    Route::delete('frecuencia-turnos/destroy', 'FrecuenciaTurnoController@massDestroy')->name('frecuencia-turnos.massDestroy');
    Route::resource('frecuencia-turnos', 'FrecuenciaTurnoController');

    // Cargo
    Route::delete('cargos/destroy', 'CargoController@massDestroy')->name('cargos.massDestroy');
    Route::resource('cargos', 'CargoController');

    // Personal
    Route::delete('personals/destroy', 'PersonalController@massDestroy')->name('personals.massDestroy');
    Route::resource('personals', 'PersonalController');

    // Turnos Frecuencia
    Route::delete('turnos-frecuencia/destroy', 'TurnosFrecuenciaController@massDestroy')->name('turnos-frecuencia.massDestroy');
    Route::resource('turnos-frecuencia', 'TurnosFrecuenciaController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');


    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Estados
    Route::delete('estados/destroy', 'EstadosController@massDestroy')->name('estados.massDestroy');
    Route::post('estados/media', 'EstadosController@storeMedia')->name('estados.storeMedia');
    Route::post('estados/ckmedia', 'EstadosController@storeCKEditorImages')->name('estados.storeCKEditorImages');
    Route::resource('estados', 'EstadosController');

    // Datos Caja
    Route::delete('datos-cajas/destroy', 'DatosCajaController@massDestroy')->name('datos-cajas.massDestroy');
    Route::resource('datos-cajas', 'DatosCajaController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});
