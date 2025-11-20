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
     * @param  string|null $specificEmbarque
     * @return array{processed:int,created:int,updated:int,skipped:int}
     */
    public function import(?Carbon $seasonStart = null, bool $onlyNewSinceLast = false, ?string $specificEmbarque = null): array
    {
        // $seasonStart = ($seasonStart ?? Carbon::now()->subMonths(12))->startOfDay();
        // $seasonStartString = $seasonStart->format('Y-m-d H:i:s');
        // $lastExternalId = $onlyNewSinceLast ? Embarque::max('origen_embarque_id') : null;
        // $lastImported = ($onlyNewSinceLast && empty($lastExternalId))
        //     ? Embarque::orderByDesc('origen_embarque_id')->first()
        //     : null;

        $baseEmbarquesQuery = DB::connection('sqlsrv')
            ->table('dbo.PKG_Embarques')
            ->where('id_adm_p_entidades_exportadora', '=', '22');

        if ($specificEmbarque) {
            $baseEmbarquesQuery->where(function ($query) use ($specificEmbarque) {
                $query->where('numero', $specificEmbarque);

            });
        }
        Log::debug("message:", [$baseEmbarquesQuery]);
        $baseEmbarques = $baseEmbarquesQuery
            ->get()
            ->keyBy('id');

        if ($baseEmbarques->isEmpty() && !$specificEmbarque) {
            return [
                'processed' => 0,
                'created' => 0,
                'updated' => 0,
                'skipped' => 0,
            ];
        }
        Log::info('Embarques obtenidos: ' , [$baseEmbarques]);
        $resolveEmbarqueNumber = static function ($record) {
            foreach (['numero'] as $field) {
                if (!empty($record->{$field})) {
                    return trim((string) $record->{$field});
                }
            }

            return null;
        };

        $preservedValues = [];
        $embarqueNumbers = $baseEmbarques
            ->map(fn ($record) => $resolveEmbarqueNumber($record))
            ->filter()
            ->unique()
            ->values();
        Log::info('Embarques a importar: ' . $embarqueNumbers->count());
        if ($embarqueNumbers->isNotEmpty()) {
            $existingRecords = Embarque::query()
                ->whereIn('num_embarque', $embarqueNumbers->all())
                ->get()
                ->groupBy('num_embarque');

            foreach ($existingRecords as $numEmbarque => $recordsGroup) {
                /** @var Embarque $firstExisting */
                $firstExisting = $recordsGroup->first();
                $preservedValues[$numEmbarque] = [
                    'cant_pallets' => $recordsGroup->pluck('cant_pallets')->filter()->first() ?? $firstExisting->cant_pallets,
                    'estado' => $firstExisting->estado,
                    'fecha_zarpe_real' => $firstExisting->fecha_zarpe_real,
                    'fecha_arribo_real' => $firstExisting->fecha_arribo_real,
                ];

                Embarque::whereIn('id', $recordsGroup->pluck('id'))->delete();
            }
        }

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

            ->where('n_exportadora_embarque', '=', 'Greenex SpA')
            ->whereNotNull('id_destinatario')
            ->whereNotNull('n_destinatario')
            ->where('c_destinatario', 'NOT LIKE', 'NAC%');

        if ($specificEmbarque) {
            $query->where(function ($builder) use ($specificEmbarque) {
                $builder->where('n_embarque', $specificEmbarque)
                    ->orWhere('numero_referencia', "i".$specificEmbarque);
            });
        } else {
            $query->whereIn('id_embarque', $baseEmbarques->keys()->all());
        }
        Log::info('Embarques a importar: ' . $query->toSql());
           // ->where('id_embarque','>',$lastExternalId? $lastExternalId->origen_embarque_id:0)


        // if ($onlyNewSinceLast && $lastExternalId) {
        //     $query->where('id_embarque', '>', $lastExternalId);
        // } elseif ($onlyNewSinceLast && $lastImported && $lastImported->num_embarque) {
        //     $query->where(function ($builder) use ($lastImported) {
        //         $builder->where('n_embarque', '>', $lastImported->num_embarque)
        //             ->orWhereNull('n_embarque');
        //     });
        // }

        $rows = collect($query->get());

        if ($rows->isEmpty()) {
            return [
                'processed' => 0,
                'created' => 0,
                'updated' => 0,
                'skipped' => 0,
            ];
        }

        // $incomingTotals = $rows
        //     ->groupBy(function ($record) {
        //         $identifier = $record->n_embarque ?: $record->numero_referencia;

        //         return $identifier ?: null;
        //     })
        //     ->filter(function ($group, $identifier) {
        //         return !empty($identifier);
        //     })
        //     ->map(function ($group) {
        //         return (int) $group->sum(function ($row) {
        //             return (int) round($row->cantidad_detalle);
        //         });
        //     });

        // if ($incomingTotals->isNotEmpty()) {
        //     $existingTotals = Embarque::query()
        //         ->select('num_embarque', DB::raw('SUM(cajas) as total_cajas'))
        //         ->whereIn('num_embarque', $incomingTotals->keys()->all())
        //         ->groupBy('num_embarque')
        //         ->get()
        //         ->keyBy('num_embarque');

        //     foreach ($incomingTotals as $numEmbarque => $totalCajas) {
        //         $existing = $existingTotals->get($numEmbarque);

        //         if ($existing && (int) $existing->total_cajas !== $totalCajas) {
        //             Embarque::where('num_embarque', $numEmbarque)->delete();
        //         }
        //     }
        // }

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
        $preserveOnUpdate = ['cant_pallets', 'estado', 'fecha_zarpe_real', 'fecha_arribo_real'];

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

            $baseInfo = $baseEmbarques->get($record->id_embarque);
            if (!$baseInfo) {
                if ($specificEmbarque) {
                    $baseInfo = (object) [
                        'id_embarque' => $record->id_embarque,
                        'n_embarque' => $record->n_embarque,
                        'numero' => $record->numero_referencia,
                        'numero_referencia' => $record->numero_referencia,
                        'id_destinatario' => $record->id_destinatario,
                        'c_destinatario' => $record->c_destinatario,
                        'fecha_g_despacho' => $record->fecha_g_despacho,
                        'n_packing_origen' => $record->n_packing_origen,
                        'c_packing_origen' => $record->c_packing_origen,
                        'n_naviera' => $record->n_naviera,
                        'n_nave' => $record->n_nave ?? $record->nave,
                        'nave' => $record->nave,
                        'contenedor' => $record->contenedor,
                        'transporte' => $record->transporte,
                        'cant_pallets' => $record->total_pallets,
                        'estado' => null,
                        'fecha_zarpe_real' => null,
                        'fecha_arribo_real' => null,
                    ];
                } else {
                    $skipped++;

                    continue;
                }
            }

            $destinatarioCode = $record->c_destinatario ?? null;
            if (!$destinatarioCode) {
                Log::warning('Registro de embarque sin destinatario, se omite', [
                    'embarque' => $record->n_embarque ?? $baseInfo->n_embarque,
                ]);
                $skipped++;

                continue;
            }

            $codCx = explode('-', $destinatarioCode)[0];
            Log::debug('ClientesComex', ['codCx' => $codCx]);
            $clientesComex = ClientesComex::where('codigo_cliente', '=', $codCx)->first();
            Log::info('ClientesComex', ['clientesComex' => $clientesComex, 'codCx' => $codCx]);
            if (!$clientesComex) {
                Log::warning('Registro de Cliente sin destinatario, se omite', [
                    'c_destinatario' => $destinatarioCode,
                    'embarque' => $record->n_embarque ?? $baseInfo->n_embarque,
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
            $numeroEmbarque = $resolveEmbarqueNumber($record) ?? $resolveEmbarqueNumber($baseInfo);
            $preservedForCurrent = $numeroEmbarque && isset($preservedValues[$numeroEmbarque])
                ? $preservedValues[$numeroEmbarque]
                : null;

            $data = [
                'temporada' => '2025-2026',
                'num_embarque' => $numeroEmbarque,
                'origen_embarque_id' => $record->id_embarque ?? $baseInfo->id_embarque ?? null,
                'id_cliente' => $record->id_destinatario ?? $baseInfo->id_destinatario,
                'c_destinatario' => $record->c_destinatario ?? $baseInfo->c_destinatario,
                'n_cliente' => $clientesComex->nombre_fantasia,
                'semana' => $semana,
                'fecha_despacho' => $fechaDespacho ?? $baseInfo->fecha_g_despacho ?? null,
                'planta_carga' => $record->n_packing_origen ?? $baseInfo->n_packing_origen,
                'c_packing_origen' => $record->c_packing_origen ?? $baseInfo->c_packing_origen,
                'n_naviera' => $record->n_naviera ?? $baseInfo->n_naviera,
                'nave' => $record->n_nave ?? $record->nave ,
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
                'mercado' => $record->transporte ?? $baseInfo->transporte,
                'etd_estimado' => $record->etd,
                'eta_estimado' => $record->eta,
                'numero_reserva_agente_naviero' => $record->numero_reserva_agente_naviero,
                'cant_pallets' => $preservedForCurrent['cant_pallets']
                    ?? $baseInfo->cant_pallets
                    ?? $record->total_pallets,
                'transporte' => trim((string) ($baseInfo->transporte ?? $record->transporte)),
                'folio' => $record->folio,
                'n_categoria' => $record->n_categoria,
                'fecha_produccion' => $record->fecha_produccion,
                'n_productor_rotulacion' => $record->n_productor_rotulacion,
                'csg_productor' => $record->csg_productor,
                'comuna_productor_rotulacion' => $record->comuna_productor_rotulacion,
                'n_calibre' => $record->n_calibre,
                'estado' => $preservedForCurrent['estado']
                    ?? $baseInfo->estado
                    ?? null,
                'fecha_zarpe_real' => $preservedForCurrent['fecha_zarpe_real']
                    ?? $baseInfo->fecha_zarpe_real
                    ?? null,
                'fecha_arribo_real' => $preservedForCurrent['fecha_arribo_real']
                    ?? $baseInfo->fecha_arribo_real
                    ?? null,
            ];

            $embarque = Embarque::Create($data);
            Log::debug('Embarque creado: ' . $embarque->n_embarque);
            $created++;

        }


        return [
            'processed' => $rows->count(),
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
        ];
    }
}
