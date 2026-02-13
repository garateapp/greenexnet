<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialConsumptionProcessCalculation;
use App\Models\MaterialConsumptionRowPattern;
use App\Models\MaterialConsumptionRuleMaterial;
use App\Models\MaterialConsumptionRuleSet;
use App\Services\MaterialConsumptionCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MaterialConsumptionController extends Controller
{
    public function index()
    {
        $ruleSets = MaterialConsumptionRuleSet::query()
            ->withCount(['rowPatterns', 'materials'])
            ->orderBy('priority')
            ->orderByDesc('id')
            ->get();

        $materialsCatalog = Material::query()
            ->orderBy('nombre')
            ->get(['id', 'codigo', 'nombre', 'costo_ult_oc']);

        return view('admin.materialConsumption.index', compact('ruleSets', 'materialsCatalog'));
    }

    public function calculate(Request $request, MaterialConsumptionCalculatorService $service)
    {
        $validated = $request->validate([
            'process_number' => ['required', 'string', 'max:50'],
            'force_recalculate' => ['nullable', 'boolean'],
        ]);

        $calculation = $service->calculate(
            $validated['process_number'],
            auth()->id(),
            (bool) ($validated['force_recalculate'] ?? false)
        );

        return response()->json($this->formatCalculation($calculation));
    }

    public function show(MaterialConsumptionProcessCalculation $calculation)
    {
        return response()->json($this->formatCalculation($calculation));
    }

    public function addAdjustment(
        Request $request,
        MaterialConsumptionProcessCalculation $calculation,
        MaterialConsumptionCalculatorService $service
    ) {
        $validated = $request->validate([
            'material_key' => ['nullable', 'string', 'max:120'],
            'material_name' => ['required', 'string', 'max:120'],
            'material_id' => ['nullable', 'integer'],
            'pallet_code' => ['nullable', 'string', 'max:120'],
            'quantity_delta' => ['required', 'numeric'],
            'unit_cost' => ['required', 'numeric'],
            'cost_delta' => ['nullable', 'numeric'],
            'reason' => ['nullable', 'string', 'max:2000'],
        ]);

        $updated = $service->addAdjustment($calculation, $validated, auth()->id());

        return response()->json($this->formatCalculation($updated));
    }

    public function rules()
    {
        $rules = MaterialConsumptionRuleSet::query()
            ->with(['rowPatterns' => function ($query) {
                $query->orderBy('total_rows');
            }, 'materials' => function ($query) {
                $query->with('material')->orderBy('material_key');
            }])
            ->orderBy('priority')
            ->orderBy('id')
            ->get();

        return response()->json($rules);
    }

    public function storeRuleSet(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'effective_from' => ['nullable', 'date'],
            'effective_to' => ['nullable', 'date'],
            'specie_name' => ['nullable', 'string', 'max:120'],
            'packaging_code' => ['nullable', 'string', 'max:120'],
            'exportadora_id' => ['nullable', 'integer'],
            'metadata' => ['nullable', 'array'],
        ]);

        $ruleSet = MaterialConsumptionRuleSet::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'] ?? 100,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'effective_from' => $validated['effective_from'] ?? null,
            'effective_to' => $validated['effective_to'] ?? null,
            'specie_name' => $validated['specie_name'] ?? null,
            'packaging_code' => $validated['packaging_code'] ?? null,
            'exportadora_id' => $validated['exportadora_id'] ?? null,
            'metadata' => $validated['metadata'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return response()->json($ruleSet->load(['rowPatterns', 'materials']), 201);
    }

    public function updateRuleSet(Request $request, MaterialConsumptionRuleSet $ruleSet)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'effective_from' => ['nullable', 'date'],
            'effective_to' => ['nullable', 'date'],
            'specie_name' => ['nullable', 'string', 'max:120'],
            'packaging_code' => ['nullable', 'string', 'max:120'],
            'exportadora_id' => ['nullable', 'integer'],
            'metadata' => ['nullable', 'array'],
        ]);

        $ruleSet->update($validated);

        return response()->json($ruleSet->fresh(['rowPatterns', 'materials']));
    }

    public function upsertRowPattern(Request $request, MaterialConsumptionRuleSet $ruleSet)
    {
        $validated = $request->validate([
            'total_rows' => ['required', 'integer', 'min:1', 'max:40'],
            'rows_with_consumption' => ['nullable'],
            'vertical_straps_count' => ['nullable', 'integer', 'min:0', 'max:20'],
            'includes_corner_posts' => ['nullable', 'boolean'],
            'includes_pallet' => ['nullable', 'boolean'],
            'includes_grill' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ]);

        $rowsWithConsumption = $this->normalizeRowsWithConsumption($validated['rows_with_consumption'] ?? []);

        $pattern = $ruleSet->rowPatterns()->updateOrCreate(
            ['total_rows' => $validated['total_rows']],
            [
                'rows_with_consumption' => $rowsWithConsumption,
                'vertical_straps_count' => $validated['vertical_straps_count'] ?? 0,
                'includes_corner_posts' => (bool) ($validated['includes_corner_posts'] ?? false),
                'includes_pallet' => (bool) ($validated['includes_pallet'] ?? true),
                'includes_grill' => (bool) ($validated['includes_grill'] ?? false),
                'metadata' => $validated['metadata'] ?? null,
            ]
        );

        return response()->json($pattern);
    }

    public function storeRuleMaterial(Request $request, MaterialConsumptionRuleSet $ruleSet)
    {
        $validated = $request->validate([
            'material_id' => ['nullable', 'integer'],
            'material_key' => ['required', 'string', 'max:120'],
            'material_name' => ['nullable', 'string', 'max:120'],
            'consumption_mode' => ['required', 'in:fixed_per_pallet,per_consumption_row,per_vertical_strap,conditional'],
            'quantity_per_unit' => ['required', 'numeric', 'min:0'],
            'min_rows' => ['nullable', 'integer', 'min:1', 'max:40'],
            'max_rows' => ['nullable', 'integer', 'min:1', 'max:40'],
            'condition' => ['nullable', 'array'],
            'cost_source' => ['required', 'in:adm_doc_unit_cost,material_last_cost,manual'],
            'manual_unit_cost' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ]);

        $materialName = $validated['material_name'] ?? null;
        if (!$materialName && !empty($validated['material_id'])) {
            $materialName = Material::query()->where('id', $validated['material_id'])->value('nombre');
        }
        if (!$materialName) {
            $materialName = $validated['material_key'];
        }
        $validated['material_name'] = $materialName;

        $material = $ruleSet->materials()->create($validated);

        return response()->json($material, 201);
    }

    public function updateRuleMaterial(Request $request, MaterialConsumptionRuleMaterial $ruleMaterial)
    {
        $validated = $request->validate([
            'material_id' => ['nullable', 'integer'],
            'material_key' => ['sometimes', 'required', 'string', 'max:120'],
            'material_name' => ['nullable', 'string', 'max:120'],
            'consumption_mode' => ['sometimes', 'required', 'in:fixed_per_pallet,per_consumption_row,per_vertical_strap,conditional'],
            'quantity_per_unit' => ['nullable', 'numeric', 'min:0'],
            'min_rows' => ['nullable', 'integer', 'min:1', 'max:40'],
            'max_rows' => ['nullable', 'integer', 'min:1', 'max:40'],
            'condition' => ['nullable', 'array'],
            'cost_source' => ['sometimes', 'required', 'in:adm_doc_unit_cost,material_last_cost,manual'],
            'manual_unit_cost' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ]);

        if (array_key_exists('material_name', $validated) && !$validated['material_name']) {
            $materialName = null;
            if (!empty($validated['material_id'])) {
                $materialName = Material::query()->where('id', $validated['material_id'])->value('nombre');
            }
            if (!$materialName) {
                $materialName = $validated['material_key'] ?? $ruleMaterial->material_key;
            }
            $validated['material_name'] = $materialName;
        }

        $ruleMaterial->update($validated);

        return response()->json($ruleMaterial->fresh());
    }

    public function destroyRuleSet(MaterialConsumptionRuleSet $ruleSet)
    {
        $ruleSet->rowPatterns()->delete();
        $ruleSet->materials()->delete();
        $ruleSet->delete();

        return response()->json(['ok' => true]);
    }

    public function destroyRowPattern(MaterialConsumptionRowPattern $rowPattern)
    {
        $rowPattern->delete();

        return response()->json(['ok' => true]);
    }

    public function destroyRuleMaterial(MaterialConsumptionRuleMaterial $ruleMaterial)
    {
        $ruleMaterial->delete();

        return response()->json(['ok' => true]);
    }

    private function normalizeRowsWithConsumption($raw): array
    {
        if (is_string($raw)) {
            $raw = array_filter(array_map('trim', explode(',', $raw)));
        }

        return collect((array) $raw)
            ->map(function ($value) {
                return (int) $value;
            })
            ->filter(function ($value) {
                return $value > 0;
            })
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    private function formatCalculation(MaterialConsumptionProcessCalculation $calculation): array
    {
        $calculation->loadMissing(['ruleSet', 'items', 'adjustments']);

        $items = $calculation->items;
        $realItems = $items->where('source', 'rule_engine')->values();
        $proratedItems = $items->where('source', 'prorated_document')->values();
        $adjustmentItems = $items->where('source', 'adjustment')->values();

        return [
            'id' => $calculation->id,
            'process_number' => $calculation->process_number,
            'status' => $calculation->status,
            'error_message' => $calculation->error_message,
            'calculated_at' => optional($calculation->calculated_at)->toDateTimeString(),
            'rule_set' => $calculation->ruleSet ? [
                'id' => $calculation->ruleSet->id,
                'name' => $calculation->ruleSet->name,
                'priority' => $calculation->ruleSet->priority,
            ] : null,
            'totals' => [
                'real' => (float) $calculation->total_cost_real,
                'prorated' => (float) $calculation->total_cost_prorated,
                'gap' => (float) $calculation->total_cost_gap,
            ],
            'source_payload' => $calculation->source_payload ?? [],
            'real_items' => $realItems,
            'real_by_material' => $this->summarizeByMaterial($realItems),
            'real_by_pallet' => $this->summarizeByPallet($realItems),
            'prorated_items' => $proratedItems,
            'prorated_by_material' => $this->summarizeByMaterial($proratedItems),
            'adjustment_items' => $adjustmentItems,
            'adjustment_by_material' => $this->summarizeByMaterial($adjustmentItems),
        ];
    }

    private function summarizeByMaterial(Collection $items): array
    {
        return $items
            ->groupBy('material_key')
            ->map(function (Collection $group, $materialKey) {
                $quantity = $group->sum('quantity');
                $cost = $group->sum('total_cost');

                return [
                    'material_key' => $materialKey,
                    'material_name' => $group->first()->material_name,
                    'quantity' => (float) $quantity,
                    'total_cost' => (float) $cost,
                    'avg_unit_cost' => $quantity > 0 ? (float) ($cost / $quantity) : 0.0,
                ];
            })
            ->values()
            ->all();
    }

    private function summarizeByPallet(Collection $items): array
    {
        return $items
            ->groupBy(function ($row) {
                return $row->pallet_code ?: 'SIN_PALLET';
            })
            ->map(function (Collection $group, $palletCode) {
                return [
                    'pallet_code' => $palletCode,
                    'total_rows' => $group->first()->total_rows,
                    'total_cost' => (float) $group->sum('total_cost'),
                    'materials' => $this->summarizeByMaterial($group),
                ];
            })
            ->values()
            ->all();
    }
}
