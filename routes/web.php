<?php

//use Illuminate\Routing\Route;
use App\Http\Controllers\Admin\ReporteriaController;

use App\Http\Controllers\Admin\TarjadoController;
use App\Models\TratoContratistas;
//use App\Http\admin\Controllers\ZktecoController;
//use Google\Service\CloudRun\Route;

//use Illuminate\Routing\Route;

//use Illuminate\Routing\Route;

//use Illuminate\Routing\Route;
// Ruta para recibir datos PUSH (POST requests)
Route::post('/iclock/cdata',  'ZktecoController@receiveData')->name('iclock.cdata');

// Ruta para manejar peticiones GET (heartbeats o para enviar comandos al dispositivo)
Route::get('/iclock/cdata', 'ZktecoController@handleIClockGet')->name('iclock.cdata');
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
    Route::get('personals/tratoembalaje', 'PersonalController@tratoembalaje')->name('personals.tratoembalaje');
    Route::post('personals/ejecutaTratoembalaje', 'PersonalController@ejecutaTratoembalaje')->name('personals.ejecutaTratoembalaje');
    Route::get('personals/tratoContratista', 'PersonalController@tratoContratista')->name('personals.tratoContratista');
    Route::post('personals/guardatratohandpack', 'PersonalController@guardatratohandpack')->name('personals.guardatratohandpack');
    Route::get('personals/destroyhandpack/{id}', 'PersonalController@destroyhandpack')->name('personals.destroyhandpack');
    Route::get('personals/downloadtrato', 'PersonalController@downloadtrato')->name('personals.downloadtrato');
    Route::post('personals/uploadtrato', 'PersonalController@uploadtrato')->name('personals.uploadtrato');

    Route::post('personals/consultahandpack', 'PersonalController@consultahandpack')->name('personals.consultahandpack');

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
    Route::get('reporteria/obtieneEmbarques', "ReporteriaController@obtieneEmbarques")->name("reporteria.obtieneEmbarques");
    Route::get('reporteria/ObjetivosEnvios', "ReporteriaController@ObjetivosEnvios")->name("reporteria.ObjetivosEnvios");
    Route::get('reporteria/ObjetivosEnviosAereos', "ReporteriaController@ObjetivosEnviosAereos")->name("reporteria.ObjetivosEnviosAereos");
    Route::get('reporteria/ObjetivosEnviosTerrestre', "ReporteriaController@ObjetivosEnviosTerrestre")->name("reporteria.ObjetivosEnviosTerrestre");
    Route::get('reporteria/ObtieneEmbarquesyPackingList', "ReporteriaController@ObtieneEmbarquesyPackingList")->name("reporteria.ObtieneEmbarquesyPackingList");
    Route::get('reporteria/detalleembarque', "ReporteriaController@detalleembarque")->name("reporteria.detalleembarque");
    Route::get('reporteria/getPAckingList', "ReporteriaController@getPackingList")->name("reporteria.getPackingList");
    Route::get('reporteria/getClientesComex', "ReporteriaController@getClientesComex")->name("reporteria.getClientesComex");
    Route::get('reporteria/getCantRegistros', "ReporteriaController@getCantRegistros")->name("reporteria.getCantRegistros");
    Route::get('reporteria/getMinMaxCajas', 'ReporteriaController@getMinMaxCajas')->name('reporteria.getMinMaxCajas');
    Route::get('reporteria/detallecajas', 'ReporteriaController@detallecajas')->name('reporteria.detallecajas');
    Route::get('reporteria/SyncDatosCajas', 'ReporteriaController@SyncDatosCajas')->name('reporteria.SyncDatosCajas');
    Route::get('reporteria/SyncDatosCajas2', 'ReporteriaController@SyncDatosCajas2')->name('reporteria.SyncDatosCajas2');
    Route::get('reporteria/liquidacionesventa', 'ReporteriaController@liquidacionesventa')->name('reporteria.liquidacionesventa');
    Route::get('reporteria/getLiquidaciones', 'ReporteriaController@getLiquidaciones')->name('reporteria.getLiquidaciones');
    Route::get('reporteria/getDetallesInstructivo', 'ReporteriaController@getDetallesInstructivo')->name('reporteria.getDetallesInstructivo');
    Route::get('reporteria/compartivoliquidacionescx', 'ReporteriaController@compartivoliquidacionescx')->name('reporteria.compartivoliquidacionescx');
    Route::get('reporteria/DataLiquidaciones', 'ReporteriaController@DataLiquidaciones')->name('reporteria.DataLiquidaciones');
    Route::get('reporteria/ObtieneDatosFOB', 'ReporteriaController@ObtieneDatosFOB')->name('reporteria.ObtieneDatosFOB');
    Route::get('reporteria/obtieneFolio', 'ReporteriaController@obtieneFolio')->name('reporteria.obtieneFolio');
    Route::get('reporteria/obtenerliquidacionesagrupadas', 'ReporteriaController@obtenerliquidacionesagrupadas')->name('reporteria.obtenerliquidacionesagrupadas');
    Route::get('reporteria/getReporteInstructivos', 'ReporteriaController@getReporteInstructivos')->name('reporteria.getReporteInstructivos');
    Route::get('reporteria/SabanaLiquidaciones', 'ReporteriaController@SabanaLiquidaciones')->name('reporteria.SabanaLiquidaciones');
    Route::get('reporteria/access-report','ReporteriaController@SabanaLiquidaciones')->name('reporteria.accessreport');
    Route::post('reporteria/obtenerComparativo', 'ReporteriaController@obtenerComparativo')->name('reporteria.obtenerComparativo');
    Route::get('reporteria/obtenerDataComparativaInicial', 'ReporteriaController@obtenerDataComparativaInicial')->name('reporteria.obtenerDataComparativaInicial');
    Route::post('reporteria/obtenerRankingOportunidad', 'ReporteriaController@obtenerRankingOportunidad')->name('reporteria.obtenerRankingOportunidad');
    Route::get('reporteria/ObtenerPaises', 'ReporteriaController@ObtenerPaises')->name('reporteria.ObtenerPaises');
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


    Route::get('comex/capturador', 'ComexController@capturador')->name('comex.capturador');
    Route::post('comex/capturadorexcel', 'ComexController@capturadorexcel')->name('comex.capturadorexcel');
    Route::post('comex/guardaliquidacion', 'ComexController@guardaliquidacion')->name('comex.guardaliquidacion');
    Route::post('comex/generacomparativa', 'ComexController@generacomparativa')->name('comex.generacomparativa');
    Route::post('comex/generacomparativaglobal', 'ComexController@generacomparativaglobal')->name('comex.generacomparativaglobal');

    Route::post('comex/eliminardatosExcel', 'ComexController@eliminardatosExcel')->name('comex.eliminardatosExcel');
    Route::get('comex/actualizarValorGD_en_fx', 'ComexController@actualizarValorGD_en_fx')->name('comex.actualizarValorGD_en_fx');

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
    Route::post('embarques/ingresaPackingList', 'EmbarquesController@ingresaPackingList')->name('embarques.ingresaPackingList');
    Route::get('embarques/ingresagrecepcion', 'EmbarquesController@ingresagrecepcion')->name('embarques.ingresagrecepcion');
    Route::resource('embarques', 'EmbarquesController');

    // Countries
    Route::delete('countries/destroy', 'CountriesController@massDestroy')->name('countries.massDestroy');
    Route::post('countries/parse-csv-import', 'CountriesController@parseCsvImport')->name('countries.parseCsvImport');
    Route::post('countries/process-csv-import', 'CountriesController@processCsvImport')->name('countries.processCsvImport');
    Route::resource('countries', 'CountriesController');

    // Puerto
    Route::delete('puertos/destroy', 'PuertoController@massDestroy')->name('puertos.massDestroy');
    Route::post('puertos/parse-csv-import', 'PuertoController@parseCsvImport')->name('puertos.parseCsvImport');
    Route::post('puertos/process-csv-import', 'PuertoController@processCsvImport')->name('puertos.processCsvImport');
    Route::resource('puertos', 'PuertoController');

    // Familia
    Route::delete('familia/destroy', 'FamiliaController@massDestroy')->name('familia.massDestroy');
    Route::post('familia/parse-csv-import', 'FamiliaController@parseCsvImport')->name('familia.parseCsvImport');
    Route::post('familia/process-csv-import', 'FamiliaController@processCsvImport')->name('familia.processCsvImport');
    Route::resource('familia', 'FamiliaController');

    // Especie
    Route::delete('especies/destroy', 'EspecieController@massDestroy')->name('especies.massDestroy');
    Route::post('especies/parse-csv-import', 'EspecieController@parseCsvImport')->name('especies.parseCsvImport');
    Route::post('especies/process-csv-import', 'EspecieController@processCsvImport')->name('especies.processCsvImport');
    Route::resource('especies', 'EspecieController');

    // Variedad
    Route::delete('variedads/destroy', 'VariedadController@massDestroy')->name('variedads.massDestroy');
    Route::post('variedads/parse-csv-import', 'VariedadController@parseCsvImport')->name('variedads.parseCsvImport');
    Route::post('variedads/process-csv-import', 'VariedadController@processCsvImport')->name('variedads.processCsvImport');
    Route::resource('variedads', 'VariedadController');

    // Categoria
    Route::delete('categoria/destroy', 'CategoriaController@massDestroy')->name('categoria.massDestroy');
    Route::post('categoria/parse-csv-import', 'CategoriaController@parseCsvImport')->name('categoria.parseCsvImport');
    Route::post('categoria/process-csv-import', 'CategoriaController@processCsvImport')->name('categoria.processCsvImport');
    Route::resource('categoria', 'CategoriaController');

    // Etiqueta
    Route::delete('etiqueta/destroy', 'EtiquetaController@massDestroy')->name('etiqueta.massDestroy');
    Route::post('etiqueta/parse-csv-import', 'EtiquetaController@parseCsvImport')->name('etiqueta.parseCsvImport');
    Route::post('etiqueta/process-csv-import', 'EtiquetaController@processCsvImport')->name('etiqueta.processCsvImport');
    Route::resource('etiqueta', 'EtiquetaController');

    // Etiquetas X Especie
    Route::delete('etiquetas-x-especies/destroy', 'EtiquetasXEspecieController@massDestroy')->name('etiquetas-x-especies.massDestroy');
    Route::post('etiquetas-x-especies/parse-csv-import', 'EtiquetasXEspecieController@parseCsvImport')->name('etiquetas-x-especies.parseCsvImport');
    Route::post('etiquetas-x-especies/process-csv-import', 'EtiquetasXEspecieController@processCsvImport')->name('etiquetas-x-especies.processCsvImport');
    Route::resource('etiquetas-x-especies', 'EtiquetasXEspecieController');

    // Naves
    Route::delete('naves/destroy', 'NavesController@massDestroy')->name('naves.massDestroy');
    Route::post('naves/parse-csv-import', 'NavesController@parseCsvImport')->name('naves.parseCsvImport');
    Route::post('naves/process-csv-import', 'NavesController@processCsvImport')->name('naves.processCsvImport');
    Route::resource('naves', 'NavesController');

    // Item Embalaje
    Route::delete('item-embalajes/destroy', 'ItemEmbalajeController@massDestroy')->name('item-embalajes.massDestroy');
    Route::post('item-embalajes/parse-csv-import', 'ItemEmbalajeController@parseCsvImport')->name('item-embalajes.parseCsvImport');
    Route::post('item-embalajes/process-csv-import', 'ItemEmbalajeController@processCsvImport')->name('item-embalajes.processCsvImport');
    Route::resource('item-embalajes', 'ItemEmbalajeController');

    // Costo
    Route::delete('costos/destroy', 'CostoController@massDestroy')->name('costos.massDestroy');
    Route::post('costos/parse-csv-import', 'CostoController@parseCsvImport')->name('costos.parseCsvImport');
    Route::post('costos/process-csv-import', 'CostoController@processCsvImport')->name('costos.processCsvImport');
    Route::resource('costos', 'CostoController');

    // Configuracion
    Route::delete('configuracions/destroy', 'ConfiguracionController@massDestroy')->name('configuracions.massDestroy');
    Route::post('configuracions/parse-csv-import', 'ConfiguracionController@parseCsvImport')->name('configuracions.parseCsvImport');
    Route::post('configuracions/process-csv-import', 'ConfiguracionController@processCsvImport')->name('configuracions.processCsvImport');
    Route::resource('configuracions', 'ConfiguracionController');

    // Diccionario
    Route::delete('diccionarios/destroy', 'DiccionarioController@massDestroy')->name('diccionarios.massDestroy');
    Route::post('diccionarios/parse-csv-import', 'DiccionarioController@parseCsvImport')->name('diccionarios.parseCsvImport');
    Route::post('diccionarios/process-csv-import', 'DiccionarioController@processCsvImport')->name('diccionarios.processCsvImport');
    Route::resource('diccionarios', 'DiccionarioController');


    Route::get('costosorigen/costosorigen', 'CostosOrigenController@costosorigen')->name('costosorigen.costosorigen');
    Route::post('costosorigen/guardacostosorigen', 'CostosOrigenController@guardacostosorigen')->name('costosorigen.guardacostosorigen');

    // Hand Pack
    Route::delete('hand-packs/destroy', 'HandPackController@massDestroy')->name('hand-packs.massDestroy');
    Route::post('hand-packs/parse-csv-import', 'HandPackController@parseCsvImport')->name('hand-packs.parseCsvImport');
    Route::post('hand-packs/process-csv-import', 'HandPackController@processCsvImport')->name('hand-packs.processCsvImport');
    Route::get('hand-packs/lector','HandPackController@lector')->name('hand-packs.lector');
    Route::post('hand-packs/lectorQr','HandPackController@lectorQr')->name('hand-packs.lectorQr');
    Route::resource('hand-packs', 'HandPackController');

    // // Conversor Xls
    // Route::delete('conversor-xls/destroy', 'ConversorXlsController@massDestroy')->name('conversor-xls.massDestroy');
    // Route::post('conversor-xls/parse-csv-import', 'ConversorXlsController@parseCsvImport')->name('conversor-xls.parseCsvImport');
    // Route::post('conversor-xls/process-csv-import', 'ConversorXlsController@processCsvImport')->name('conversor-xls.processCsvImport');
    // Route::resource('conversor-xls', 'ConversorXlsController');

    // Tipos Seccion Conversor
    Route::delete('tipos-seccion-conversors/destroy', 'TiposSeccionConversorController@massDestroy')->name('tipos-seccion-conversors.massDestroy');
    Route::post('tipos-seccion-conversors/parse-csv-import', 'TiposSeccionConversorController@parseCsvImport')->name('tipos-seccion-conversors.parseCsvImport');
    Route::post('tipos-seccion-conversors/process-csv-import', 'TiposSeccionConversorController@processCsvImport')->name('tipos-seccion-conversors.processCsvImport');
    Route::resource('tipos-seccion-conversors', 'TiposSeccionConversorController');

    // Funciones
    Route::delete('funciones/destroy', 'FuncionesController@massDestroy')->name('funciones.massDestroy');
    Route::post('funciones/parse-csv-import', 'FuncionesController@parseCsvImport')->name('funciones.parseCsvImport');
    Route::post('funciones/process-csv-import', 'FuncionesController@processCsvImport')->name('funciones.processCsvImport');
    Route::resource('funciones', 'FuncionesController');

    // Modulo
    Route::delete('modulos/destroy', 'ModuloController@massDestroy')->name('modulos.massDestroy');
    Route::post('modulos/parse-csv-import', 'ModuloController@parseCsvImport')->name('modulos.parseCsvImport');
    Route::post('modulos/process-csv-import', 'ModuloController@processCsvImport')->name('modulos.processCsvImport');
    Route::resource('modulos', 'ModuloController');

    // Capturador
    Route::delete('capturadors/destroy', 'CapturadorController@massDestroy')->name('capturadors.massDestroy');
    Route::post('capturadors/parse-csv-import', 'CapturadorController@parseCsvImport')->name('capturadors.parseCsvImport');
    Route::post('capturadors/process-csv-import', 'CapturadorController@processCsvImport')->name('capturadors.processCsvImport');
    Route::resource('capturadors', 'CapturadorController');

    // Capturador Estructura
    Route::delete('capturador-estructuras/destroy', 'CapturadorEstructuraController@massDestroy')->name('capturador-estructuras.massDestroy');
    Route::post('capturador-estructuras/parse-csv-import', 'CapturadorEstructuraController@parseCsvImport')->name('capturador-estructuras.parseCsvImport');
    Route::post('capturador-estructuras/process-csv-import', 'CapturadorEstructuraController@processCsvImport')->name('capturador-estructuras.processCsvImport');

    Route::resource('capturador-estructuras', 'CapturadorEstructuraController');


    //GoogleSheets



    //Liquidaciones de Cliente

    //Controlador Central de Liquidaciones de Cliente


    // Liq Cx Cabecera
    Route::delete('liq-cx-cabeceras/destroy', 'LiqCxCabeceraController@massDestroy')->name('liq-cx-cabeceras.massDestroy');
    Route::post('liq-cx-cabeceras/parse-csv-import', 'LiqCxCabeceraController@parseCsvImport')->name('liq-cx-cabeceras.parseCsvImport');
    Route::post('liq-cx-cabeceras/process-csv-import', 'LiqCxCabeceraController@processCsvImport')->name('liq-cx-cabeceras.processCsvImport');
    Route::get('liq-cx-cabeceras/getDatosLiqItems', 'LiqCxCabeceraController@getDatosLiqItems')->name('liq-cx-cabeceras.getDatosLiqItems');
    Route::get('liq-cx-cabeceras/getDatosLiqCostos', 'LiqCxCabeceraController@getDatosLiqCostos')->name('liq-cx-cabeceras.getDatosLiqCostos');
    Route::post('liq-cx-cabeceras/update-inline', 'LiqCxCabeceraController@updateInline')->name('liq-cx-cabeceras.updateInline');
    Route::get('liq-cx-cabeceras/destroyItem/{id}', 'LiqCxCabeceraController@destroyItem')->name('liq-cx-cabeceras.destroyItem');
    Route::get('liq-cx-cabeceras/destroyCosto/{id}', 'LiqCxCabeceraController@destroyCosto')->name('liq-cx-cabeceras.destroyCosto');
    Route::post('liq-cx-cabeceras/cloneItem', 'LiqCxCabeceraController@cloneItem')->name('liq-cx-cabeceras.cloneItem');
    Route::post('liq-cx-cabeceras/sinproceso', 'LiqCxCabeceraController@sinproceso')->name('liq-cx-cabeceras.sinproceso');
    Route::get('liq-cx-cabeceras/actualizarValorGD_Unitario/{id}', 'LiqCxCabeceraController@actualizarValorGD_Unitario')->name('liq-cx-cabeceras.actualizarValorGD_Unitario');
    Route::get('liq-cx-cabeceras/actualizarValorCliente', 'LiqCxCabeceraController@actualizarValorCliente')->name('liq-cx-cabeceras.actualizarValorCliente');
    Route::get('liq-cx-cabeceras/pdfliq', 'LiqCxCabeceraController@pdfliq')->name('liq-cx-cabeceras.pdfliq');
    Route::get('liq-cx-cabeceras/pdf', 'LiqCxCabeceraController@pdf')->name('liq-cx-cabeceras.pdf');
    Route::get('liq-cx-cabeceras/getgraphs/{productor}', 'LiqCxCabeceraController@getgraphs')->name('liq-cx-cabeceras.getgraphs');
    Route::get('liq-cx-cabeceras/selprods', 'LiqCxCabeceraController@selprods')->name('liq-cx-cabeceras.selprods');

    Route::get('liq-cx-cabeceras/comparativoliquidaciones', 'LiqCxCabeceraController@comparativoliquidaciones')->name('liq-cx-cabeceras.comparativoliquidaciones');

    Route::post('liq-cx-cabeceras/download-pdf/{productor}', 'LiqCxCabeceraController@downloadChartsPdf')->name('liq-cx-cabeceras.download-pdf');
    Route::get('liq-cx-cabeceras/generateZip', 'LiqCxCabeceraController@generateZip')->name('liq-cx-cabeceras.generateZip');
    Route::resource('liq-cx-cabeceras', 'LiqCxCabeceraController');

    // Liquidaciones Cx
    Route::delete('liquidaciones-cxes/destroy', 'LiquidacionesCxController@massDestroy')->name('liquidaciones-cxes.massDestroy');
    Route::post('liquidaciones-cxes/parse-csv-import', 'LiquidacionesCxController@parseCsvImport')->name('liquidaciones-cxes.parseCsvImport');
    Route::post('liquidaciones-cxes/process-csv-import', 'LiquidacionesCxController@processCsvImport')->name('liquidaciones-cxes.processCsvImport');

    Route::resource('liquidaciones-cxes', 'LiquidacionesCxController');

    // Liq Costos
    Route::delete('liq-costos/destroy', 'LiqCostosController@massDestroy')->name('liq-costos.massDestroy');
    Route::post('liq-costos/parse-csv-import', 'LiqCostosController@parseCsvImport')->name('liq-costos.parseCsvImport');
    Route::post('liq-costos/process-csv-import', 'LiqCostosController@processCsvImport')->name('liq-costos.processCsvImport');
    Route::post('liq-costos/updatecosto', 'LiqCostosController@updatecosto')->name('liq-costos.updatecosto');
    Route::resource('liq-costos', 'LiqCostosController');

    //Fin Liquidaciones de Cliente
    // Proveedor
    Route::delete('proveedors/destroy', 'ProveedorController@massDestroy')->name('proveedors.massDestroy');
    Route::post('proveedors/parse-csv-import', 'ProveedorController@parseCsvImport')->name('proveedors.parseCsvImport');
    Route::post('proveedors/process-csv-import', 'ProveedorController@processCsvImport')->name('proveedors.processCsvImport');
    Route::resource('proveedors', 'ProveedorController');


    // Base Recibidor
    Route::delete('base-recibidors/destroy', 'BaseRecibidorController@massDestroy')->name('base-recibidors.massDestroy');
    Route::post('base-recibidors/parse-csv-import', 'BaseRecibidorController@parseCsvImport')->name('base-recibidors.parseCsvImport');
    Route::post('base-recibidors/process-csv-import', 'BaseRecibidorController@processCsvImport')->name('base-recibidors.processCsvImport');
    Route::resource('base-recibidors', 'BaseRecibidorController');

    // Base Contacto
    Route::delete('base-contactos/destroy', 'BaseContactoController@massDestroy')->name('base-contactos.massDestroy');
    Route::post('base-contactos/parse-csv-import', 'BaseContactoController@parseCsvImport')->name('base-contactos.parseCsvImport');
    Route::post('base-contactos/process-csv-import', 'BaseContactoController@processCsvImport')->name('base-contactos.processCsvImport');
    Route::resource('base-contactos', 'BaseContactoController');

    // Agente Aduana
    Route::delete('agente-aduanas/destroy', 'AgenteAduanaController@massDestroy')->name('agente-aduanas.massDestroy');
    Route::post('agente-aduanas/parse-csv-import', 'AgenteAduanaController@parseCsvImport')->name('agente-aduanas.parseCsvImport');
    Route::post('agente-aduanas/process-csv-import', 'AgenteAduanaController@processCsvImport')->name('agente-aduanas.processCsvImport');
    Route::resource('agente-aduanas', 'AgenteAduanaController');

    // Puerto Correo
    Route::delete('puerto-correos/destroy', 'PuertoCorreoController@massDestroy')->name('puerto-correos.massDestroy');
    Route::post('puerto-correos/parse-csv-import', 'PuertoCorreoController@parseCsvImport')->name('puerto-correos.parseCsvImport');
    Route::post('puerto-correos/process-csv-import', 'PuertoCorreoController@processCsvImport')->name('puerto-correos.processCsvImport');
    Route::resource('puerto-correos', 'PuertoCorreoController');

    // Embarcador
    Route::delete('embarcadors/destroy', 'EmbarcadorController@massDestroy')->name('embarcadors.massDestroy');
    Route::post('embarcadors/parse-csv-import', 'EmbarcadorController@parseCsvImport')->name('embarcadors.parseCsvImport');
    Route::post('embarcadors/process-csv-import', 'EmbarcadorController@processCsvImport')->name('embarcadors.processCsvImport');
    Route::resource('embarcadors', 'EmbarcadorController');

    // Chofer
    Route::delete('chofers/destroy', 'ChoferController@massDestroy')->name('chofers.massDestroy');
    Route::post('chofers/parse-csv-import', 'ChoferController@parseCsvImport')->name('chofers.parseCsvImport');
    Route::post('chofers/process-csv-import', 'ChoferController@processCsvImport')->name('chofers.processCsvImport');
    Route::get('chofers/getchofer', 'ChoferController@getchofer')->name('chofers.getchofer');
    Route::post('chofers/guardachofer', 'ChoferController@guardachofer')->name('chofers.guardachofer');
    Route::resource('chofers', 'ChoferController');

    // Planta Carga
    Route::delete('planta-cargas/destroy', 'PlantaCargaController@massDestroy')->name('planta-cargas.massDestroy');
    Route::post('planta-cargas/parse-csv-import', 'PlantaCargaController@parseCsvImport')->name('planta-cargas.parseCsvImport');
    Route::post('planta-cargas/process-csv-import', 'PlantaCargaController@processCsvImport')->name('planta-cargas.processCsvImport');
    Route::resource('planta-cargas', 'PlantaCargaController');

    // Peso Embalaje
    Route::delete('peso-embalajes/destroy', 'PesoEmbalajeController@massDestroy')->name('peso-embalajes.massDestroy');
    Route::post('peso-embalajes/parse-csv-import', 'PesoEmbalajeController@parseCsvImport')->name('peso-embalajes.parseCsvImport');
    Route::post('peso-embalajes/process-csv-import', 'PesoEmbalajeController@processCsvImport')->name('peso-embalajes.processCsvImport');
    Route::resource('peso-embalajes', 'PesoEmbalajeController');

    // Naviera
    Route::delete('navieras/destroy', 'NavieraController@massDestroy')->name('navieras.massDestroy');
    Route::post('navieras/parse-csv-import', 'NavieraController@parseCsvImport')->name('navieras.parseCsvImport');
    Route::post('navieras/process-csv-import', 'NavieraController@processCsvImport')->name('navieras.processCsvImport');
    Route::resource('navieras', 'NavieraController');

    // Condpago
    Route::delete('condpagos/destroy', 'CondpagoController@massDestroy')->name('condpagos.massDestroy');
    Route::post('condpagos/parse-csv-import', 'CondpagoController@parseCsvImport')->name('condpagos.parseCsvImport');
    Route::post('condpagos/process-csv-import', 'CondpagoController@processCsvImport')->name('condpagos.processCsvImport');
    Route::resource('condpagos', 'CondpagoController');

    // Correoalso Air
    Route::delete('correoalso-airs/destroy', 'CorreoalsoAirController@massDestroy')->name('correoalso-airs.massDestroy');
    Route::post('correoalso-airs/parse-csv-import', 'CorreoalsoAirController@parseCsvImport')->name('correoalso-airs.parseCsvImport');
    Route::post('correoalso-airs/process-csv-import', 'CorreoalsoAirController@processCsvImport')->name('correoalso-airs.processCsvImport');
    Route::resource('correoalso-airs', 'CorreoalsoAirController');

    // Instructivo Embarque
    Route::delete('instructivo-embarques/destroy', 'InstructivoEmbarqueController@massDestroy')->name('instructivo-embarques.massDestroy');
    Route::post('instructivo-embarques/parse-csv-import', 'InstructivoEmbarqueController@parseCsvImport')->name('instructivo-embarques.parseCsvImport');
    Route::post('instructivo-embarques/process-csv-import', 'InstructivoEmbarqueController@processCsvImport')->name('instructivo-embarques.processCsvImport');
    Route::get('instructivo-embarques/download/{id}', 'InstructivoEmbarqueController@download')->name('instructivo-embarques.download');
    Route::get('instructivo-embarques/getchoferbyid/{id}', 'InstructivoEmbarqueController@getchoferbyid')->name('instructivo-embarques.getchoferbyid');
    Route::get('instructivo-embarques/getembarcadorbyid/{id}', 'InstructivoEmbarqueController@getembarcadorbyid')->name('instructivo-embarques.getembarcadorbyid');
    Route::get('instructivo-embarques/getagente_aduana/{id}', 'InstructivoEmbarqueController@getagente_aduana')->name('instructivo-embarques.getagente_aduana');
    Route::get('instructivo-embarques/getpuertocorreobyid/{id}', 'InstructivoEmbarqueController@getpuertocorreobyid')->name('instructivo-embarques.getpuertocorreobyid');
    Route::get('instructivo-embarques/getplantacarga/{id}', 'InstructivoEmbarqueController@getplantacarga')->name('instructivo-embarques.getplantacarga');
    Route::post('instructivo-embarques/send-email/{instructivoEmbarque}', 'InstructivoEmbarqueController@sendEmailWithExcel')->name('instructivo-embarques.send-email');


    Route::resource('instructivo-embarques', 'InstructivoEmbarqueController');

    // Tipoflete
    Route::delete('tipofletes/destroy', 'TipofleteController@massDestroy')->name('tipofletes.massDestroy');
    Route::post('tipofletes/parse-csv-import', 'TipofleteController@parseCsvImport')->name('tipofletes.parseCsvImport');
    Route::post('tipofletes/process-csv-import', 'TipofleteController@processCsvImport')->name('tipofletes.processCsvImport');
    Route::resource('tipofletes', 'TipofleteController');

    // Emision Bl
    Route::delete('emision-bls/destroy', 'EmisionBlController@massDestroy')->name('emision-bls.massDestroy');
    Route::post('emision-bls/parse-csv-import', 'EmisionBlController@parseCsvImport')->name('emision-bls.parseCsvImport');
    Route::post('emision-bls/process-csv-import', 'EmisionBlController@processCsvImport')->name('emision-bls.processCsvImport');
    Route::resource('emision-bls', 'EmisionBlController');

    // Forma Pago
    Route::delete('forma-pagos/destroy', 'FormaPagoController@massDestroy')->name('forma-pagos.massDestroy');
    Route::post('forma-pagos/parse-csv-import', 'FormaPagoController@parseCsvImport')->name('forma-pagos.parseCsvImport');
    Route::post('forma-pagos/process-csv-import', 'FormaPagoController@processCsvImport')->name('forma-pagos.processCsvImport');
    Route::resource('forma-pagos', 'FormaPagoController');

    // Mod Venta
    Route::delete('mod-venta/destroy', 'ModVentaController@massDestroy')->name('mod-venta.massDestroy');
    Route::post('mod-venta/parse-csv-import', 'ModVentaController@parseCsvImport')->name('mod-venta.parseCsvImport');
    Route::post('mod-venta/process-csv-import', 'ModVentaController@processCsvImport')->name('mod-venta.processCsvImport');
    Route::resource('mod-venta', 'ModVentaController');

    // Clausula Venta
    Route::delete('clausula-venta/destroy', 'ClausulaVentaController@massDestroy')->name('clausula-venta.massDestroy');
    Route::post('clausula-venta/parse-csv-import', 'ClausulaVentaController@parseCsvImport')->name('clausula-venta.parseCsvImport');
    Route::post('clausula-venta/process-csv-import', 'ClausulaVentaController@processCsvImport')->name('clausula-venta.processCsvImport');
    Route::resource('clausula-venta', 'ClausulaVentaController');

    // Moneda
    Route::delete('monedas/destroy', 'MonedaController@massDestroy')->name('monedas.massDestroy');
    Route::post('monedas/parse-csv-import', 'MonedaController@parseCsvImport')->name('monedas.parseCsvImport');
    Route::post('monedas/process-csv-import', 'MonedaController@processCsvImport')->name('monedas.processCsvImport');
    Route::resource('monedas', 'MonedaController');

    // Grupo
    Route::delete('grupos/destroy', 'GrupoController@massDestroy')->name('grupos.massDestroy');
    Route::post('grupos/parse-csv-import', 'GrupoController@parseCsvImport')->name('grupos.parseCsvImport');
    Route::post('grupos/process-csv-import', 'GrupoController@processCsvImport')->name('grupos.processCsvImport');
    Route::resource('grupos', 'GrupoController');

    // Productor
    Route::delete('productors/destroy', 'ProductorController@massDestroy')->name('productors.massDestroy');
    Route::post('productors/parse-csv-import', 'ProductorController@parseCsvImport')->name('productors.parseCsvImport');
    Route::post('productors/process-csv-import', 'ProductorController@processCsvImport')->name('productors.processCsvImport');
    Route::resource('productors', 'ProductorController');

    // Conjunto
    Route::delete('conjuntos/destroy', 'ConjuntoController@massDestroy')->name('conjuntos.massDestroy');
    Route::post('conjuntos/parse-csv-import', 'ConjuntoController@parseCsvImport')->name('conjuntos.parseCsvImport');
    Route::post('conjuntos/process-csv-import', 'ConjuntoController@processCsvImport')->name('conjuntos.processCsvImport');
    Route::resource('conjuntos', 'ConjuntoController');

    // Valor Flete
    Route::delete('valor-fletes/destroy', 'ValorFleteController@massDestroy')->name('valor-fletes.massDestroy');
    Route::post('valor-fletes/parse-csv-import', 'ValorFleteController@parseCsvImport')->name('valor-fletes.parseCsvImport');
    Route::post('valor-fletes/process-csv-import', 'ValorFleteController@processCsvImport')->name('valor-fletes.processCsvImport');
    Route::resource('valor-fletes', 'ValorFleteController');

    // Valor Dolar
    Route::delete('valor-dolars/destroy', 'ValorDolarController@massDestroy')->name('valor-dolars.massDestroy');
    Route::post('valor-dolars/parse-csv-import', 'ValorDolarController@parseCsvImport')->name('valor-dolars.parseCsvImport');
    Route::post('valor-dolars/process-csv-import', 'ValorDolarController@processCsvImport')->name('valor-dolars.processCsvImport');
    Route::resource('valor-dolars', 'ValorDolarController');

    // Valor Envase
    Route::delete('valor-envases/destroy', 'ValorEnvaseController@massDestroy')->name('valor-envases.massDestroy');
    Route::post('valor-envases/parse-csv-import', 'ValorEnvaseController@parseCsvImport')->name('valor-envases.parseCsvImport');
    Route::post('valor-envases/process-csv-import', 'ValorEnvaseController@processCsvImport')->name('valor-envases.processCsvImport');
    Route::resource('valor-envases', 'ValorEnvaseController');

    // Anticipo
    Route::delete('anticipos/destroy', 'AnticipoController@massDestroy')->name('anticipos.massDestroy');
    Route::post('anticipos/parse-csv-import', 'AnticipoController@parseCsvImport')->name('anticipos.parseCsvImport');
    Route::post('anticipos/process-csv-import', 'AnticipoController@processCsvImport')->name('anticipos.processCsvImport');
    Route::resource('anticipos', 'AnticipoController');

    // Interes Anticipo
    Route::delete('interes-anticipos/destroy', 'InteresAnticipoController@massDestroy')->name('interes-anticipos.massDestroy');
    Route::post('interes-anticipos/parse-csv-import', 'InteresAnticipoController@parseCsvImport')->name('interes-anticipos.parseCsvImport');
    Route::post('interes-anticipos/process-csv-import', 'InteresAnticipoController@processCsvImport')->name('interes-anticipos.processCsvImport');
    Route::resource('interes-anticipos', 'InteresAnticipoController');

    // Recepcion
    Route::delete('recepcions/destroy', 'RecepcionController@massDestroy')->name('recepcions.massDestroy');
    Route::post('recepcions/parse-csv-import', 'RecepcionController@parseCsvImport')->name('recepcions.parseCsvImport');
    Route::post('recepcions/process-csv-import', 'RecepcionController@processCsvImport')->name('recepcions.processCsvImport');
    Route::resource('recepcions', 'RecepcionController');

    // Proceso
    Route::delete('procesos/destroy', 'ProcesoController@massDestroy')->name('procesos.massDestroy');
    Route::post('procesos/parse-csv-import', 'ProcesoController@parseCsvImport')->name('procesos.parseCsvImport');
    Route::post('procesos/process-csv-import', 'ProcesoController@processCsvImport')->name('procesos.processCsvImport');
    Route::resource('procesos', 'ProcesoController');

     // Analisis
    Route::delete('analisis/destroy', 'AnalisisController@massDestroy')->name('analisis.massDestroy');
    Route::post('analisis/parse-csv-import', 'AnalisisController@parseCsvImport')->name('analisis.parseCsvImport');
    Route::post('analisis/process-csv-import', 'AnalisisController@processCsvImport')->name('analisis.processCsvImport');
    Route::resource('analisis', 'AnalisisController');


    //tarjador
    Route::post('tarjado/getMaterialesUtilizados','TarjadoController@getMaterialesUtilizados')->name('tarjado.getMaterialesUtilizados');
    Route::post('tarjado/getMateriales','TarjadoController@getMateriales')->name('tarjado.getMateriales');
    Route::post('tarjado/getTarjado','TarjadoController@getTarjado')->name('tarjado.getTarjado');
    
    Route::resource('tarjado', 'TarjadoController');

    //Generador de Liquidaciones


