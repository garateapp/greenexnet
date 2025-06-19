<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class InstructivoMaritimoExport implements FromArray, WithStyles, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];

        // Header Section
        $rows[] = ['Exportador', $this->data['exportador']['nombre'], '', 'Instructivo de Embarque Marítimo', '', '', ''];
        $rows[] = ['Rut', $this->data['exportador']['rut'], '', '', '', '', ''];
        $rows[] = ['Dirección', $this->data['exportador']['direccion'], '', 'Temporada 2024 - 2025', '', 'Embarque N°', $this->data['embarque']['numero']];
        $rows[] = ['Attn', $this->data['exportador']['contacto'] . ' // Teléfono: ' . $this->data['exportador']['telefono'], '', '', '', 'Fecha', $this->data['embarque']['fecha']];
        $rows[] = ['E-mail', $this->data['exportador']['email'], '', '', '', '', ''];
        $rows[] = [''];

        // Embarcador y Agente de Aduanas
        $rows[] = ['Embarcador', '', '', 'Agente de Aduanas', '', '', ''];
        $rows[] = ['Embarcador', $this->data['embarcador']['nombre'], '', 'Agencia de aduana', $this->data['agente_aduana']['nombre'], '', ''];
        $rows[] = ['Rut', $this->data['embarcador']['rut'], '', 'Rut', $this->data['agente_aduana']['rut'], '', ''];
        $rows[] = ['Dirección', $this->data['embarcador']['direccion'], '', 'Dirección', $this->data['agente_aduana']['direccion'], '', ''];
        $rows[] = ['Attn', $this->data['embarcador']['contacto'], 'Teléfono', $this->data['embarcador']['telefono'], 'Código de Agente', $this->data['agente_aduana']['codigo'], 'Teléfono', $this->data['agente_aduana']['telefono']];
        $rows[] = ['E-mail', $this->data['embarcador']['email'], '', 'E-mail', $this->data['agente_aduana']['email'], '', ''];
        $rows[] = [''];

        // Consignee y Notify
        $rows[] = ['Consignee y Notify', '', '', '', '', '', ''];
        $rows[] = ['Consignee', $this->data['consignee']['nombre'], '', '', 'Notify', $this->data['notify']['nombre'], '', ''];
        $rows[] = ['N° ID', $this->data['consignee']['id'], '', '', 'N° ID', $this->data['notify']['id'], '', ''];
        $rows[] = ['Dirección', $this->data['consignee']['direccion'], '', '', 'Dirección', $this->data['notify']['direccion'], '', ''];
        $rows[] = ['Contacto', $this->data['consignee']['contacto'], '', '', 'Contacto', $this->data['notify']['contacto'], '', ''];
        $rows[] = ['Teléfono', $this->data['consignee']['telefono'], '', '', 'Teléfono', $this->data['notify']['telefono'], '', ''];
        $rows[] = ['Email', $this->data['consignee']['email'], '', '', 'Email', $this->data['notify']['email'], '', ''];
        $rows[] = [''];

        // Detalle de Embarque
        $rows[] = ['Detalle de Embarque', '', '', '', '', '', ''];
        $rows[] = ['Naviera', $this->data['detalle_embarque']['naviera'], '', 'N° Contenedor', $this->data['detalle_embarque']['num_contenedor'], 'Ventilacion', $this->data['detalle_embarque']['ventilacion']];
        $rows[] = ['N° Booking', $this->data['detalle_embarque']['num_booking'], '', 'Tara Contenedor', $this->data['detalle_embarque']['tara_contenedor'], 'Quest', $this->data['detalle_embarque']['quest']];
        $rows[] = ['Nave', $this->data['detalle_embarque']['nave'], '', 'N° Sello', $this->data['detalle_embarque']['num_sello'], 'Temperatura', $this->data['detalle_embarque']['temperatura']];
        $rows[] = ['Cut-Off', $this->data['detalle_embarque']['cut_off'], '', 'Empresa Transportista', $this->data['detalle_embarque']['empresa_transportista'], '', ''];
        $rows[] = ['Stacking', $this->data['detalle_embarque']['stacking'], '', 'Conductor', $this->data['detalle_embarque']['conductor'], 'Rut', $this->data['detalle_embarque']['rut_conductor']];
        $rows[] = ['ETD', $this->data['detalle_embarque']['etd'], '', 'PPU', $this->data['detalle_embarque']['ppu'], 'Telefono', $this->data['detalle_embarque']['telefono']];
        $rows[] = ['ETA', $this->data['detalle_embarque']['eta'], '', 'Planta de Carga', $this->data['detalle_embarque']['planta_carga'], '', ''];
        $rows[] = ['Puerto Embarque', $this->data['detalle_embarque']['puerto_embarque'], 'Pais Embarque', $this->data['detalle_embarque']['pais_embarque'], 'Dirección', $this->data['detalle_embarque']['direccion_carga'], ''];
        $rows[] = ['Puerto Destino', $this->data['detalle_embarque']['puerto_destino'], 'Pais Destino', $this->data['detalle_embarque']['pais_destino'], 'Fecha de Carga', $this->data['detalle_embarque']['fecha_carga'], 'Hora de Carga', $this->data['detalle_embarque']['hora_carga']];
        $rows[] = ['C.O. Puerto de Descarga', $this->data['detalle_embarque']['puerto_descarga'], '', 'Guia Despacho Dirigida', $this->data['detalle_embarque']['guia_despacho'], '', ''];
        $rows[] = ['PHYTO. Punto de Entrada', $this->data['detalle_embarque']['punto_entrada'], '', 'Planilla Sag Dirigida', $this->data['detalle_embarque']['planilla_sag'], '', ''];
        $rows[] = [''];

        // Antecedentes Comerciales
        $rows[] = ['Antecedentes Comerciales', '', '', '', '', '', ''];
        $rows[] = ['N° PO', $this->data['comerciales']['num_po'], '', 'Moneda', $this->data['comerciales']['moneda'], '', 'Emisión de BL', $this->data['comerciales']['emision_bl']];
        $rows[] = ['Forma de pago', $this->data['comerciales']['forma_pago'], '', 'Tipo de flete', $this->data['comerciales']['tipo_flete'], '', ''];
        $rows[] = ['Modalidad venta', $this->data['comerciales']['modalidad_venta'], '', 'Cláusula de venta', $this->data['comerciales']['clausula_venta'], '', ''];
        $rows[] = [''];

        // Detalle de la Carga
        $rows[] = ['Detalle de la carga', '', '', '', '', '', '', '', '', '', '', ''];
        $rows[] = ['Especie', 'Variedad', 'Calibres', 'Cajas', 'Etiqueta', 'Pallet', 'Categoria', 'Envase', 'Peso neto', 'Peso bruto', 'Total Neto', 'Total Bruto'];
        foreach ($this->data['carga'] as $item) {
            $rows[] = [
                $item['especie'],
                $item['variedad'],
                $item['calibres'],
                $item['cajas'],
                $item['etiqueta'],
                $item['pallet'],
                $item['categoria'],
                $item['envase'],
                $item['peso_neto'],
                $item['peso_bruto'],
                $item['total_neto'],
                $item['total_bruto']
            ];
        }
        $rows[] = [''];
        $rows[] = ['Total Cajas', $this->data['carga_totals']['total_cajas'], '', 'Total Neto', $this->data['carga_totals']['total_neto'], '', 'Total Peso Pallet', $this->data['carga_totals']['total_peso_pallet'], '', ''];
        $rows[] = ['Cantidad de Pallet', $this->data['carga_totals']['cantidad_pallet'], '', 'Total Bruto', $this->data['carga_totals']['total_bruto'], '', 'Total Peso Carga', $this->data['carga_totals']['total_peso_carga'], '', ''];
        $rows[] = [''];

        // Observaciones
        $rows[] = ['Observaciones', '', '', '', '', '', ''];
        foreach ($this->data['observaciones'] as $obs) {
            $rows[] = [$obs];
        }
        $rows[] = [''];

        // Instrucciones Agencia de Aduanas
        $rows[] = ['Agencia de Aduanas: Instrucciones Para Documentos de Exportacion', '', '', '', '', '', ''];
        foreach ($this->data['instrucciones_aduana'] as $inst) {
            $rows[] = [$inst];
        }
        $rows[] = [''];

        // Instrucciones Frigorifico
        $rows[] = ['Frigorifico: Instrucciones Para Planta de Carga', '', '', '', '', '', ''];
        foreach ($this->data['instrucciones_frigorifico'] as $inst) {
            $rows[] = [$inst];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for section titles
        $sectionTitles = [
            1 => 'A1:G1', // Exportador
            7 => 'A7:G7', // Embarcador
            14 => 'A14:G14', // Consignee y Notify
            22 => 'A22:G22', // Detalle de Embarque
            35 => 'A35:G35', // Antecedentes Comerciales
            41 => 'A41:L41', // Detalle de la Carga
            47 => 'A47:G47', // Observaciones
            49 => 'A49:G49', // Instrucciones Agencia de Aduanas
            56 => 'A56:G56', // Instrucciones Frigorifico
        ];

        foreach ($sectionTitles as $row => $range) {
            $sheet->mergeCells($range);
            $sheet->getStyle($range)->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 10,
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD3D3D3'], // Light gray
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ]);
        }

        // Style for data rows
        $dataRanges = [
            'A2:B5', 'D3:G4', // Exportador
            'A8:B12', 'D8:G12', // Embarcador y Agente
            'A15:B20', 'E15:G20', // Consignee y Notify
            'A23:G34', // Detalle de Embarque
            'A36:G39', // Antecedentes Comerciales
            'A42:L46', // Detalle de la Carga
            'A48:G48', // Observaciones
            'A50:G55', // Instrucciones Agencia de Aduanas
            'A57:G58', // Instrucciones Frigorifico
        ];

        foreach ($dataRanges as $range) {
            $sheet->getStyle($range)->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 10,
                    'bold' => false,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                ],
            ]);
        }

        // Bold labels in data sections
        $labelColumns = [
            'A2:A5', 'D3:D4', // Exportador
            'A8:A12', 'D8:D12', // Embarcador y Agente
            'A15:A20', 'E15:E20', // Consignee y Notify
            'A23:A34', 'D23:D34', // Detalle de Embarque
            'A36:A39', 'D36:D39', // Antecedentes Comerciales
            'A42:L42', // Carga headers
            'A46:A47', 'D46:D47', // Carga totals
        ];

        foreach ($labelColumns as $range) {
            $sheet->getStyle($range)->getFont()->setBold(true);
        }

        // Center numeric columns in Detalle de la Carga
        $sheet->getStyle('D43:D45')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Cajas
        $sheet->getStyle('F43:F45')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Pallet
        $sheet->getStyle('I43:L45')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Peso neto, bruto, totals

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);

        // Adjust row heights for wrapped text
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(14)->setRowHeight(20);
        $sheet->getRowDimension(22)->setRowHeight(20);
        $sheet->getRowDimension(35)->setRowHeight(20);
        $sheet->getRowDimension(41)->setRowHeight(20);
        $sheet->getRowDimension(47)->setRowHeight(20);
        $sheet->getRowDimension(49)->setRowHeight(20);
        $sheet->getRowDimension(56)->setRowHeight(20);

        return [];
    }

    public function title(): string
    {
        return 'Instructivo Maritimo';
    }
}