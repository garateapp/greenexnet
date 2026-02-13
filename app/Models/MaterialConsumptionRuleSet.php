<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialConsumptionRuleSet extends Model
{
    use SoftDeletes;

    public $table = 'material_consumption_rule_sets';

    protected $fillable = [
        'name',
        'description',
        'priority',
        'is_active',
        'effective_from',
        'effective_to',
        'specie_name',
        'packaging_code',
        'exportadora_id',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function rowPatterns()
    {
        return $this->hasMany(MaterialConsumptionRowPattern::class, 'rule_set_id');
    }

    public function materials()
    {
        return $this->hasMany(MaterialConsumptionRuleMaterial::class, 'rule_set_id');
    }

    public function calculations()
    {
        return $this->hasMany(MaterialConsumptionProcessCalculation::class, 'rule_set_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