Route::post('constructorliquidacion/getProcesos', 'ConstructorLiquidacionController@getProcesos')->name('constructorliquidacion.getProcesos');
Route::post('constructorliquidacion/generatepdf', 'ConstructorLiquidacionController@generatepdf')->name('constructorliquidacion.generatepdf');
    //Route::post('constructorliquidacion/getProcesos', 'ConstructorLiquidacionController@getProcesos')->name('constructorliquidacion.getProcesos');
    Route::get('constructorliquidacion/selector', 'ConstructorLiquidacionController@selector')->name('constructorliquidacion.selector');

    //Route::resource('constructorliquidacion', 'ConstructorLiquidacionController');

     // Material
    Route::delete('materials/destroy', 'MaterialController@massDestroy')->name('materials.massDestroy');
    Route::post('materials/parse-csv-import', 'MaterialController@parseCsvImport')->name('materials.parseCsvImport');
    Route::post('materials/process-csv-import', 'MaterialController@processCsvImport')->name('materials.processCsvImport');
    Route::resource('materials', 'MaterialController');

    // Material Producto
    Route::delete('material-productos/destroy', 'MaterialProductoController@massDestroy')->name('material-productos.massDestroy');
    Route::post('material-productos/parse-csv-import', 'MaterialProductoController@parseCsvImport')->name('material-productos.parseCsvImport');
    Route::post('material-productos/process-csv-import', 'MaterialProductoController@processCsvImport')->name('material-productos.processCsvImport');
    Route::resource('material-productos', 'MaterialProductoController');

       // Multiresiduo
    Route::delete('multiresiduos/destroy', 'MultiresiduoController@massDestroy')->name('multiresiduos.massDestroy');
    Route::post('multiresiduos/parse-csv-import', 'MultiresiduoController@parseCsvImport')->name('multiresiduos.parseCsvImport');
    Route::post('multiresiduos/process-csv-import', 'MultiresiduoController@processCsvImport')->name('multiresiduos.processCsvImport');
    Route::resource('multiresiduos', 'MultiresiduoController');

    // Bonificacion
    Route::delete('bonificacions/destroy', 'BonificacionController@massDestroy')->name('bonificacions.massDestroy');
    Route::post('bonificacions/parse-csv-import', 'BonificacionController@parseCsvImport')->name('bonificacions.parseCsvImport');
    Route::post('bonificacions/process-csv-import', 'BonificacionController@processCsvImport')->name('bonificacions.processCsvImport');
    Route::resource('bonificacions', 'BonificacionController');

    // Otro Cobro
    Route::delete('otro-cobros/destroy', 'OtroCobroController@massDestroy')->name('otro-cobros.massDestroy');
    Route::post('otro-cobros/parse-csv-import', 'OtroCobroController@parseCsvImport')->name('otro-cobros.parseCsvImport');
    Route::post('otro-cobros/process-csv-import', 'OtroCobroController@processCsvImport')->name('otro-cobros.processCsvImport');
    Route::resource('otro-cobros', 'OtroCobroController');


    // Otroscargo
    Route::delete('otroscargos/destroy', 'OtroscargoController@massDestroy')->name('otroscargos.massDestroy');
    Route::post('otroscargos/parse-csv-import', 'OtroscargoController@parseCsvImport')->name('otroscargos.parseCsvImport');
    Route::post('otroscargos/process-csv-import', 'OtroscargoController@processCsvImport')->name('otroscargos.processCsvImport');
    Route::resource('otroscargos', 'OtroscargoController');


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

      // Multiresiduo
    // Route::delete('multiresiduos/destroy', 'MultiresiduoController@massDestroy')->name('multiresiduos.massDestroy');
    // Route::post('multiresiduos/parse-csv-import', 'MultiresiduoController@parseCsvImport')->name('multiresiduos.parseCsvImport');
    // Route::post('multiresiduos/process-csv-import', 'MultiresiduoController@processCsvImport')->name('multiresiduos.processCsvImport');
    // Route::resource('multiresiduos', 'MultiresiduoController');

    // // Bonificacion
    // Route::delete('bonificacions/destroy', 'BonificacionController@massDestroy')->name('bonificacions.massDestroy');
    // Route::post('bonificacions/parse-csv-import', 'BonificacionController@parseCsvImport')->name('bonificacions.parseCsvImport');
    // Route::post('bonificacions/process-csv-import', 'BonificacionController@processCsvImport')->name('bonificacions.processCsvImport');
    // Route::resource('bonificacions', 'BonificacionController');

    // // Otro Cobro
    // Route::delete('otro-cobros/destroy', 'OtroCobroController@massDestroy')->name('otro-cobros.massDestroy');
    // Route::post('otro-cobros/parse-csv-import', 'OtroCobroController@parseCsvImport')->name('otro-cobros.parseCsvImport');
    // Route::post('otro-cobros/process-csv-import', 'OtroCobroController@processCsvImport')->name('otro-cobros.processCsvImport');
    // Route::resource('otro-cobros', 'OtroCobroController');

    // Buscar Guias Aclaradas
    // Route::delete('buscar-guias-aclaradas/destroy', 'BuscarGuiasAclaradasController@massDestroy')->name('buscar-guias-aclaradas.massDestroy');
    // Route::resource('buscar-guias-aclaradas', 'BuscarGuiasAclaradasController');

    // // Consulta Arancelaria
    // Route::delete('consulta-arancelaria/destroy', 'ConsultaArancelariaController@massDestroy')->name('consulta-arancelaria.massDestroy');
    // Route::resource('consulta-arancelaria', 'ConsultaArancelariaController');

    // // Impresion Dips
    // Route::delete('impresion-dips/destroy', 'ImpresionDipsController@massDestroy')->name('impresion-dips.massDestroy');
    // Route::resource('impresion-dips', 'ImpresionDipsController');

    // // Multi Impresion Dips
    // Route::delete('multi-impresion-dips/destroy', 'MultiImpresionDipsController@massDestroy')->name('multi-impresion-dips.massDestroy');
    // Route::resource('multi-impresion-dips', 'MultiImpresionDipsController');

    // // Dips Aprobadas
    // Route::delete('dips-aprobadas/destroy', 'DipsAprobadasController@massDestroy')->name('dips-aprobadas.massDestroy');
    // Route::resource('dips-aprobadas', 'DipsAprobadasController');

    // // Buscar Batch
    // Route::delete('buscar-batches/destroy', 'BuscarBatchController@massDestroy')->name('buscar-batches.massDestroy');
    // Route::resource('buscar-batches', 'BuscarBatchController');

    // // Guias En Batch
    // Route::delete('guias-en-batches/destroy', 'GuiasEnBatchController@massDestroy')->name('guias-en-batches.massDestroy');
    // Route::resource('guias-en-batches', 'GuiasEnBatchController');

    // // Crear Batch
    // Route::delete('crear-batches/destroy', 'CrearBatchController@massDestroy')->name('crear-batches.massDestroy');
    // Route::resource('crear-batches', 'CrearBatchController');

    // // Hawb Batch
    // Route::delete('hawb-batches/destroy', 'HawbBatchController@massDestroy')->name('hawb-batches.massDestroy');
    // Route::resource('hawb-batches', 'HawbBatchController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // // Faq Category
    // Route::delete('faq-categories/destroy', 'FaqCategoryController@massDestroy')->name('faq-categories.massDestroy');
    // Route::resource('faq-categories', 'FaqCategoryController');

    // // Faq Question
    // Route::delete('faq-questions/destroy', 'FaqQuestionController@massDestroy')->name('faq-questions.massDestroy');
    // Route::resource('faq-questions', 'FaqQuestionController');

    // // Courses
    // Route::delete('courses/destroy', 'CoursesController@massDestroy')->name('courses.massDestroy');
    // Route::post('courses/media', 'CoursesController@storeMedia')->name('courses.storeMedia');
    // Route::post('courses/ckmedia', 'CoursesController@storeCKEditorImages')->name('courses.storeCKEditorImages');
    // Route::resource('courses', 'CoursesController');

    // // Lessons
    // Route::delete('lessons/destroy', 'LessonsController@massDestroy')->name('lessons.massDestroy');
    // Route::post('lessons/media', 'LessonsController@storeMedia')->name('lessons.storeMedia');
    // Route::post('lessons/ckmedia', 'LessonsController@storeCKEditorImages')->name('lessons.storeCKEditorImages');
    // Route::resource('lessons', 'LessonsController');

    // // Tests
    // Route::delete('tests/destroy', 'TestsController@massDestroy')->name('tests.massDestroy');
    // Route::resource('tests', 'TestsController');

    // // Questions
    // Route::delete('questions/destroy', 'QuestionsController@massDestroy')->name('questions.massDestroy');
    // Route::post('questions/media', 'QuestionsController@storeMedia')->name('questions.storeMedia');
    // Route::post('questions/ckmedia', 'QuestionsController@storeCKEditorImages')->name('questions.storeCKEditorImages');
    // Route::resource('questions', 'QuestionsController');

    // // Question Options
    // Route::delete('question-options/destroy', 'QuestionOptionsController@massDestroy')->name('question-options.massDestroy');
    // Route::resource('question-options', 'QuestionOptionsController');

    // // Test Results
    // Route::delete('test-results/destroy', 'TestResultsController@massDestroy')->name('test-results.massDestroy');
    // Route::resource('test-results', 'TestResultsController');

    // // Test Answers
    // Route::delete('test-answers/destroy', 'TestAnswersController@massDestroy')->name('test-answers.massDestroy');
    // Route::resource('test-answers', 'TestAnswersController');

    // // Task Status
    // Route::delete('task-statuses/destroy', 'TaskStatusController@massDestroy')->name('task-statuses.massDestroy');
    // Route::resource('task-statuses', 'TaskStatusController');

    // // Task Tag
    // Route::delete('task-tags/destroy', 'TaskTagController@massDestroy')->name('task-tags.massDestroy');
    // Route::resource('task-tags', 'TaskTagController');

    // // Task
    // Route::delete('tasks/destroy', 'TaskController@massDestroy')->name('tasks.massDestroy');
    // Route::post('tasks/media', 'TaskController@storeMedia')->name('tasks.storeMedia');
    // Route::post('tasks/ckmedia', 'TaskController@storeCKEditorImages')->name('tasks.storeCKEditorImages');
    // Route::resource('tasks', 'TaskController');

    // // Tasks Calendar
    // Route::resource('tasks-calendars', 'TasksCalendarController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // // Inhumados
    // Route::delete('inhumados/destroy', 'InhumadosController@massDestroy')->name('inhumados.massDestroy');
    // Route::resource('inhumados', 'InhumadosController');

    // // Cliente
    // Route::delete('clientes/destroy', 'ClienteController@massDestroy')->name('clientes.massDestroy');
    // Route::resource('clientes', 'ClienteController');

    // // Dussi
    // Route::delete('dussis/destroy', 'DussiController@massDestroy')->name('dussis.massDestroy');
    // Route::resource('dussis', 'DussiController');

    // // Batch
    // Route::delete('batches/destroy', 'BatchController@massDestroy')->name('batches.massDestroy');
    // Route::resource('batches', 'BatchController');

    // // Aclaracionguia
    // Route::delete('aclaracionguia/destroy', 'AclaracionguiaController@massDestroy')->name('aclaracionguia.massDestroy');
    // Route::resource('aclaracionguia', 'AclaracionguiaController');

    // // Ingresos
    // Route::delete('ingresos/destroy', 'IngresosController@massDestroy')->name('ingresos.massDestroy');
    // Route::resource('ingresos', 'IngresosController');

    // // Guias Dussi
    // Route::delete('guias-dussis/destroy', 'GuiasDussiController@massDestroy')->name('guias-dussis.massDestroy');
    // Route::resource('guias-dussis', 'GuiasDussiController');

    // // Ingreso Bulto
    // Route::delete('ingreso-bultos/destroy', 'IngresoBultoController@massDestroy')->name('ingreso-bultos.massDestroy');
    // Route::resource('ingreso-bultos', 'IngresoBultoController');

    // // Estados Saep
    // Route::delete('estados-saeps/destroy', 'EstadosSaepController@massDestroy')->name('estados-saeps.massDestroy');
    // Route::resource('estados-saeps', 'EstadosSaepController');

    // // Motivo Saep
    // Route::delete('motivo-saeps/destroy', 'MotivoSaepController@massDestroy')->name('motivo-saeps.massDestroy');
    // Route::resource('motivo-saeps', 'MotivoSaepController');

    // // Bodega
    // Route::delete('bodegas/destroy', 'BodegaController@massDestroy')->name('bodegas.massDestroy');
    // Route::resource('bodegas', 'BodegaController');

    // // Ingreso Cadena Custodia
    // Route::delete('ingreso-cadena-custodia/destroy', 'IngresoCadenaCustodiaController@massDestroy')->name('ingreso-cadena-custodia.massDestroy');
    // Route::resource('ingreso-cadena-custodia', 'IngresoCadenaCustodiaController');

    // // Estado Abandono
    // Route::delete('estado-abandonos/destroy', 'EstadoAbandonoController@massDestroy')->name('estado-abandonos.massDestroy');
    // Route::resource('estado-abandonos', 'EstadoAbandonoController');

    // // Correlativo
    // Route::delete('correlativos/destroy', 'CorrelativoController@massDestroy')->name('correlativos.massDestroy');
    // Route::resource('correlativos', 'CorrelativoController');

    // // Datos Caja
    // Route::delete('datos-cajas/destroy', 'DatosCajaController@massDestroy')->name('datos-cajas.massDestroy');
    // Route::resource('datos-cajas', 'DatosCajaController');

    // // Entidad
    // Route::delete('entidads/destroy', 'EntidadController@massDestroy')->name('entidads.massDestroy');
    // Route::resource('entidads', 'EntidadController');

    // // Area
    // Route::delete('areas/destroy', 'AreaController@massDestroy')->name('areas.massDestroy');
    // Route::resource('areas', 'AreaController');

    // // Locacion
    // Route::delete('locacions/destroy', 'LocacionController@massDestroy')->name('locacions.massDestroy');
    // Route::resource('locacions', 'LocacionController');

    // // Turno
    // Route::delete('turnos/destroy', 'TurnoController@massDestroy')->name('turnos.massDestroy');
    // Route::resource('turnos', 'TurnoController');

    // // Frecuencia Turno
    // Route::delete('frecuencia-turnos/destroy', 'FrecuenciaTurnoController@massDestroy')->name('frecuencia-turnos.massDestroy');
    // Route::resource('frecuencia-turnos', 'FrecuenciaTurnoController');

    // // Cargo
    // Route::delete('cargos/destroy', 'CargoController@massDestroy')->name('cargos.massDestroy');
    // Route::resource('cargos', 'CargoController');

    // // Personal
    // Route::delete('personals/destroy', 'PersonalController@massDestroy')->name('personals.massDestroy');
    // Route::resource('personals', 'PersonalController');


    // // Turnos Frecuencia
    // Route::delete('turnos-frecuencia/destroy', 'TurnosFrecuenciaController@massDestroy')->name('turnos-frecuencia.massDestroy');
    // Route::resource('turnos-frecuencia', 'TurnosFrecuenciaController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');


    // Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
    // Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    // Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    // Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    // Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    // Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    // Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    // Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    // Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    // Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');
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
