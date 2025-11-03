<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SeguimientoEmbarquesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @var \Illuminate\Support\Collection<int,\App\Models\Embarque>
     */
    protected Collection $embarques;

    public function __construct(Collection $embarques)
    {
        $this->embarques = $embarques;
    }

    public function collection(): Collection
    {
        return $this->embarques;
    }

    public function headings(): array
    {
        return [
            'Temporada',
            'Semana',
            'N° Embarque',
            'Cliente',
            'Planta de Carga',
            'Naviera',
            'Nave',
            'AWB/N° Res.Nav',
            'Contenedor',
            'Pallets',
            'Cajas',
            'Especie',
            'Variedad',
            'Embalaje',
            'Etiqueta',
            'Peso Neto',
            'Puerto de Embarque',
            'País Destino',
            'Puerto Destino',
            'Transporte',
            'ETD Estimado',
            'ETA Estimado',
            'Zarpe Real',
            'Arribo Real',
            'Estado',
            'Descargado',
            'Retirado Full',
            'Dev. Vacío',
            'Notas',
            'N° Orden',
            'Tipo Especie',
        ];
    }

    public function map($embarque): array
    {
        $formatDate = static function ($value) {
            if (empty($value)) {
                return null;
            }

            try {
                return Carbon::parse($value)->format('d-m-Y H:i');
            } catch (\Throwable $th) {
                return $value;
            }
        };

        return [
            $embarque->temporada,
            $embarque->semana,
            $embarque->num_embarque,
            $embarque->n_cliente,
            $embarque->planta_carga,
            $embarque->n_naviera,
            $embarque->nave,
            $embarque->numero_reserva_agente_naviero,
            $embarque->num_contenedor,
            (float) ($embarque->cant_pallets ?? 0),
            (float) ($embarque->cajas ?? 0),
            $embarque->especie,
            $embarque->variedad,
            $embarque->embalajes,
            $embarque->etiqueta,
            (float) ($embarque->peso_neto ?? 0),
            $embarque->puerto_embarque,
            $embarque->pais_destino,
            $embarque->puerto_destino,
            $embarque->transporte,
            $formatDate($embarque->etd_estimado),
            $formatDate($embarque->eta_estimado),
            $formatDate($embarque->fecha_zarpe_real),
            $formatDate($embarque->fecha_arribo_real),
            $embarque->estado,
            $embarque->descargado,
            $embarque->retirado_full,
            $embarque->devuelto_vacio,
            $embarque->notas,
            $embarque->num_orden,
            $embarque->tipo_especie,
        ];
    }
}
