<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialConsumptionRuleMaterial extends Model
{
    use SoftDeletes;

    public const MODE_FIXED_PER_PALLET = 'fixed_per_pallet';
    public const MODE_PER_CONSUMPTION_ROW = 'per_consumption_row';
    public const MODE_PER_VERTICAL_STRAP = 'per_vertical_strap';
    public const MODE_CONDITIONAL = 'conditional';

    public const COST_SOURCE_ADM_DOC = 'adm_doc_unit_cost';
    public const COST_SOURCE_LAST_COST = 'material_last_cost';
    public const COST_SOURCE_MANUAL = 'manual';

    public $table = 'material_consumption_rule_materials';

    protected $fillable = [
        'rule_set_id',
        'material_id',
        'material_key',
        'material_name',
        'consumption_mode',
        'quantity_per_unit',
        'min_rows',
        'max_rows',
        'condition',
        'cost_source',
        'manual_unit_cost',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'condition' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'quantity_per_unit' => 'float',
        'manual_unit_cost' => 'float',
    ];

    public function ruleSet()
    {
        return $this->belongsTo(MaterialConsumptionRuleSet::class, 'rule_set_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
