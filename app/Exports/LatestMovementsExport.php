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
            'Entrada',
            'Salida',
            'Estado',
        ];
    }

    public function map($row): array
    {
        $entrada = $row->primera_entrada ?? $row->last_entry_at;
        $salida = $row->ultima_salida ?? $row->last_exit_at;

        return [
            $row->personal_id,
            $row->nombre,
            $row->departamento,
            optional($entrada)?->format('Y-m-d H:i'),
            optional($salida)?->format('Y-m-d H:i'),
            $salida ? 'Fuera' : 'Dentro',
        ];
    }
}
