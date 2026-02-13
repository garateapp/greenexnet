<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialConsumptionAdjustment extends Model
{
    public $table = 'material_consumption_adjustments';

    protected $fillable = [
        'calculation_id',
        'pallet_code',
        'material_id',
        'material_key',
        'material_name',
        'quantity_delta',
        'unit_cost',
        'cost_delta',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'quantity_delta' => 'float',
        'unit_cost' => 'float',
        'cost_delta' => 'float',
    ];

    public function calculation()
    {
        return $this->belongsTo(MaterialConsumptionProcessCalculation::class, 'calculation_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
