<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ZktecoController extends Controller
{
    /**
     * Recibe los datos PUSH del dispositivo ZKTeco.
     * La ruta de la URL típica es /iclock/cdata
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function receiveData(Request $request)
    {
        $logPathPost = storage_path('logs/zkt_post_log.txt');
        $xmlData = $request->getContent();

        // Guarda el contenido XML crudo
        \Illuminate\Support\Facades\File::append($logPathPost, "--- New Request (POST) ---\n" . $xmlData . "\n\n");

        // ZKTeco espera una respuesta "OK" para confirmar que los datos fueron recibidos.
        return response('OK', 200);
    }

    /**
     * Endpoint para las peticiones GET (Heartbeat/Keep-alive) de ZKTeco.
     * La ruta de la URL típica es /iclock/cdata
     * ZKTeco envía GET para verificar la conexión o solicitar comandos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleIClockGet(Request $request)
    {
        $logPathGet = storage_path('logs/zkt_get_log.txt');
        $fullUrl = $request->fullUrl();

        // Guarda la URL completa de la petición GET
        \Illuminate\Support\Facades\File::append($logPathGet, "--- New Request (GET) ---\n" . $fullUrl . "\n\n");

        // Respuesta estándar al dispositivo
        return response("OK", 200)->header('Content-Type', 'text/plain');
    }
}
