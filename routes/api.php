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

    // Airline
    Route::apiResource('locacions', 'LocacionApiController');

    // Importacion Marcas Manifiesto


    // Tipo
    Route::apiResource('tipos', 'TipoApiController');

    // Tipoequis
    // Datos Caja
    Route::apiResource('datos-cajas', 'DatosCajaApiController');
    Route::apiResource('turnos', 'TurnoApiController');
});

Route::group(['middleware' => ['auth:sanctum']], function () {


    // ... Other routes
});
