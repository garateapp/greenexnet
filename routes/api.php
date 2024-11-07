<?php

Route::post('register', 'Api\\AuthController@register');
Route::post('login', 'Api\\AuthController@login');
Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::post('users/media', 'UsersApiController@storeMedia')->name('users.storeMedia');
    Route::apiResource('users', 'UsersApiController');

    // Estados
    Route::post('estados/media', 'EstadosApiController@storeMedia')->name('estados.storeMedia');
    Route::apiResource('estados', 'EstadosApiController');




    // Datos Caja
    Route::apiResource('datos-cajas', 'DatosCajaApiController');
    Route::apiResource('turnos', 'TurnoApiController');
    // Turno


    // Frecuencia Turno
    Route::apiResource('frecuencia-turnos', 'FrecuenciaTurnoApiController');
    Route::get('/frecuencia-turnos/{id}/obtieneTurno', 'FrecuenciaTurnoApiController@obtieneTurno');

    Route::apiResource('locacions', 'LocacionApiController');
    Route::get('/locacions/{id}/obtieneUbicacion', 'LocacionApiController@obtieneUbicacion');
    Route::get('/locacions/{id}/obtienePuesto', 'LocacionApiController@obtienePuesto');
    Route::apiResource('asistencia', 'AsistenciaApiController');
    Route::get('/asistencia/{id}/obtieneAsistencia', 'AsistenciaApiController@obtieneAsistencia');
    Route::post('asistencia/guardarAsistencia', 'AsistenciaApiController@guardarAsistencia');
    Route::get('asistencia/obtieneAsistenciaActual', 'AsistenciaApiController@obtieneAsistenciaActual');

    // Cargo
    Route::apiResource('cargos', 'CargoApiController');

    // Personal
    Route::apiResource('personals', 'PersonalApiController');

    // Turnos Frecuencia
    Route::apiResource('turnos-frecuencia', 'TurnosFrecuenciaApiController');
});

Route::group(['middleware' => ['auth:sanctum']], function () {


    // ... Other routes
});
