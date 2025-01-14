<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TratoContratistasTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            [
                'fecha' => '2024-01-01',
                'rut' => '12345678-9',

                '9' => '42',
                '7' => '0',
                '6' => '0',
                '5' => '17',
                'nombre'=> 'Pepito Perez',
                'contratista'=>'2 para Contratista 1  ó  3 para Contratista 2',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Rut',
            '9',
            '7',
            '6',
            '5',
            'Nombre',
            'Contratista',
        ];
    }
}
