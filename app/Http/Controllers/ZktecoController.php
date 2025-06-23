<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Para registrar lo que llega
use SimpleXMLElement; // Para parsear XML

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
        Log::info('ZKTeco Request Received', $request->all());

        // El dispositivo envía datos mediante POST.
        // El contenido puede venir directamente como raw body (XML)
        $xmlData = $request->getContent();

        if (empty($xmlData)) {
            Log::warning('ZKTeco: No XML data received.');
            // Puedes retornar un HTTP 200 OK incluso si no hay datos,
            // para que el dispositivo no reintente continuamente si es un keep-alive vacío.
            return response('OK', 200);
        }

        try {
            // Parsear el XML
            $xml = new SimpleXMLElement($xmlData);

            // Determinar el tipo de datos que se reciben (registros, usuarios, etc.)
            // Esto dependerá del XML que ZKTeco envíe.
            // Los tags comunes pueden ser <Cdata>, <Trans>, <User>, <Enroll>, etc.

            if (isset($xml->Cdata)) {
                // Es un registro de transacción (fichaje)
                foreach ($xml->Cdata->Row as $row) {
                    $pin = (string)$row->PIN;       // PIN del usuario
                    $time = (string)$row->DateTime; // Fecha y hora del fichaje
                    $deviceIp = (string)$row->IP;   // IP del dispositivo
                    $verifyMode = (string)$row->Verify; // Modo de verificación (rostro, huella)
                    $status = (string)$row->Status; // Estado de la entrada/salida (IN/OUT)
                    $workCode = (string)$row->WorkCode; // Código de trabajo (si aplica)
                    $deviceSN = (string)$row->sn; // Número de serie del dispositivo

                    Log::info("ZKTeco Trans: PIN={$pin}, Time={$time}, DeviceSN={$deviceSN}");

                    // Aquí, guarda los datos en tu base de datos de Laravel
                    // Por ejemplo:
                    // \App\Models\Attendance::create([
                    //     'user_pin' => $pin,
                    //     'fichaje_at' => $time,
                    //     'device_serial_number' => $deviceSN,
                    //     'verify_mode' => $verifyMode,
                    //     'status' => $status,
                    // ]);
                }
            } elseif (isset($xml->User)) {
                // Es información de usuario (Enrollment)
                foreach ($xml->User->Row as $row) {
                    $pin = (string)$row->PIN;
                    $name = (string)$row->Name;
                    $card = (string)$row->Card;
                    $group = (string)$row->Group;
                    $password = (string)$row->Password;
                    $privilege = (string)$row->Privilege;

                    Log::info("ZKTeco User: PIN={$pin}, Name={$name}");

                    // Aquí actualiza o crea usuarios en tu base de datos
                    // \App\Models\User::updateOrCreate(
                    //     ['pin' => $pin],
                    //     ['name' => $name, 'card_number' => $card, ...]
                    // );
                }
            }
            // Puedes añadir más lógica para otros tipos de datos que envíe el dispositivo

            // ZKTeco espera una respuesta "OK" para confirmar que los datos fueron recibidos.
            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('ZKTeco: Error parsing XML or processing data: ' . $e->getMessage(), ['xml' => $xmlData]);
            // Retorna un error para que el dispositivo pueda reintentar, si lo deseas.
            // O un "OK" si prefieres que no reintente aunque haya fallado en tu lado.
            return response('ERROR: ' . $e->getMessage(), 500);
        }
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
        Log::info('ZKTeco GET Request Received', $request->all());

        $deviceSN = $request->query('SN'); // Número de serie del dispositivo

        // La respuesta que el ZKTeco espera para GET es una serie de comandos.
        // `GET ATTLOG` le dice al dispositivo que envíe todos los registros de asistencia pendientes.
        // `INFO,0` es un comando general para obtener información del dispositivo (a veces requerido antes de otros comandos).
        // `OK` simplemente confirma la conexión sin pedir nada específico.
        // `GET OPTION` también puede ser un comando para ver sus configuraciones.

        // Vamos a probar con GET ATTLOG para forzar el envío de logs de asistencia.
        // NOTA: Si esto funciona, tendrás que implementar una lógica más inteligente
        // para cuándo pedir los logs (ej. cada X minutos, o solo una vez por GET,
        // o después de recibir un marcaje para confirmar que se envió todo).

        $responseContent = "GET ATTLOG\r\n"; // \r\n es importante para el protocolo ZKTeco
        // $responseContent = "INFO,0\r\n"; // Otra opción si GET ATTLOG no funciona solo
        // $responseContent = "OK"; // Tu respuesta actual

        Log::info('ZKTeco GET Response Sent', ['response' => $responseContent]);

        return response($responseContent, 200)
            ->header('Content-Type', 'text/plain'); // Asegurarse que es texto plano
    }
}
