<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialConsumptionProcessItem extends Model
{
    public $table = 'material_consumption_process_items';

    protected $fillable = [
        'calculation_id',
        'source',
        'pallet_code',
        'total_rows',
        'material_id',
        'material_key',
        'material_name',
        'document_item',
        'quantity',
        'unit_cost',
        'total_cost',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
        'quantity' => 'float',
        'unit_cost' => 'float',
        'total_cost' => 'float',
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
