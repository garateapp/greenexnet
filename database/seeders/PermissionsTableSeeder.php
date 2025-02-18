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
                'id'    => 600,
                'title' => 'proveedor_create',
            ],
            [
                'id'    => 601,
                'title' => 'proveedor_edit',
            ],
            [
                'id'    => 602,
                'title' => 'proveedor_show',
            ],
            [
                'id'    => 603,
                'title' => 'proveedor_delete',
            ],
            [
                'id'    => 604,
                'title' => 'proveedor_access',
            ],


        ];

        Permission::insert($permissions);
    }
}
