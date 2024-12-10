<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsistenciaExport implements FromArray, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Devuelve los datos del Excel.
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * Devuelve los encabezados del Excel.
     */
    public function headings(): array
    {
        return [
            'RUT', 'Nombre', 'Departamento', 'Fecha y Hora', 'Tipo', 'Color'
        ];
    }

    /**
     * Aplica estilos al archivo Excel.
     */
    public function styles(Worksheet $sheet)
    {
        foreach ($this->data as $index => $row) {
            $rowIndex = $index + 2; // +2 porque la fila 1 es el encabezado

            // Aplicar color según el valor del último campo (color)
            switch ($row[5]) {
                case 'verde':
                    $sheet->getStyle("A$rowIndex:F$rowIndex")->applyFromArray([
                        'font' => [
                            'color' => ['argb' => 'FFFFFFFF'], // Blanco
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '91975a'], // Verde
                        ],
                    ]);
                    break;
                case 'rojo':
                    $sheet->getStyle("A$rowIndex:F$rowIndex")->applyFromArray([
                        'font' => [
                            'color' => ['argb' => 'FFFFFFFF'], // Blanco
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFFF0000'], // Rojo
                        ],
                    ]);
                    break;
                case 'amarillo':
                    $sheet->getStyle("A$rowIndex:F$rowIndex")->applyFromArray([
                        'font' => [
                            'color' => ['argb' => 'FFFFFFFF'], // Blanco
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFFFFF00'], // Amarillo
                        ],
                    ]);
                    break;
            }
        }
    }
}
?>
