<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\BeforeSheet;

class NormaExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize, WithCustomStartCell
{
    protected $data;
    protected $productorNombre;

    public function __construct(Collection $data, $productorNombre)
    {
        $this->data = $data;
        $this->productorNombre = $productorNombre;
    }

    public function collection()
    {
        $formattedData = new Collection();

        // Objeto para agrupar por especie, variedad, etiqueta y calibre
        $datosAgrupados = [];

        foreach ($this->data as $item) {
            if (strtoupper($item->categoria) === 'CAT 1' || strtoupper($item->categoria) === 'CAT 2') {
                // Conversión de nombre de especie
                $especie = $item->especie->nombre;
                switch(strtoupper($especie)) {
                    case 'PLUMS': $especie = 'Ciruela'; break;
                    case 'NECTARINES': $especie = 'Nectarin'; break;
                    case 'PEACHES': $especie = 'Durazno'; break;
                }

                $variedad = $item->variedad;
                $etiqueta = $item->etiqueta;
                $calibre = $item->calibre;
                $color = $item->color ?: '';
                $totalKilos = (float)str_replace(',', '.', $item->total_kilos) ?: 0;
                $rnpTotal = (float)str_replace(',', '.', $item->resultado_total) ?: 0;
                $rnpKilo = $rnpTotal / $totalKilos; //(float)str_replace(',', '.', $item->resultado_total) ?: 0;

                // Nueva agrupación: especie -> variedad -> etiqueta -> calibre
                if (!isset($datosAgrupados[$especie])) $datosAgrupados[$especie] = [];
                if (!isset($datosAgrupados[$especie][$variedad])) $datosAgrupados[$especie][$variedad] = [];
                if (!isset($datosAgrupados[$especie][$variedad][$etiqueta])) {
                    $datosAgrupados[$especie][$variedad][$etiqueta] = [
                        'calibres' => [],
                        'total_kilos' => 0, // Total kilos for this etiqueta
                        'rnp_total' => 0,   // Total RNP for this etiqueta
                        'rnp_kilo_sum' => 0, // Sum of (rnp_kilo * total_kilos) for this etiqueta
                        'rnp_kilo_kilos' => 0 // Sum of total_kilos for this etiqueta
                    ];
                }
                if (!isset($datosAgrupados[$especie][$variedad][$etiqueta]['calibres'][$calibre])) {
                    $datosAgrupados[$especie][$variedad][$etiqueta]['calibres'][$calibre] = [
                        'color' => $color,
                        'total_kilos' => 0,
                        'rnp_total' => 0,
                        'rnp_kilo' => 0
                    ];
                }

                $datosAgrupados[$especie][$variedad][$etiqueta]['calibres'][$calibre]['total_kilos'] += $totalKilos;
                $datosAgrupados[$especie][$variedad][$etiqueta]['calibres'][$calibre]['rnp_total'] += $rnpTotal;
                $datosAgrupados[$especie][$variedad][$etiqueta]['calibres'][$calibre]['rnp_kilo'] += $rnpKilo;

                // Acumular totales para la etiqueta actual
                $datosAgrupados[$especie][$variedad][$etiqueta]['total_kilos'] += $totalKilos;
                $datosAgrupados[$especie][$variedad][$etiqueta]['rnp_total'] += $rnpTotal;
                $datosAgrupados[$especie][$variedad][$etiqueta]['rnp_kilo_sum'] += $rnpKilo;
                $datosAgrupados[$especie][$variedad][$etiqueta]['rnp_kilo_kilos'] += $totalKilos;
            }
        }

        $ordenCalibres = ['7J', '6J', '5J', '4J', '3J', '2J', 'J', 'XL', 'L'];

        $totalGeneral = [
            'cajas_equivalentes' => 0,
            'total_kilos' => 0,
            'rnp_total' => 0,
            'rnp_kilo_sum' => 0,
            'rnp_kilo_kilos' => 0
        ];

        $especies = array_keys($datosAgrupados);
        sort($especies);

        foreach ($especies as $especie) {
            $totalEspecie = [
                'cajas_equivalentes' => 0,
                'total_kilos' => 0,
                'rnp_total' => 0,
                'rnp_kilo_sum' => 0,
                'rnp_kilo_kilos' => 0
            ];

            $variedades = array_keys($datosAgrupados[$especie]);
            sort($variedades);

            foreach ($variedades as $variedad) {
                $totalVariedad = [
                    'cajas_equivalentes' => 0,
                    'total_kilos' => 0,
                    'rnp_total' => 0,
                    'rnp_kilo_sum' => 0,
                    'rnp_kilo_kilos' => 0
                ];

                $etiquetas = array_keys($datosAgrupados[$especie][$variedad]);
                sort($etiquetas);

                foreach ($etiquetas as $etiqueta) {
                    $totalEtiqueta = [
                        'cajas_equivalentes' => 0,
                        'total_kilos' => 0,
                        'rnp_total' => 0,
                        'rnp_kilo_sum' => 0,
                        'rnp_kilo_kilos' => 0
                    ];
                    $datosEtiqueta = $datosAgrupados[$especie][$variedad][$etiqueta];
                    $calibres = array_keys($datosEtiqueta['calibres']);
                    sort($calibres);

                    foreach ($calibres as $calibre) {
                        $datosCalibre = $datosEtiqueta['calibres'][$calibre];
                        $curvaCalibre = $datosEtiqueta['total_kilos'] ? ($datosCalibre['total_kilos'] / $datosEtiqueta['total_kilos']) : 0;
                        $cajasEquivalentes = round($datosCalibre['total_kilos'] / 5);

                        $formattedData->push([
                            'Especie' => $especie,
                            'Variedad' => $variedad,
                            'Etiqueta' => $etiqueta,
                            'Calibre' => $calibre,
                            'Color' => $datosCalibre['color'],
                            'Curva Calibre' => number_format($curvaCalibre * 100, 2) . ' %',
                            'Cajas' => $cajasEquivalentes,
                            'Kilos Totales' => number_format($datosCalibre['total_kilos'], 2, ',', '.'),
                            'RNP Total' => number_format($datosCalibre['rnp_total'], 2, ',', '.'),
                            'RNP Kilo' => number_format($datosCalibre['rnp_kilo'], 2, ',', '.'),
                        ]);

                        $totalEtiqueta['cajas_equivalentes'] += $cajasEquivalentes;
                        $totalEtiqueta['total_kilos'] += $datosCalibre['total_kilos'];
                        $totalEtiqueta['rnp_total'] += $datosCalibre['rnp_total'];
                        $totalEtiqueta['rnp_kilo_sum'] += $datosCalibre['rnp_total'] / $datosCalibre['total_kilos'];
                        $totalEtiqueta['rnp_kilo_kilos'] += $datosCalibre['total_kilos'];
                    }

                    // Total por etiqueta
                    $rnpKiloEtiqueta = $totalEtiqueta['rnp_kilo_kilos'] ? ($totalEtiqueta['rnp_total'] / $totalEtiqueta['rnp_kilo_kilos']) : 0;
                    $formattedData->push([
                        'Especie'=>'',
                        'Variedad' => '',
                        'Etiqueta' => 'Total ' . $etiqueta,
                        'Calibre' => '',
                        'Color' => '',
                        'Curva Calibre' => '100.00 %',
                        'Cajas' => $totalEtiqueta['cajas_equivalentes'],
                        'Kilos Totales' => number_format($totalEtiqueta['total_kilos'], 2, ',', '.'),
                        'RNP Total' => number_format($totalEtiqueta['rnp_total'], 2, ',', '.'),
                        'RNP Kilo' => number_format($rnpKiloEtiqueta, 4, ',', '.'),
                    ]);

                    $totalVariedad['cajas_equivalentes'] += $totalEtiqueta['cajas_equivalentes'];
                    $totalVariedad['total_kilos'] += $totalEtiqueta['total_kilos'];
                    $totalVariedad['rnp_total'] += $totalEtiqueta['rnp_total'];
                    $totalVariedad['rnp_kilo_sum'] += $totalEtiqueta['rnp_total'] / $totalEtiqueta['total_kilos'];
                    $totalVariedad['rnp_kilo_kilos'] += $totalEtiqueta['rnp_kilo_kilos'];





                    }

                }


            // Total por especie
            $rnpKiloEspecie = $totalEspecie['rnp_kilo_kilos'] ? ($totalEspecie['rnp_kilo_sum'] / $totalEspecie['rnp_kilo_kilos']) : 0;
            $formattedData->push([
                'Especie' => 'Total ' . $especie,
                'Variedad' => '',
                'Etiqueta' => '',
                'Calibre' => '',
                'Color' => '',
                'Curva Calibre' => '',
                'Cajas' => $totalEspecie['cajas_equivalentes'],
                'Kilos Totales' => number_format($totalEspecie['total_kilos'], 2, ',', '.'),
                'RNP Total' => number_format($totalEspecie['rnp_total'], 2, ',', '.'),
                'RNP Kilo' => number_format($rnpKiloEspecie, 4, ',', '.'),
            ]);

            $totalGeneral['cajas_equivalentes'] += $totalEspecie['cajas_equivalentes'];
            $totalGeneral['total_kilos'] += $totalEspecie['total_kilos'];
            $totalGeneral['rnp_total'] += $totalEspecie['rnp_total'];
            $totalGeneral['rnp_kilo_sum'] += $totalEspecie['rnp_kilo_sum'];
            $totalGeneral['rnp_kilo_kilos'] += $totalEspecie['rnp_kilo_kilos'];
        }


        // Total general de la norma
        $rnpKiloGeneral = $totalGeneral['rnp_kilo_kilos'] ? ($totalGeneral['rnp_kilo_sum'] / $totalGeneral['rnp_kilo_kilos']) : 0;
        $formattedData->push([
            'Especie' => 'Total General',
            'Variedad' => '',
            'Etiqueta' => '',
            'Calibre' => '',
            'Color' => '',
            'Curva Calibre' => '',
            'Cajas' => $totalGeneral['cajas_equivalentes'],
            'Kilos Totales' => number_format($totalGeneral['total_kilos'], 2, ',', '.'),
            'RNP Total' => number_format($totalGeneral['rnp_total'], 2, ',', '.'),
            'RNP Kilo' => number_format($rnpKiloGeneral, 4, ',', '.'),
        ]);

        return $formattedData;

    }

    public function headings(): array
    {
        return [
            'Especie',
            'Variedad',
            'Etiqueta',
            'Calibre',
            'Color',
            'Curva Calibre',
            'Cajas',
            'Kilos Totales',
            'RNP Total',
            'RNP Kilo',
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $event->sheet->mergeCells('A1:I1');
                $event->sheet->setCellValue('A1', 'EXPORTACIÓN DENTRO DE NORMA');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->mergeCells('A2:I2');
                $event->sheet->setCellValue('A2', 'Productor: ' . $this->productorNombre);
                $event->sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
