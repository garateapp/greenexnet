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
                'id'    => 806,
                'title' => 'multiresiduo_create',
            ],
            [
                'id'    => 807,
                'title' => 'multiresiduo_edit',
            ],
            [
                'id'    => 808,
                'title' => 'multiresiduo_show',
            ],
            [
                'id'    => 809,
                'title' => 'multiresiduo_delete',
            ],
            [
                'id'    => 810,
                'title' => 'multiresiduo_access',
            ],
            [
                'id'    => 811,
                'title' => 'bonificacion_create',
            ],
            [
                'id'    => 812,
                'title' => 'bonificacion_edit',
            ],
            [
                'id'    => 813,
                'title' => 'bonificacion_show',
            ],
            [
                'id'    => 814,
                'title' => 'bonificacion_delete',
            ],
            [
                'id'    => 815,
                'title' => 'bonificacion_access',
            ],
            [
                'id'    => 816,
                'title' => 'otro_cobro_create',
            ],
            [
                'id'    => 817,
                'title' => 'otro_cobro_edit',
            ],
            [
                'id'    => 818,
                'title' => 'otro_cobro_show',
            ],
            [
                'id'    => 819,
                'title' => 'otro_cobro_delete',
            ],
            [
                'id'    => 820,
                'title' => 'otro_cobro_access',
            ],
            [
                'id'    => 821,
                'title' => 'profile_password_edit',
            ],


        ];

        Permission::insert($permissions);
    }
}
