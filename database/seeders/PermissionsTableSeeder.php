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
                'id'    => 570,
                'title' => 'liq_cx_cabecera_create',
            ],
            [
                'id'    => 571,
                'title' => 'liq_cx_cabecera_edit',
            ],
            [
                'id'    => 572,
                'title' => 'liq_cx_cabecera_show',
            ],
            [
                'id'    => 573,
                'title' => 'liq_cx_cabecera_delete',
            ],
            [
                'id'    => 574,
                'title' => 'liq_cx_cabecera_access',
            ],
            [
                'id'    => 575,
                'title' => 'liquidaciones_cx_create',
            ],
            [
                'id'    => 576,
                'title' => 'liquidaciones_cx_edit',
            ],
            [
                'id'    => 577,
                'title' => 'liquidaciones_cx_show',
            ],
            [
                'id'    => 578,
                'title' => 'liquidaciones_cx_delete',
            ],
            [
                'id'    => 579,
                'title' => 'liquidaciones_cx_access',
            ],
            [
                'id'    => 580,
                'title' => 'liq_costo_create',
            ],
            [
                'id'    => 581,
                'title' => 'liq_costo_edit',
            ],
            [
                'id'    => 582,
                'title' => 'liq_costo_show',
            ],
            [
                'id'    => 583,
                'title' => 'liq_costo_delete',
            ],
            [
                'id'    => 584,
                'title' => 'liq_costo_access',
            ],


        ];

        Permission::insert($permissions);
    }
}
