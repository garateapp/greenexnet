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
                'id'    => 822,
                'title' => 'otroscargo_create',
            ],
            [
                'id'    => 823,
                'title' => 'otroscargo_edit',
            ],
            [
                'id'    => 824,
                'title' => 'otroscargo_show',
            ],
            [
                'id'    => 825,
                'title' => 'otroscargo_delete',
            ],
            [
                'id'    => 826,
                'title' => 'otroscargo_access',
            ],


        ];

        Permission::insert($permissions);
    }
}
