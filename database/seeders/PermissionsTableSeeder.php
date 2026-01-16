<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Seed the permissions table with a predefined list of permissions.
     * Inserts an array of permissions, each with an id and title, into the database.
     */

    public function run()
    {
        $permissions = [



            [
                'id'    => 1015,
                'title' => 'centro_costo_create',
            ],
            [
                'id'    => 1016,
                'title' => 'centro_costo_edit',
            ],
            [
                'id'    => 1017,
                'title' => 'centro_costo_show',
            ],
            [
                'id'    => 1018,
                'title' => 'centro_costo_delete',
            ],
            [
                'id'    => 1019,
                'title' => 'centro_costo_access',
            ],


        ];

        Permission::insert($permissions);
    }
}
