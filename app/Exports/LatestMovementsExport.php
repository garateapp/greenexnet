<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LatestMovementsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Collection $movements;

    public function __construct(Collection $movements)
    {
        $this->movements = $movements;
    }

    public function collection(): Collection
    {
        return $this->movements;
    }

    public function headings(): array
    {
        return [
            'Personal',
            'Nombre',
            'Departamento',
            'Primera entrada',
            'Ultima salida',
            'Estado',
        ];
    }

    public function map($row): array
    {
        return [
            $row->personal_id,
            $row->nombre,
            $row->departamento,
            optional($row->primera_entrada)?->format('Y-m-d H:i'),
            optional($row->ultima_salida)?->format('Y-m-d H:i'),
            $row->ultima_salida ? 'Fuera' : 'Dentro',
        ];
    }
}
