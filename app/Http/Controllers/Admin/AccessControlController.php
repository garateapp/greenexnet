<?php
// app/Http/Controllers/AccessControlController.php
namespace App\Http\Controllers\Admin;

use App\Services\GoogleSheetsService;

class AccessControlController extends Controller
{
    protected $sheetsService;

    public function __construct(GoogleSheetsService $sheetsService)
    {
        $this->sheetsService = $sheetsService;
    }

    public function getAccessReport()
    {
        $spreadsheetId = 'TU_SPREADSHEET_ID'; // ID de tu Google Sheet
        
        // Definir los rangos de las hojas
        $entryRange = 'Control_Entrada!A:M'; // Ajusta según tus columnas (A hasta M por los 13 campos)
        $exitRange = 'Control_Salida!A:J';   // Ajusta según tus columnas (A hasta J por los 10 campos)

        // Obtener datos
        $entryData = $this->sheetsService->getSheetData($spreadsheetId, $entryRange);
        $exitData = $this->sheetsService->getSheetData($spreadsheetId, $exitRange);

        // Estructurar los datos
        $entryRecords = $this->formatEntryData($entryData);
        $exitRecords = $this->formatExitData($exitData);

        return view('reporteria.controldeacceso', [
            'entryRecords' => $entryRecords,
            'exitRecords' => $exitRecords
        ]);
    }

    private function formatEntryData($data)
    {
        $headers = [
            'fecha_hora_entrada',
            'nombre',
            'rut',
            'telefono',
            'empresa',
            'patente',
            'n_guia_despacho',
            'foto_patente',
            'foto_guia_despacho',
            'foto_sello',
            'fotos_interior',
            'area_destino',
            'motivo_ingreso'
        ];

        return $this->formatRecords($data, $headers);
    }

    private function formatExitData($data)
    {
        $headers = [
            'fecha_hora_salida',
            'nombre',
            'rut',
            'empresa',
            'patente',
            'n_guia_despacho',
            'foto_patente',
            'foto_guia_despacho',
            'foto_sello',
            'fotos_interior'
        ];

        return $this->formatRecords($data, $headers);
    }

    private function formatRecords($data, $headers)
    {
        $records = [];
        $isFirstRow = true;

        foreach ($data as $row) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue; // Saltar la fila de encabezados
            }
            
            $record = [];
            foreach ($headers as $index => $header) {
                $record[$header] = $row[$index] ?? '';
            }
            $records[] = $record;
        }

        return $records;
    }
}

