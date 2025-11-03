<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PackingListExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @param  \Illuminate\Support\Collection<int,\App\Models\Embarque>  $records
     */
    public function __construct(protected Collection $records)
    {
    }

    public function collection(): Collection
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'Transporte',
            'Fecha Despacho',
            'N° Embarque',
            'Destinatario',
            'Packing Origen',
            'Folio',
            'Etiqueta',
            'Tipo Embalaje',
            'Peso Std Embalaje',
            'Especie',
            'Variedad',
            'Categoría',
            'Fecha Producción',
            'Productor Rotulado',
            'CSG Productor',
            'Comuna Productor',
            'Calibre',
            'Cantidad',
            'Contenedor',
        ];
    }

    public function map($embarque): array
    {
        return [
            $embarque->transporte,
            $this->formatDate($embarque->fecha_despacho),
            $embarque->num_embarque,
            $embarque->c_destinatario,
            $embarque->c_packing_origen,
            $embarque->folio,
            $embarque->etiqueta,
            $embarque->t_embalaje,
            (float) ($embarque->peso_std_embalaje ?? 0),
            $embarque->especie,
            $embarque->variedad,
            $embarque->n_categoria,
            $this->formatDate($embarque->fecha_produccion, 'd-m-Y'),
            $embarque->n_productor_rotulacion,
            $embarque->csg_productor,
            $embarque->comuna_productor_rotulacion,
            $embarque->n_calibre,
            (float) ($embarque->cantidad ?? 0),
            $embarque->num_contenedor,
        ];
    }

    protected function formatDate($value, string $format = 'd-m-Y H:i'): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->format($format);
        } catch (\Throwable $th) {
            return $value;
        }
    }
}
