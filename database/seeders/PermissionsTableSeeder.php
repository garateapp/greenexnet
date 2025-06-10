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
                'id'    => 735,
                'title' => 'operacione_access',
            ],
            [
                'id'    => 736,
                'title' => 'material_create',
            ],
            [
                'id'    => 737,
                'title' => 'material_edit',
            ],
            [
                'id'    => 738,
                'title' => 'material_show',
            ],
            [
                'id'    => 739,
                'title' => 'material_delete',
            ],
            [
                'id'    => 740,
                'title' => 'material_access',
            ],
            [
                'id'    => 741,
                'title' => 'material_producto_create',
            ],
            [
                'id'    => 742,
                'title' => 'material_producto_edit',
            ],
            [
                'id'    => 743,
                'title' => 'material_producto_show',
            ],
            [
                'id'    => 744,
                'title' => 'material_producto_delete',
            ],
            [
                'id'    => 745,
                'title' => 'material_producto_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
