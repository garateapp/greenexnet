<?php

return [
    'control_access' => [
        'to' => env('CONTROL_ACCESS_REPORT_TO'),
        'cc' => env('CONTROL_ACCESS_REPORT_CC'),
        'bcc' => env('CONTROL_ACCESS_REPORT_BCC'),
        'subject' => env('CONTROL_ACCESS_REPORT_SUBJECT', 'Reporte Control de Acceso'),
    ],
];
