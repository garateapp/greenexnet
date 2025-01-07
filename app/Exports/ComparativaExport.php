<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComparativaExport implements FromCollection, WithHeadings
{
    protected $dataComparativa;

    public function __construct(Collection $dataComparativa)
    {
        $this->dataComparativa = $dataComparativa;
    }

    /**
     * Devuelve los datos a exportar.
     */
    public function collection()
    {
        return $this->dataComparativa;
    }

    /**
     * Define las cabeceras del Excel.
     */
    public function headings(): array
    {
        return array_keys($this->dataComparativa->first());
    }
}
