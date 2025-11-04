<?php

namespace App\Services;

use App\Models\Embarque;
use App\Models\ClientesComex;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EmbarqueImporter
{
    /**
     * Importa embarques desde la vista externa al sistema local.
     *
     * @param  \Carbon\Carbon|null  $seasonStart
     * @param  bool  $onlyNewSinceLast
     * @return array{processed:int,created:int,updated:int,skipped:int}
     */
    public function import(?Carbon $seasonStart = null, bool $onlyNewSinceLast = true): array
    {
        $seasonStart = ($seasonStart ?? Carbon::now()->subMonths(12))->startOfDay();
        $seasonStartString = $seasonStart->format('Y-m-d H:i:s');
        $lastExternalId = $onlyNewSinceLast ? Embarque::max('origen_embarque_id') : null;
        $lastImported = ($onlyNewSinceLast && empty($lastExternalId))
            ? Embarque::orderByDesc('origen_embarque_id')->first()
            : null;



        $query = DB::connection('sqlsrv')
            ->table('dbo.V_PKG_Embarques')
            ->select([
                'id_embarque',
                'n_embarque',
                'numero_referencia',
                'id_destinatario',
                'c_destinatario',
                'n_destinatario',
                'fecha_embarque',
                'fecha_g_despacho',
                'n_packing_origen',
                'c_packing_origen',
                'n_naviera',
                'n_nave',
                'nave',
                'contenedor',
                'N_Especie as especie',
                'N_Variedad as variedad_detalle',
                'n_embalaje',
                't_embalaje',
                'peso_std_embalaje',
                'n_etiqueta',
                'folio',
                'n_categoria',
                'fecha_produccion',
                'n_productor_rotulacion',
                'CSG_productor as csg_productor',
                'comuna_productor_rotulacion',
                'n_calibre',
                DB::raw('ISNULL(Cantidad, 0) as cantidad_detalle'),
                DB::raw('ISNULL(peso_neto, 0) as peso_neto_detalle'),
                'n_puerto_origen',
                'n_pais_destino',
                'n_puerto_destino',
                'transporte',
                'etd',
                'eta',
                'numero_reserva_agente_naviero',
                'total_pallets',
            ])

            ->where('id_exportadora', '=', '22')
            ->whereNotNull('id_destinatario')
            ->whereNotNull('n_destinatario')
            ->where('c_destinatario', 'NOT LIKE', 'NAC%');
           // ->where('id_embarque','>',$lastExternalId? $lastExternalId->origen_embarque_id:0)


        if ($onlyNewSinceLast && $lastExternalId) {
            $query->where('id_embarque', '>', $lastExternalId);
        } elseif ($onlyNewSinceLast && $lastImported && $lastImported->num_embarque) {
            $query->where(function ($builder) use ($lastImported) {
                $builder->where('n_embarque', '>', $lastImported->num_embarque)
                    ->orWhereNull('n_embarque');
            });
        }

        $rows = collect($query->get());

        if ($rows->isEmpty()) {
            return [
                'processed' => 0,
                'created' => 0,
                'updated' => 0,
                'skipped' => 0,
            ];
        }

        $parseDate = static function ($value) {
            if (empty($value)) {
                return null;
            }

            try {
                return Carbon::parse($value);
            } catch (\Throwable $th) {
                Log::warning('Fecha de embarque inválida en importación', ['valor' => $value]);

                return null;
            }
        };

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $record) {
            /** @var \stdClass $first */
            $first = $record;

            $etd = $parseDate($first->etd);
            $eta = $parseDate($first->eta);
            $fechaEmbarque = $parseDate($record->fecha_g_despacho);
            $fechaReferencia = $fechaEmbarque ?? $etd ?? $eta;

            $temporada = null;
            $semana = null;
            $fechaDespacho = null;

            if ($fechaReferencia) {
                $year = (int) $fechaReferencia->format('Y');
                $currentYear = (int) date('Y');
                $temporada = $currentYear === $year
                    ? sprintf('%d-%d', $year, $year + 1)
                    : sprintf('%d-%d', $year - 1, $year);
                $semana = (int) $fechaReferencia->format('W');
                $fechaDespacho = $record->fecha_g_despacho;
            }

            $identificador = $first->n_embarque ?: $first->numero_referencia;
            if (empty($identificador)) {
                Log::warning('Registro de embarque sin identificador, se omite', [
                    'id_destinatario' => $first->id_destinatario,
                    'fecha_g_despacho' => $first->fecha_g_despacho,
                ]);
                $skipped++;

                continue;
            }
            $codCx = explode('-', $record->c_destinatario)[0];
            $clientesComex = ClientesComex::where('codigo_cliente', '=', $codCx)->first();
            Log::info('ClientesComex', ['clientesComex' => $clientesComex, 'codCx' => $codCx]);
            if (!$clientesComex) {
                Log::warning('Registro de Cliente sin destinatario, se omite', [
                    'c_destinatario' => $record->c_destinatario,
                    'embarque' => $record->n_embarque,
                ]);
                $skipped++;
                continue;
            }
            $peso_embalaje='';
            if (preg_match('/[-+]?[0-9]*[.,]?[0-9]+/', $record->t_embalaje, $coincidencias)) {
    // Normalizamos la coma como punto
                    $peso_embalaje = (float) str_replace(',', '.', $coincidencias[0]);
                } else {
                    $peso_embalaje = null; // No hay número
                }



            $data = [
                'temporada' => '2025-2026',
                'num_embarque' => $record->n_embarque,
                'origen_embarque_id' => $record->id_embarque ?? null,
                'id_cliente' => $record->id_destinatario,
                'c_destinatario' => $record->c_destinatario,
                'n_cliente' => $clientesComex->nombre_fantasia,
                'semana' => $semana,
                'fecha_despacho' => $fechaDespacho,
                'planta_carga' => $record->n_packing_origen,
                'c_packing_origen' => $record->c_packing_origen,
                'n_naviera' => $record->n_naviera,
                'nave' => $record->n_nave ?? $record->nave,
                'num_contenedor' => $record->contenedor,
                'especie' => $record->especie,
                'variedad' => $record->variedad_detalle,
                'embalajes' => $peso_embalaje,
                't_embalaje' => $record->t_embalaje,
                'peso_std_embalaje' => $record->peso_std_embalaje,
                'etiqueta' => $record->n_etiqueta,
                'cajas' => (int) round($record->cantidad_detalle),
                'cantidad' => (int) round($record->cantidad_detalle),
                'peso_neto' => (float) $record->peso_neto_detalle,
                'puerto_embarque' => $record->n_puerto_origen,
                'pais_destino' => $record->n_pais_destino,
                'puerto_destino' => $record->n_puerto_destino,
                'mercado' => $record->transporte,
                'etd_estimado' => $record->etd,
                'eta_estimado' => $record->eta,
                'numero_reserva_agente_naviero' => $record->numero_reserva_agente_naviero,
                'cant_pallets' => $record->total_pallets,
                'transporte' => trim((string) $record->transporte),
                'folio' => $record->folio,
                'n_categoria' => $record->n_categoria,
                'fecha_produccion' => $record->fecha_produccion,
                'n_productor_rotulacion' => $record->n_productor_rotulacion,
                'csg_productor' => $record->csg_productor,
                'comuna_productor_rotulacion' => $record->comuna_productor_rotulacion,
                'n_calibre' => $record->n_calibre,
            ];

            $uniqueKeys = !empty($data['origen_embarque_id'])
                ? ['origen_embarque_id' => $data['origen_embarque_id']]
                : [
                    'num_embarque' => $data['num_embarque'],
                    'id_cliente' => $data['id_cliente'],
                ];

            /** @var Embarque $model */
            $model = Embarque::Create($data);

            if ($model->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }


        return [
            'processed' => $rows->count(),
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
        ];
    }
}


