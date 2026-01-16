<?php

return [
    'date_format'         => 'd/m/Y',
    'time_format'         => 'H:i:s',
    'primary_language'    => 'es',
    'available_languages' => [
        'es'    => 'Spanish',
        'en'    => 'English',
        'pt-br' => 'Brazilian Portuguese',
    ],
    'registration_default_role' => '2',
    'adquisiciones_notificacion_email' => env('ADQUISICIONES_NOTIFICACION_EMAIL', ''),
    'adquisiciones_puede_subir_cotizaciones' => env('ADQUISICIONES_PUEDE_SUBIR_COTIZACIONES', false),

];
