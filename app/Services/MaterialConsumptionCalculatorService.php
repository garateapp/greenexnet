<?php

namespace App\Services;

use App\Models\Embalaje;
use App\Models\Material;
use App\Models\MaterialConsumptionAdjustment;
use App\Models\MaterialConsumptionProcessCalculation;
use App\Models\MaterialConsumptionProcessItem;
use App\Models\MaterialConsumptionRowPattern;
use App\Models\MaterialConsumptionRuleMaterial;
use App\Models\MaterialConsumptionRuleSet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaterialConsumptionCalculatorService
{
    public function calculate(string $processNumber, ?int $userId = null, bool $forceRecalculate = false): MaterialConsumptionProcessCalculation
    {
        $processNumber = trim($processNumber);

        if (!$forceRecalculate) {
            $existing = MaterialConsumptionProcessCalculation::query()
                ->where('process_number', $processNumber)
                ->orderByDesc('calculated_at')
                ->with(['ruleSet', 'items', 'adjustments'])
                ->first();

            if ($existing) {
                return $existing;
            }
        }

        $proratedRows = collect($this->fetchProratedRows($processNumber));
        $palletRows = $this->hydratePalletRowsWithTotalLines(collect($this->fetchPalletRows($processNumber)));
        $processMeta = $this->fetchProcessMeta($processNumber);

        $processDate = $this->resolveProcessDate($proratedRows);
        $this->bootstrapDefaultRulesIfNeeded($userId);

        $ruleSet = $this->resolveRuleSet([
            'process_date' => $processDate,
            'packaging_code' => $palletRows->first()['packaging_code'] ?? null,
            'specie_name' => null,
            'exportadora_id' => $processMeta['exportadora_id'] ?? null,
        ]);

        $proratedCostMap = $this->buildProratedCostMap($proratedRows);
        $ruleMaterials = $ruleSet
            ? $ruleSet->materials()->where('is_active', true)->with('material')->get()
            : collect();
        $patternMap = $ruleSet
            ? $ruleSet->rowPatterns()->get()->keyBy('total_rows')
            : collect();

        DB::beginTransaction();
        try {
            $calculation = MaterialConsumptionProcessCalculation::create([
                'process_number' => $processNumber,
                'rule_set_id' => optional($ruleSet)->id,
                'calculated_at' => now(),
                'status' => 'calculated',
                'source_payload' => [
                    'process_meta' => $processMeta,
                    'pallet_count' => $palletRows->count(),
                    'prorated_rows' => $proratedRows->count(),
                ],
                'created_by' => $userId,
            ]);

            $realItems = $this->buildRealItems($palletRows, $patternMap, $ruleMaterials, $proratedCostMap);
            foreach ($realItems as $item) {
                $calculation->items()->create($item);
            }

            $proratedItems = $this->buildProratedItems($proratedRows);
            foreach ($proratedItems as $item) {
                $calculation->items()->create($item);
            }

            $this->refreshTotals($calculation);

            DB::commit();

            return $calculation->fresh(['ruleSet', 'items', 'adjustments']);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return MaterialConsumptionProcessCalculation::create([
                'process_number' => $processNumber,
                'rule_set_id' => optional($ruleSet)->id,
                'calculated_at' => now(),
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'source_payload' => [
                    'process_meta' => $processMeta,
                ],
                'created_by' => $userId,
            ])->fresh(['ruleSet', 'items', 'adjustments']);
        }
    }

    public function addAdjustment(MaterialConsumptionProcessCalculation $calculation, array $data, ?int $userId = null): MaterialConsumptionProcessCalculation
    {
        $quantityDelta = (float) ($data['quantity_delta'] ?? 0);
        $unitCost = (float) ($data['unit_cost'] ?? 0);
        $costDelta = array_key_exists('cost_delta', $data)
            ? (float) $data['cost_delta']
            : ($quantityDelta * $unitCost);

        MaterialConsumptionAdjustment::create([
            'calculation_id' => $calculation->id,
            'pallet_code' => $data['pallet_code'] ?? null,
            'material_id' => $data['material_id'] ?? null,
            'material_key' => $this->normalizeMaterialKey($data['material_key'] ?? $data['material_name'] ?? 'AJUSTE'),
            'material_name' => $data['material_name'] ?? ($data['material_key'] ?? 'Ajuste manual'),
            'quantity_delta' => $quantityDelta,
            'unit_cost' => $unitCost,
            'cost_delta' => $costDelta,
            'reason' => $data['reason'] ?? null,
            'created_by' => $userId,
        ]);

        MaterialConsumptionProcessItem::create([
            'calculation_id' => $calculation->id,
            'source' => 'adjustment',
            'pallet_code' => $data['pallet_code'] ?? null,
            'total_rows' => null,
            'material_id' => $data['material_id'] ?? null,
            'material_key' => $this->normalizeMaterialKey($data['material_key'] ?? $data['material_name'] ?? 'AJUSTE'),
            'material_name' => $data['material_name'] ?? ($data['material_key'] ?? 'Ajuste manual'),
            'document_item' => null,
            'quantity' => $quantityDelta,
            'unit_cost' => $unitCost,
            'total_cost' => $costDelta,
            'details' => [
                'reason' => $data['reason'] ?? null,
                'origin' => 'manual_adjustment',
            ],
        ]);

        $this->refreshTotals($calculation);

        return $calculation->fresh(['ruleSet', 'items', 'adjustments']);
    }

    private function fetchProcessMeta(string $processNumber): array
    {
        try {
            $row = DB::connection('sqlsrv')
                ->table('PKG_G_Produccion')
                ->select('numero_i', 'id_adm_p_entidades_exportadora')
                ->where('numero_i', $processNumber)
                ->first();

            if (!$row) {
                return [];
            }

            return [
                'numero_i' => (string) ($row->numero_i ?? $processNumber),
                'exportadora_id' => $row->id_adm_p_entidades_exportadora ?? null,
            ];
        } catch (\Throwable $exception) {
            return [];
        }
    }

    private function fetchProratedRows(string $processNumber): array
    {
        $sql = "
            SELECT
                n_unidad,
                fecha_cargo,
                n_item,
                cantidad,
                valor_total,
                costo_total,
                cantidad_entregada,
                cantidad_facturada,
                mueve_stock,
                valor_unitario
            FROM dbo.V_ADM_Documentos
            WHERE numero_doc = 2
              AND id_doc IN (
                SELECT AD.id_adm_documentos
                FROM dbo.PKG_G_Produccion (NOLOCK) AS PGP
                LEFT OUTER JOIN dbo.ADM_Documentos_Det (NOLOCK) AS AD
                    ON PGP.id_adm_documentos_consumo_materiales = AD.id_adm_documentos
                WHERE PGP.numero_i = ?
              )
        ";

        try {
            return DB::connection('sqlsrv')->select($sql, [$processNumber]);
        } catch (\Throwable $exception) {
            return [];
        }
    }

    private function fetchPalletRows(string $processNumber): array
    {
        $queries = [
            "
                SELECT
                    a.folio AS pallet_code,
                    MAX(a.c_embalaje) AS packaging_code,
                    MAX(a.c_altura) AS raw_height,
                    MAX(TRY_CAST(a.c_altura AS INT)) AS parsed_rows,
                    SUM(a.cantidad) AS boxes
                FROM dbo.V_PKG_Produccion_Salidas_XXX AS a WITH (NOLOCK)
                WHERE a.t_categoria = 'exportacion'
                  AND a.tipo_g_produccion = 'PRN'
                  AND a.numero_g_produccion = ?
                GROUP BY a.folio
            ",
            "
                SELECT
                    a.folio AS pallet_code,
                    MAX(a.c_embalaje) AS packaging_code,
                    MAX(a.c_altura) AS raw_height,
                    MAX(TRY_CAST(a.c_altura AS INT)) AS parsed_rows,
                    SUM(a.cantidad) AS boxes
                FROM dbo.V_PKG_Produccion_Salidas AS a WITH (NOLOCK)
                WHERE a.t_categoria = 'exportacion'
                  AND a.tipo_g_produccion = 'PRN'
                  AND a.numero_g_produccion = ?
                GROUP BY a.folio
            ",
        ];

        foreach ($queries as $sql) {
            try {
                $rows = DB::connection('sqlsrv')->select($sql, [$processNumber]);
                if (!empty($rows)) {
                    return $rows;
                }
            } catch (\Throwable $exception) {
                continue;
            }
        }

        return [];
    }

    private function hydratePalletRowsWithTotalLines(Collection $palletRows): Collection
    {
        if ($palletRows->isEmpty()) {
            return collect();
        }

        $packagingCodes = $palletRows
            ->pluck('packaging_code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $packagingConfig = Embalaje::query()
            ->whereIn('c_embalaje', $packagingCodes)
            ->get(['c_embalaje', 'cajasxlinea', 'lineasxpallet'])
            ->keyBy('c_embalaje');

        return $palletRows->map(function ($row) use ($packagingConfig) {
            $packagingCode = $row->packaging_code ?? null;
            $boxes = (float) ($row->boxes ?? 0);
            $config = $packagingCode ? $packagingConfig->get($packagingCode) : null;
            $boxesPerLine = (float) optional($config)->cajasxlinea;
            $defaultRows = (int) optional($config)->lineasxpallet;

            $totalRows = 0;
            if ($boxesPerLine > 0 && $boxes > 0) {
                $totalRows = (int) ceil($boxes / $boxesPerLine);
            }

            if ($totalRows <= 0 && $defaultRows > 0) {
                $totalRows = $defaultRows;
            }

            if ($totalRows <= 0) {
                $totalRows = $this->normalizeRows($row->parsed_rows ?? null, $row->raw_height ?? null);
            }

            return [
                'pallet_code' => (string) ($row->pallet_code ?? ''),
                'packaging_code' => $packagingCode,
                'boxes' => $boxes,
                'total_rows' => $totalRows,
            ];
        })->values();
    }

    private function normalizeRows($parsedRows, $rawHeight): int
    {
        $value = (int) $parsedRows;
        if ($value > 0 && $value <= 20) {
            return $value;
        }

        if (is_string($rawHeight)) {
            $numbers = preg_replace('/[^0-9]/', '', $rawHeight);
            if ($numbers !== '') {
                $parsed = (int) $numbers;
                if ($parsed > 0 && $parsed <= 20) {
                    return $parsed;
                }
            }
        }

        return 0;
    }

    private function resolveProcessDate(Collection $proratedRows): Carbon
    {
        $rawDate = optional($proratedRows->first())->fecha_cargo;
        if ($rawDate) {
            try {
                return Carbon::parse($rawDate);
            } catch (\Throwable $exception) {
                return now();
            }
        }

        return now();
    }

    private function resolveRuleSet(array $context): ?MaterialConsumptionRuleSet
    {
        $processDate = $context['process_date'] ?? now();
        $packagingCode = $context['packaging_code'] ?? null;
        $specieName = $context['specie_name'] ?? null;
        $exportadoraId = $context['exportadora_id'] ?? null;

        $candidates = MaterialConsumptionRuleSet::query()
            ->active()
            ->where(function ($query) use ($processDate) {
                $query->whereNull('effective_from')
                    ->orWhere('effective_from', '<=', $processDate->toDateString());
            })
            ->where(function ($query) use ($processDate) {
                $query->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $processDate->toDateString());
            })
            ->orderBy('priority')
            ->orderBy('id')
            ->get();

        foreach ($candidates as $candidate) {
            if ($candidate->packaging_code && $packagingCode && $candidate->packaging_code !== $packagingCode) {
                continue;
            }

            if ($candidate->packaging_code && !$packagingCode) {
                continue;
            }

            if ($candidate->specie_name && $specieName && $candidate->specie_name !== $specieName) {
                continue;
            }

            if ($candidate->specie_name && !$specieName) {
                continue;
            }

            if ($candidate->exportadora_id && $exportadoraId && (int) $candidate->exportadora_id !== (int) $exportadoraId) {
                continue;
            }

            if ($candidate->exportadora_id && !$exportadoraId) {
                continue;
            }

            return $candidate;
        }

        return $candidates->first();
    }

    private function buildProratedCostMap(Collection $proratedRows): array
    {
        $map = [];

        foreach ($proratedRows as $row) {
            $materialKey = $this->normalizeMaterialKey($row->n_item ?? null);
            $unitCost = $this->extractUnitCostFromProratedRow($row);
            if ($unitCost <= 0) {
                continue;
            }

            if (!isset($map[$materialKey])) {
                $map[$materialKey] = [
                    'sum' => 0,
                    'count' => 0,
                ];
            }

            $map[$materialKey]['sum'] += $unitCost;
            $map[$materialKey]['count']++;
        }

        $avg = [];
        foreach ($map as $materialKey => $data) {
            $avg[$materialKey] = $data['count'] > 0
                ? ($data['sum'] / $data['count'])
                : 0;
        }

        return $avg;
    }

    private function buildRealItems(
        Collection $palletRows,
        Collection $patternMap,
        Collection $ruleMaterials,
        array $proratedCostMap
    ): Collection {
        $generated = collect();

        foreach ($palletRows as $palletRow) {
            $totalRows = (int) ($palletRow['total_rows'] ?? 0);
            $pattern = $this->resolvePattern($patternMap, $totalRows);

            foreach ($ruleMaterials as $ruleMaterial) {
                $quantity = $this->calculateMaterialQuantity($ruleMaterial, $pattern, $totalRows);
                if ($quantity <= 0) {
                    continue;
                }

                $unitCost = $this->resolveUnitCost($ruleMaterial, $proratedCostMap);
                $materialKey = $this->normalizeMaterialKey($ruleMaterial->material_key ?: $ruleMaterial->material_name);
                $materialName = $ruleMaterial->material_name ?: optional($ruleMaterial->material)->nombre ?: $materialKey;

                $generated->push([
                    'source' => 'rule_engine',
                    'pallet_code' => $palletRow['pallet_code'] ?: null,
                    'total_rows' => $totalRows > 0 ? $totalRows : null,
                    'material_id' => $ruleMaterial->material_id,
                    'material_key' => $materialKey,
                    'material_name' => $materialName,
                    'document_item' => null,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'total_cost' => $quantity * $unitCost,
                    'details' => [
                        'mode' => $ruleMaterial->consumption_mode,
                        'pattern_rows' => $pattern['rows_with_consumption'],
                        'vertical_straps_count' => $pattern['vertical_straps_count'],
                        'includes_corner_posts' => $pattern['includes_corner_posts'],
                        'includes_pallet' => $pattern['includes_pallet'],
                        'includes_grill' => $pattern['includes_grill'],
                    ],
                ]);
            }
        }

        return $generated
            ->groupBy(function ($item) {
                return ($item['pallet_code'] ?? 'SIN_PALLET') . '|' . $item['material_key'] . '|' . ($item['total_rows'] ?? '0');
            })
            ->map(function (Collection $group) {
                $quantity = $group->sum('quantity');
                $totalCost = $group->sum('total_cost');
                $template = $group->first();

                return [
                    'source' => 'rule_engine',
                    'pallet_code' => $template['pallet_code'],
                    'total_rows' => $template['total_rows'],
                    'material_id' => $template['material_id'],
                    'material_key' => $template['material_key'],
                    'material_name' => $template['material_name'],
                    'document_item' => null,
                    'quantity' => $quantity,
                    'unit_cost' => $quantity > 0 ? ($totalCost / $quantity) : 0,
                    'total_cost' => $totalCost,
                    'details' => [
                        'lines' => $group->pluck('details')->values()->all(),
                    ],
                ];
            })
            ->values();
    }

    private function buildProratedItems(Collection $proratedRows): Collection
    {
        return $proratedRows->map(function ($row) {
            $quantity = (float) ($row->cantidad_entregada ?? $row->cantidad ?? 0);
            $unitCost = $this->extractUnitCostFromProratedRow($row);

            $totalCost = (float) ($row->costo_total ?? 0);
            if ($totalCost <= 0) {
                $totalCost = (float) ($row->valor_total ?? 0);
            }
            if ($totalCost <= 0 && $quantity > 0) {
                $totalCost = $quantity * $unitCost;
            }

            $materialKey = $this->normalizeMaterialKey($row->n_item ?? null);

            return [
                'source' => 'prorated_document',
                'pallet_code' => null,
                'total_rows' => null,
                'material_id' => null,
                'material_key' => $materialKey,
                'material_name' => $row->n_item ?? $materialKey,
                'document_item' => $row->n_item ?? null,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'total_cost' => $totalCost,
                'details' => [
                    'n_unidad' => $row->n_unidad ?? null,
                    'fecha_cargo' => $row->fecha_cargo ?? null,
                    'cantidad_facturada' => $row->cantidad_facturada ?? null,
                    'mueve_stock' => $row->mueve_stock ?? null,
                ],
            ];
        })->values();
    }

    private function extractUnitCostFromProratedRow($row): float
    {
        $unitCost = (float) ($row->valor_unitario ?? 0);
        if ($unitCost > 0) {
            return $unitCost;
        }

        $quantity = (float) ($row->cantidad_entregada ?? $row->cantidad ?? 0);
        $total = (float) ($row->costo_total ?? 0);
        if ($total <= 0) {
            $total = (float) ($row->valor_total ?? 0);
        }

        if ($quantity > 0 && $total > 0) {
            return $total / $quantity;
        }

        return 0;
    }

    private function resolvePattern(Collection $patternMap, int $totalRows): array
    {
        $default = [
            'rows_with_consumption' => [],
            'vertical_straps_count' => 0,
            'includes_corner_posts' => false,
            'includes_pallet' => true,
            'includes_grill' => false,
        ];

        if ($patternMap->isEmpty()) {
            return $default;
        }

        if ($patternMap->has($totalRows)) {
            $pattern = $patternMap->get($totalRows);
            return $this->patternToArray($pattern);
        }

        $nearest = $patternMap
            ->keys()
            ->map(function ($value) {
                return (int) $value;
            })
            ->filter(function ($value) use ($totalRows) {
                return $value <= $totalRows;
            })
            ->sortDesc()
            ->first();

        if ($nearest) {
            return $this->patternToArray($patternMap->get($nearest));
        }

        return $default;
    }

    private function patternToArray(?MaterialConsumptionRowPattern $pattern): array
    {
        if (!$pattern) {
            return [
                'rows_with_consumption' => [],
                'vertical_straps_count' => 0,
                'includes_corner_posts' => false,
                'includes_pallet' => true,
                'includes_grill' => false,
            ];
        }

        return [
            'rows_with_consumption' => $pattern->rows_with_consumption ?: [],
            'vertical_straps_count' => (int) ($pattern->vertical_straps_count ?? 0),
            'includes_corner_posts' => (bool) $pattern->includes_corner_posts,
            'includes_pallet' => (bool) $pattern->includes_pallet,
            'includes_grill' => (bool) $pattern->includes_grill,
        ];
    }

    private function calculateMaterialQuantity(
        MaterialConsumptionRuleMaterial $ruleMaterial,
        array $pattern,
        int $totalRows
    ): float {
        if (!$ruleMaterial->is_active) {
            return 0;
        }

        if ($ruleMaterial->min_rows !== null && $totalRows < (int) $ruleMaterial->min_rows) {
            return 0;
        }

        if ($ruleMaterial->max_rows !== null && $totalRows > (int) $ruleMaterial->max_rows) {
            return 0;
        }

        if (!$this->passesRuleCondition($ruleMaterial->condition, $pattern, $totalRows)) {
            return 0;
        }

        $quantityPerUnit = (float) $ruleMaterial->quantity_per_unit;
        if ($quantityPerUnit <= 0) {
            return 0;
        }

        switch ($ruleMaterial->consumption_mode) {
            case MaterialConsumptionRuleMaterial::MODE_PER_CONSUMPTION_ROW:
                return $quantityPerUnit * count($pattern['rows_with_consumption'] ?? []);

            case MaterialConsumptionRuleMaterial::MODE_PER_VERTICAL_STRAP:
                return $quantityPerUnit * (int) ($pattern['vertical_straps_count'] ?? 0);

            case MaterialConsumptionRuleMaterial::MODE_CONDITIONAL:
            case MaterialConsumptionRuleMaterial::MODE_FIXED_PER_PALLET:
            default:
                return $quantityPerUnit;
        }
    }

    private function passesRuleCondition(?array $condition, array $pattern, int $totalRows): bool
    {
        if (!$condition || !isset($condition['type'])) {
            return true;
        }

        $type = $condition['type'];

        if ($type === 'rows_gte') {
            return $totalRows >= (int) ($condition['value'] ?? 0);
        }

        if ($type === 'rows_lte') {
            return $totalRows <= (int) ($condition['value'] ?? 0);
        }

        if ($type === 'rows_between') {
            $from = (int) ($condition['from'] ?? 0);
            $to = (int) ($condition['to'] ?? 0);
            return $totalRows >= $from && $totalRows <= $to;
        }

        if ($type === 'rows_in') {
            $values = collect($condition['values'] ?? [])
                ->map(function ($value) {
                    return (int) $value;
                })
                ->all();

            return in_array($totalRows, $values, true);
        }

        if ($type === 'flag_true') {
            $flag = (string) ($condition['flag'] ?? '');
            return !empty($pattern[$flag]);
        }

        return true;
    }

    private function resolveUnitCost(MaterialConsumptionRuleMaterial $ruleMaterial, array $proratedCostMap): float
    {
        if ($ruleMaterial->cost_source === MaterialConsumptionRuleMaterial::COST_SOURCE_MANUAL) {
            return (float) ($ruleMaterial->manual_unit_cost ?? 0);
        }

        if ($ruleMaterial->cost_source === MaterialConsumptionRuleMaterial::COST_SOURCE_LAST_COST) {
            $materialCost = (float) optional($ruleMaterial->material)->costo_ult_oc;
            if ($materialCost > 0) {
                return $materialCost;
            }
        }

        $key = $this->normalizeMaterialKey($ruleMaterial->material_key ?: $ruleMaterial->material_name);
        if (isset($proratedCostMap[$key]) && $proratedCostMap[$key] > 0) {
            return (float) $proratedCostMap[$key];
        }

        $materialCost = (float) optional($ruleMaterial->material)->costo_ult_oc;
        if ($materialCost > 0) {
            return $materialCost;
        }

        return 0;
    }

    private function normalizeMaterialKey(?string $raw): string
    {
        $value = Str::upper(Str::ascii((string) $raw));
        $value = preg_replace('/[^A-Z0-9]+/', '_', $value);
        $value = trim((string) $value, '_');

        if (Str::contains($value, 'ZUNCHO')) {
            return 'ZUNCHO';
        }

        if (Str::contains($value, 'SELLO')) {
            return 'SELLO_METALICO';
        }

        if (Str::contains($value, 'ESQUIN')) {
            return 'ESQUINERO';
        }

        if (Str::contains($value, 'PARRILLA') || Str::contains($value, 'LISTON')) {
            return 'PARRILLA_LISTON';
        }

        if (Str::contains($value, 'PALLET')) {
            return 'PALLET';
        }

        return $value !== '' ? $value : 'SIN_CLASIFICAR';
    }

    private function refreshTotals(MaterialConsumptionProcessCalculation $calculation): void
    {
        $items = $calculation->items()->get();
        $realTotal = $items
            ->whereIn('source', ['rule_engine', 'adjustment'])
            ->sum('total_cost');
        $proratedTotal = $items
            ->where('source', 'prorated_document')
            ->sum('total_cost');

        $calculation->update([
            'total_cost_real' => $realTotal,
            'total_cost_prorated' => $proratedTotal,
            'total_cost_gap' => $realTotal - $proratedTotal,
        ]);
    }

    private function bootstrapDefaultRulesIfNeeded(?int $userId = null): void
    {
        if (MaterialConsumptionRuleSet::query()->active()->exists()) {
            return;
        }

        DB::transaction(function () use ($userId) {
            $ruleSet = MaterialConsumptionRuleSet::create([
                'name' => 'Regla base costeo real pallets',
                'description' => 'Regla inicial para consumo real por filas con matriz 1..14.',
                'priority' => 10,
                'is_active' => true,
                'created_by' => $userId,
                'metadata' => [
                    'bootstrap' => true,
                    'version' => 1,
                ],
            ]);

            $rowMap = $this->defaultRowsWithConsumptionByTotalRows();
            foreach ($rowMap as $totalRows => $consumptionRows) {
                MaterialConsumptionRowPattern::create([
                    'rule_set_id' => $ruleSet->id,
                    'total_rows' => $totalRows,
                    'rows_with_consumption' => $consumptionRows,
                    'vertical_straps_count' => $totalRows >= 14 ? 4 : 0,
                    'includes_corner_posts' => $totalRows >= 3,
                    'includes_pallet' => true,
                    'includes_grill' => $totalRows >= 14,
                ]);
            }

            $this->createDefaultRuleMaterial($ruleSet, [
                'material_key' => 'PALLET',
                'material_name' => 'Pallet',
                'consumption_mode' => MaterialConsumptionRuleMaterial::MODE_FIXED_PER_PALLET,
                'quantity_per_unit' => 1,
                'cost_source' => MaterialConsumptionRuleMaterial::COST_SOURCE_ADM_DOC,
            ]);

            $this->createDefaultRuleMaterial($ruleSet, [
                'material_key' => 'ESQUINERO',
                'material_name' => 'Esquinero',
                'consumption_mode' => MaterialConsumptionRuleMaterial::MODE_CONDITIONAL,
                'quantity_per_unit' => 4,
                'min_rows' => 3,
                'cost_source' => MaterialConsumptionRuleMaterial::COST_SOURCE_ADM_DOC,
            ]);

            $this->createDefaultRuleMaterial($ruleSet, [
                'material_key' => 'PARRILLA_LISTON',
                'material_name' => 'Parrilla liston',
                'consumption_mode' => MaterialConsumptionRuleMaterial::MODE_CONDITIONAL,
                'quantity_per_unit' => 1,
                'min_rows' => 14,
                'cost_source' => MaterialConsumptionRuleMaterial::COST_SOURCE_ADM_DOC,
            ]);

            $this->createDefaultRuleMaterial($ruleSet, [
                'material_key' => 'ZUNCHO',
                'material_name' => 'Zuncho',
                'consumption_mode' => MaterialConsumptionRuleMaterial::MODE_PER_CONSUMPTION_ROW,
                'quantity_per_unit' => 1,
                'cost_source' => MaterialConsumptionRuleMaterial::COST_SOURCE_ADM_DOC,
            ]);

            $this->createDefaultRuleMaterial($ruleSet, [
                'material_key' => 'SELLO_METALICO',
                'material_name' => 'Sello metalico',
                'consumption_mode' => MaterialConsumptionRuleMaterial::MODE_PER_CONSUMPTION_ROW,
                'quantity_per_unit' => 1,
                'cost_source' => MaterialConsumptionRuleMaterial::COST_SOURCE_ADM_DOC,
            ]);

            $this->createDefaultRuleMaterial($ruleSet, [
                'material_key' => 'ZUNCHO',
                'material_name' => 'Zuncho vertical',
                'consumption_mode' => MaterialConsumptionRuleMaterial::MODE_CONDITIONAL,
                'quantity_per_unit' => 4,
                'min_rows' => 14,
                'cost_source' => MaterialConsumptionRuleMaterial::COST_SOURCE_ADM_DOC,
                'condition' => [
                    'type' => 'flag_true',
                    'flag' => 'includes_grill',
                ],
            ]);
        });
    }

    private function createDefaultRuleMaterial(MaterialConsumptionRuleSet $ruleSet, array $data): void
    {
        $material = Material::query()
            ->where('nombre', 'like', '%' . $data['material_name'] . '%')
            ->first();

        MaterialConsumptionRuleMaterial::create([
            'rule_set_id' => $ruleSet->id,
            'material_id' => optional($material)->id,
            'material_key' => $data['material_key'],
            'material_name' => $data['material_name'],
            'consumption_mode' => $data['consumption_mode'],
            'quantity_per_unit' => $data['quantity_per_unit'],
            'min_rows' => $data['min_rows'] ?? null,
            'max_rows' => $data['max_rows'] ?? null,
            'condition' => $data['condition'] ?? null,
            'cost_source' => $data['cost_source'] ?? MaterialConsumptionRuleMaterial::COST_SOURCE_ADM_DOC,
            'manual_unit_cost' => $data['manual_unit_cost'] ?? null,
            'is_active' => true,
        ]);
    }

    private function defaultRowsWithConsumptionByTotalRows(): array
    {
        return [
            1 => [],
            2 => [],
            3 => [2],
            4 => [1, 3],
            5 => [2, 4],
            6 => [1, 2, 3, 5],
            7 => [1, 2, 3, 5],
            8 => [1, 2, 3, 6],
            9 => [1, 2, 3, 6, 8],
            10 => [1, 2, 3, 6, 9],
            11 => [1, 2, 3, 6, 10],
            12 => [1, 2, 3, 6, 9, 11],
            13 => [1, 2, 3, 6, 9, 12],
            14 => [1, 2, 3, 6, 9, 13],
        ];
    }
}
