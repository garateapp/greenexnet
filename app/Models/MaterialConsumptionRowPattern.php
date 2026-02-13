<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialConsumptionRowPattern extends Model
{
    public $table = 'material_consumption_row_patterns';

    protected $fillable = [
        'rule_set_id',
        'total_rows',
        'rows_with_consumption',
        'vertical_straps_count',
        'includes_corner_posts',
        'includes_pallet',
        'includes_grill',
        'metadata',
    ];

    protected $casts = [
        'rows_with_consumption' => 'array',
        'includes_corner_posts' => 'boolean',
        'includes_pallet' => 'boolean',
        'includes_grill' => 'boolean',
        'metadata' => 'array',
    ];

    public function ruleSet()
    {
        return $this->belongsTo(MaterialConsumptionRuleSet::class, 'rule_set_id');
    }
}
