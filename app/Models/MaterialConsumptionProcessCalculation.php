<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialConsumptionProcessCalculation extends Model
{
    public $table = 'material_consumption_process_calculations';

    protected $fillable = [
        'process_number',
        'rule_set_id',
        'calculated_at',
        'status',
        'error_message',
        'total_cost_real',
        'total_cost_prorated',
        'total_cost_gap',
        'source_payload',
        'created_by',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
        'source_payload' => 'array',
        'total_cost_real' => 'float',
        'total_cost_prorated' => 'float',
        'total_cost_gap' => 'float',
    ];

    public function ruleSet()
    {
        return $this->belongsTo(MaterialConsumptionRuleSet::class, 'rule_set_id');
    }

    public function items()
    {
        return $this->hasMany(MaterialConsumptionProcessItem::class, 'calculation_id');
    }

    public function adjustments()
    {
        return $this->hasMany(MaterialConsumptionAdjustment::class, 'calculation_id');
    }
}
