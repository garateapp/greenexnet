<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [



            [
                'id'    => 381,
                'title' => 'entidad_create',
            ],
            [
                'id'    => 382,
                'title' => 'entidad_edit',
            ],
            [
                'id'    => 383,
                'title' => 'entidad_show',
            ],
            [
                'id'    => 384,
                'title' => 'entidad_delete',
            ],
            [
                'id'    => 385,
                'title' => 'entidad_access',
            ],
            [
                'id'    => 386,
                'title' => 'area_create',
            ],
            [
                'id'    => 387,
                'title' => 'area_edit',
            ],
            [
                'id'    => 388,
                'title' => 'area_show',
            ],
            [
                'id'    => 389,
                'title' => 'area_delete',
            ],
            [
                'id'    => 390,
                'title' => 'area_access',
            ],
            [
                'id'    => 391,
                'title' => 'greenex_net_access',
            ],
            [
                'id'    => 392,
                'title' => 'locacion_create',
            ],
            [
                'id'    => 393,
                'title' => 'locacion_edit',
            ],
            [
                'id'    => 394,
                'title' => 'locacion_show',
            ],
            [
                'id'    => 395,
                'title' => 'locacion_delete',
            ],
            [
                'id'    => 396,
                'title' => 'locacion_access',
            ],
            [
                'id'    => 397,
                'title' => 'turno_create',
            ],
            [
                'id'    => 398,
                'title' => 'turno_edit',
            ],
            [
                'id'    => 399,
                'title' => 'turno_show',
            ],
            [
                'id'    => 400,
                'title' => 'turno_delete',
            ],
            [
                'id'    => 401,
                'title' => 'turno_access',
            ],
            [
                'id'    => 402,
                'title' => 'frecuencia_turno_create',
            ],
            [
                'id'    => 403,
                'title' => 'frecuencia_turno_edit',
            ],
            [
                'id'    => 404,
                'title' => 'frecuencia_turno_show',
            ],
            [
                'id'    => 405,
                'title' => 'frecuencia_turno_delete',
            ],
            [
                'id'    => 406,
                'title' => 'frecuencia_turno_access',
            ],
            [
                'id'    => 407,
                'title' => 'cargo_create',
            ],
            [
                'id'    => 408,
                'title' => 'cargo_edit',
            ],
            [
                'id'    => 409,
                'title' => 'cargo_show',
            ],
            [
                'id'    => 410,
                'title' => 'cargo_delete',
            ],
            [
                'id'    => 411,
                'title' => 'cargo_access',
            ],
            [
                'id'    => 412,
                'title' => 'personal_create',
            ],
            [
                'id'    => 413,
                'title' => 'personal_edit',
            ],
            [
                'id'    => 414,
                'title' => 'personal_show',
            ],
            [
                'id'    => 415,
                'title' => 'personal_delete',
            ],
            [
                'id'    => 416,
                'title' => 'personal_access',
            ],
            [
                'id'    => 417,
                'title' => 'turnos_frecuencium_create',
            ],
            [
                'id'    => 418,
                'title' => 'turnos_frecuencium_edit',
            ],
            [
                'id'    => 419,
                'title' => 'turnos_frecuencium_show',
            ],
            [
                'id'    => 420,
                'title' => 'turnos_frecuencium_delete',
            ],
            [
                'id'    => 421,
                'title' => 'turnos_frecuencium_access',
            ],

        ];

        Permission::insert($permissions);
    }
}
