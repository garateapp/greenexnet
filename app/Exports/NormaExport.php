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

        // Objeto para agrupar por variedad, etiqueta, semana y calibre
        $datosAgrupados_v2 = [];

        foreach ($this->data as $item_v2) {
            if (strtoupper($item_v2->categoria) === 'CAT 1' || strtoupper($item_v2->categoria) === 'CAT 2') {
                $variedad_v2 = $item_v2->variedad;
                $etiqueta_v2 = $item_v2->etiqueta;
                $semana_v2 = (string)$item_v2->eta_week;
                $calibre_v2 = $item_v2->calibre;
                $color_v2 = $item_v2->color ?: '';
                $totalKilos_v2 = (float)str_replace(',', '.', $item_v2->total_kilos) ?: 0;
                $rnpTotal_v2 = (float)str_replace(',', '.', $item_v2->resultado_total) ?: 0;
                $rnpKilo_v2 = (float)str_replace(',', '.', $item_v2->resultado_kilo) ?: 0;

                if (!isset($datosAgrupados_v2[$variedad_v2])) $datosAgrupados_v2[$variedad_v2] = [];
                if (!isset($datosAgrupados_v2[$variedad_v2][$etiqueta_v2])) $datosAgrupados_v2[$variedad_v2][$etiqueta_v2] = [];
                if (!isset($datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2])) {
                    $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2] = [
                        'calibres' => [],
                        'total_kilos' => 0,
                        'rnp_total' => 0,
                        'rnp_kilo_sum' => 0,
                        'rnp_kilo_kilos' => 0
                    ];
                }
                if (!isset($datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2]['calibres'][$calibre_v2])) {
                    $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2]['calibres'][$calibre_v2] = [
                        'color' => $color_v2,
                        'total_kilos' => 0,
                        'rnp_total' => 0,
                        'rnp_kilo' => 0
                    ];
                }

                $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2]['calibres'][$calibre_v2]['total_kilos'] += $totalKilos_v2;
                $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2]['calibres'][$calibre_v2]['rnp_total'] += $rnpTotal_v2;
                $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2]['calibres'][$calibre_v2]['rnp_kilo'] += $rnpKilo_v2;
                $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2]['total_kilos'] += $totalKilos_v2;
                $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2]['rnp_total'] += $rnpTotal_v2;
                $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2]['rnp_kilo_sum'] += $rnpKilo_v2 * $totalKilos_v2;
                $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2]['rnp_kilo_kilos'] += $totalKilos_v2;
            }
        }

        $ordenCalibres_v2 = ['7J', '6J', '5J', '4J', '3J', '2J', 'J', 'XL', 'L'];

        $totalGeneral_v2 = [
            'cajas_equivalentes' => 0,
            'total_kilos' => 0,
            'rnp_total' => 0,
            'rnp_kilo_sum' => 0,
            'rnp_kilo_kilos' => 0
        ];

        $variedades_v2 = array_keys($datosAgrupados_v2);
        sort($variedades_v2);

        foreach ($variedades_v2 as $variedad_v2) {
            $totalVariedad_v2 = [
                'cajas_equivalentes' => 0,
                'total_kilos' => 0,
                'rnp_total' => 0,
                'rnp_kilo_sum' => 0,
                'rnp_kilo_kilos' => 0
            ];
            $etiquetas_v2 = array_keys($datosAgrupados_v2[$variedad_v2]);
            sort($etiquetas_v2);

            foreach ($etiquetas_v2 as $etiqueta_v2) {
                $totalEtiqueta_v2 = [
                    'cajas_equivalentes' => 0,
                    'total_kilos' => 0,
                    'rnp_total' => 0,
                    'rnp_kilo_sum' => 0,
                    'rnp_kilo_kilos' => 0
                ];
                $semanas_v2 = array_keys($datosAgrupados_v2[$variedad_v2][$etiqueta_v2]);
                sort($semanas_v2, SORT_NUMERIC);

                foreach ($semanas_v2 as $semana_v2) {
                    $datosSemana_v2 = $datosAgrupados_v2[$variedad_v2][$etiqueta_v2][$semana_v2];
                    $calibres_v2 = array_keys($datosSemana_v2['calibres']);
                    usort($calibres_v2, function($a, $b) use ($ordenCalibres_v2) {
                        return array_search($a, $ordenCalibres_v2) - array_search($b, $ordenCalibres_v2);
                    });

                    foreach ($calibres_v2 as $calibre_v2) {
                        $datosCalibre_v2 = $datosSemana_v2['calibres'][$calibre_v2];
                        $curvaCalibre_v2 = $datosSemana_v2['total_kilos'] ? ($datosCalibre_v2['total_kilos'] / $datosSemana_v2['total_kilos']) : 0;
                        $cajasEquivalentes_v2 = round($datosCalibre_v2['total_kilos'] / 5);

                        $formattedData->push([
                            'Variedad' => $variedad_v2,
                            'Etiqueta' => $etiqueta_v2,
                            'Serie' => $calibre_v2,
                            'Color' => $datosCalibre_v2['color'],
                            'Curva Calibre' => number_format($curvaCalibre_v2 * 100, 2) . ' %',
                            'Cajas' => $cajasEquivalentes_v2,
                            'Kilos Totales' => number_format($datosCalibre_v2['total_kilos'], 2, ',', '.'),
                            'RNP Total' => number_format($datosCalibre_v2['rnp_total'], 2, ',', '.'),
                            'RNP Kilo' => number_format($datosCalibre_v2['rnp_kilo'], 4, ',', '.'),
                        ]);

                        $totalEtiqueta_v2['cajas_equivalentes'] += $cajasEquivalentes_v2;
                        $totalEtiqueta_v2['total_kilos'] += $datosCalibre_v2['total_kilos'];
                        $totalEtiqueta_v2['rnp_total'] += $datosCalibre_v2['rnp_total'];
                        $totalEtiqueta_v2['rnp_kilo_sum'] += $datosCalibre_v2['rnp_kilo'] * $datosCalibre_v2['total_kilos'];
                        $totalEtiqueta_v2['rnp_kilo_kilos'] += $datosCalibre_v2['total_kilos'];
                    }

                }

                // Total por etiqueta
                $rnpKiloEtiqueta_v2 = $totalEtiqueta_v2['rnp_kilo_kilos'] ? ($totalEtiqueta_v2['rnp_kilo_sum'] / $totalEtiqueta_v2['rnp_kilo_kilos']) : 0;
                $totalEtiqueta_v2['cajas_equivalentes'] = round($totalEtiqueta_v2['total_kilos'] / 9);
                $formattedData->push([
                    'Variedad' => '',
                    'Etiqueta' => 'Total ' . $etiqueta_v2,
                    'Serie' => '',
                    'Color' => '',
                    'Curva Calibre' => '100.00 %',
                    'Cajas' => $totalEtiqueta_v2['cajas_equivalentes'],
                    'Kilos Totales' => number_format($totalEtiqueta_v2['total_kilos'], 2, ',', '.'),
                    'RNP Total' => number_format($totalEtiqueta_v2['rnp_total'], 2, ',', '.'),
                    'RNP Kilo' => number_format($rnpKiloEtiqueta_v2, 4, ',', '.'),
                ]);

                $totalVariedad_v2['cajas_equivalentes'] += $totalEtiqueta_v2['cajas_equivalentes'];
                $totalVariedad_v2['total_kilos'] += $totalEtiqueta_v2['total_kilos'];
                $totalVariedad_v2['rnp_total'] += $totalEtiqueta_v2['rnp_total'];
                $totalVariedad_v2['rnp_kilo_sum'] += $totalEtiqueta_v2['rnp_kilo_sum'];
                $totalVariedad_v2['rnp_kilo_kilos'] += $totalEtiqueta_v2['rnp_kilo_kilos'];
            }

            // Total por variedad
            $rnpKiloVariedad_v2 = $totalVariedad_v2['rnp_kilo_kilos'] ? ($totalVariedad_v2['rnp_kilo_sum'] / $totalVariedad_v2['rnp_kilo_kilos']) : 0;
            $formattedData->push([
                'Variedad' => 'Total ' . $variedad_v2,
                'Etiqueta' => '',
                'Semana' => '',
                'Serie' => '',
                'Color' => '',
                'Curva Calibre' => '',
                'Cajas' => round($totalVariedad_v2['cajas_equivalentes']),
                'Kilos Totales' => number_format($totalVariedad_v2['total_kilos'], 2, ',', '.'),
                'RNP Total' => number_format($totalVariedad_v2['rnp_total'], 2, ',', '.'),
                'RNP Kilo' => number_format($rnpKiloVariedad_v2, 4, ',', '.'),
            ]);

            $totalGeneral_v2['cajas_equivalentes'] += $totalVariedad_v2['cajas_equivalentes'];
            $totalGeneral_v2['total_kilos'] += $totalVariedad_v2['total_kilos'];
            $totalGeneral_v2['rnp_total'] += $totalVariedad_v2['rnp_total'];
            $totalGeneral_v2['rnp_kilo_sum'] += $totalVariedad_v2['rnp_kilo_sum'];
            $totalGeneral_v2['rnp_kilo_kilos'] += $totalVariedad_v2['rnp_kilo_kilos'];
        }

        // Total general
        $rnpKiloGeneral_v2 = $totalGeneral_v2['rnp_kilo_kilos'] ? ($totalGeneral_v2['rnp_kilo_sum'] / $totalGeneral_v2['rnp_kilo_kilos']) : 0;
        $formattedData->push([
            'Variedad' => 'Total General',
            'Etiqueta' => '',
            'Semana' => '',
            'Serie' => '',
            'Color' => '',
            'Curva Calibre' => '',
            'Cajas' => round($totalGeneral_v2['cajas_equivalentes']),
            'Kilos Totales' => number_format($totalGeneral_v2['total_kilos'], 2, ',', '.'),
            'RNP Total' => number_format($totalGeneral_v2['rnp_total'], 2, ',', '.'),
            'RNP Kilo' => number_format($rnpKiloGeneral_v2, 4, ',', '.'),
        ]);

        return $formattedData;
    }

    public function headings(): array
    {
        return [
            'Variedad',
            'Etiqueta',
            'Serie',
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
