<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Embarque extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'embarques';

    public const TEMPORADA_SELECT = [
        '2023-2024' => '2023-2024',
        '2024-2025' => '2024-2025',
        '2025-2026' => '2025-2026',
        '2026-2027' => '2026-2027',
    ];
    public const TRANSPORTE_SELECT = [
        'MARITIMO' => 'MARITIMO',
        'AEREO' => 'AEREO',
        'CAMION FRIGORIFICO' => 'CAMION FRIGORIFICO',
    ];
    protected $dates = [
        'etd_estimado',
        'fecha_zarpe_real',
        'fecha_arribo_real',
        'con_fecha_hora',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const ESTADO_SELECT = [
        'ESPERA DE ZARPE' => 'ESPERA DE ZARPE',
        'EN TRANSITO'     => 'EN TRANSITO',
        'ARRIBO DESTINO'  => 'ARRIBO DESTINO',
        'RETRASO'         => 'RETRASO',
    ];

    public const STATUS_AEREO_SELECT = [
        'ARRIBADO'                 => 'ARRIBADO',
        'ESPERA CONEXION'          => 'ESPERA CONEXIÓN',
        'EN AEROPUERTO ESPERA ETD' => 'EN AEROPUERTO ESPERA ETD',
        'RETRASO'                  => 'RETRASO',
        'EN TRANSITO'              => 'EN TRANSITO',
    ];

    protected $fillable = [
        'temporada',
        'num_embarque',
        'id_cliente',
        'n_cliente',
        'semana',
        'planta_carga',
        'n_naviera',
        'nave',
        'num_contenedor',
        'especie',
        'variedad',
        'embalajes',
        'etiqueta',
        'cajas',
        'peso_neto',
        'puerto_embarque',
        'pais_destino',
        'puerto_destino',
        'mercado',
        'etd_estimado',
        'eta_estimado',
        'fecha_zarpe_real',
        'fecha_arribo_real',
        'dias_transito_real',
        'estado',
        'descargado',
        'retirado_full',
        'devuelto_vacio',
        'notas',
        'calificacion',
        'conexiones',
        'con_fecha_hora',
        'status_aereo',
        'num_pallets',
        'embalaje_std',
        'num_orden',
        'tipo_especie',
        'numero_reserva_agente_naviero',
        'cant_pallets',
        'transporte',
        'pais_conexion',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }

    // public function getEtdEstimadoAttribute($value)
    // {
    //     return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    // }

    // public function setEtdEstimadoAttribute($value)
    // {
    //     $this->attributes['etd_estimado'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    // }

    // public function getFechaZarpeRealAttribute($value)
    // {
    //     return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    // }

    // public function setFechaZarpeRealAttribute($value)
    // {
    //     $this->attributes['fecha_zarpe_real'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    // }

    // public function getFechaArriboRealAttribute($value)
    // {
    //     return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    // }

    // public function setFechaArriboRealAttribute($value)
    // {
    //     $this->attributes['fecha_arribo_real'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    // }

    // public function getConFechaHoraAttribute($value)
    // {
    //     return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    // }

    // public function setConFechaHoraAttribute($value)
    // {
    //     $this->attributes['con_fecha_hora'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    // }
}
