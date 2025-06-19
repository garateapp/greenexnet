<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Balancemasas extends Model
{
    use HasFactory;

    protected $table = 'balancemasas';
    protected $connection = 'greenexnet'; // Assuming 'greenexnet' is the database connection
    //public $incrementing = true; // Since 'id' is bigint UNSIGNED NOT NULL
    protected $primaryKey = 'id';
    protected $keyType = 'bigint';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        //'temporada_id',
        'id_empresa',
        'c_etiqueta',
        'id_variedad',
        'c_calibre',
        'c_categoria',
        'tipo_g_despacho',
        'numero_g_despacho',
        'numero_guia_produccion',
        'fecha_g_despacho',
        'semana',
        'folio',
        'r_productor',
        'c_productor',
        'n_productor',
        'n_especie',
        'n_variedad',
        'c_embalaje',
        'n_embalaje',
        'n_categoria',
        't_categoria',
        'n_categoria_st',
        'n_calibre',
        'n_etiqueta',
        'cantidad',
        'peso_neto',
        'tipo_transporte',
        'precio_unitario',
        'exportadora',
        'exportadora_embarque',
        'etd',
        'eta',
        'etd_semana',
        'eta_semana',
        'control_fechas',
        'factor',
        'peso_neto2',
        'fecha_sync',
        'factor_sync',
        'fecha_produccion',
        'peso_std_embalaje',
        //'fob_id',
        'color',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'temporada_id' => 'integer',
        // 'fob_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Relationship with Temporada (assuming a related table exists)
     */
    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'temporada_id');
    }

    /**
     * Relationship with Fob (assuming a related table exists)
     */
    public function fob()
    {
        return $this->belongsTo(Fob::class, 'fob_id');
    }
}