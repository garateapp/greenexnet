<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Para registrar lo que llega
use SimpleXMLElement; // Para parsear XML
use Illuminate\Support\Facades\File;


class ZktecoController extends Controller
{
    /**
     * Recibe los datos PUSH del dispositivo ZKTeco.
     * La ruta de la URL típica es /iclock/cdata
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function receiveData(Request $request)
    // {
    //     Log::info('ZKTeco Request Received', $request->all());

    //     // El dispositivo envía datos mediante POST.
    //     // El contenido puede venir directamente como raw body (XML)
    //     $xmlData = $request->getContent();

    //     if (empty($xmlData)) {
    //         Log::warning('ZKTeco: No XML data received.');
    //         // Puedes retornar un HTTP 200 OK incluso si no hay datos,
    //         // para que el dispositivo no reintente continuamente si es un keep-alive vacío.
    //         return response('OK', 200);
    //     }

    //     try {
    //         // Parsear el XML
    //         $xml = new SimpleXMLElement($xmlData);

    //         // Determinar el tipo de datos que se reciben (registros, usuarios, etc.)
    //         // Esto dependerá del XML que ZKTeco envíe.
    //         // Los tags comunes pueden ser <Cdata>, <Trans>, <User>, <Enroll>, etc.

    //         if (isset($xml->Cdata)) {
    //             // Es un registro de transacción (fichaje)
    //             foreach ($xml->Cdata->Row as $row) {
    //                 $pin = (string)$row->PIN;       // PIN del usuario
    //                 $time = (string)$row->DateTime; // Fecha y hora del fichaje
    //                 $deviceIp = (string)$row->IP;   // IP del dispositivo
    //                 $verifyMode = (string)$row->Verify; // Modo de verificación (rostro, huella)
    //                 $status = (string)$row->Status; // Estado de la entrada/salida (IN/OUT)
    //                 $workCode = (string)$row->WorkCode; // Código de trabajo (si aplica)
    //                 $deviceSN = (string)$row->sn; // Número de serie del dispositivo

    //                 Log::info("ZKTeco Trans: PIN={$pin}, Time={$time}, DeviceSN={$deviceSN}");

    //                 // Aquí, guarda los datos en tu base de datos de Laravel
    //                 // Por ejemplo:
    //                 // \App\Models\Attendance::create([
    //                 //     'user_pin' => $pin,
    //                 //     'fichaje_at' => $time,
    //                 //     'device_serial_number' => $deviceSN,
    //                 //     'verify_mode' => $verifyMode,
    //                 //     'status' => $status,
    //                 // ]);
    //             }
    //         } elseif (isset($xml->User)) {
    //             // Es información de usuario (Enrollment)
    //             foreach ($xml->User->Row as $row) {
    //                 $pin = (string)$row->PIN;
    //                 $name = (string)$row->Name;
    //                 $card = (string)$row->Card;
    //                 $group = (string)$row->Group;
    //                 $password = (string)$row->Password;
    //                 $privilege = (string)$row->Privilege;

    //                 Log::info("ZKTeco User: PIN={$pin}, Name={$name}");

    //                 // Aquí actualiza o crea usuarios en tu base de datos
    //                 // \App\Models\User::updateOrCreate(
    //                 //     ['pin' => $pin],
    //                 //     ['name' => $name, 'card_number' => $card, ...]
    //                 // );
    //             }
    //         }
    //         // Puedes añadir más lógica para otros tipos de datos que envíe el dispositivo

    //         // ZKTeco espera una respuesta "OK" para confirmar que los datos fueron recibidos.
    //         return response('OK', 200);

    //     } catch (\Exception $e) {
    //         Log::error('ZKTeco: Error parsing XML or processing data: ' . $e->getMessage(), ['xml' => $xmlData]);
    //         // Retorna un error para que el dispositivo pueda reintentar, si lo deseas.
    //         // O un "OK" si prefieres que no reintente aunque haya fallado en tu lado.
    //         return response('ERROR: ' . $e->getMessage(), 500);
    //     }
    // }

    // /**
    //  * Endpoint para las peticiones GET (Heartbeat/Keep-alive) de ZKTeco.
    //  * La ruta de la URL típica es /iclock/cdata
    //  * ZKTeco envía GET para verificar la conexión o solicitar comandos.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function handleIClockGet(Request $request)
    // {
    //     Log::info('ZKTeco GET Request Received', $request->all());

    //     $deviceSN = $request->query('SN');

    //     // Este comando instruye al dispositivo a enviar sus registros de asistencia.
    //     $responseContent = "GET USER\r\nGET ATTLOG\r\n";

    //     Log::info('ZKTeco GET Response Sent', ['response' => $responseContent]);

    //     return response($responseContent, 200)
    //         ->header('Content-Type', 'text/plain');
    // }
     public function receiveData(Request $request)
    {
        $logPathPost = storage_path('logs/zkt_post_log.txt');
        $xmlData = $request->getContent();

        // Guarda el contenido XML crudo
        File::append($logPathPost, "--- New Request (POST) ---\n" . $xmlData . "\n\n");

        // El resto del código puede permanecer para la lógica futura,
        // pero por ahora nos centramos en capturar los datos.

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
        File::append($logPathGet, "--- New Request (GET) ---\n" . $fullUrl . "\n\n");

        // Respuesta estándar al dispositivo
        return response("GET ATTLOG\r\n", 200)->header('Content-Type', 'text/plain');
    }

}
