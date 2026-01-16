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
                'id'    => 1005,
                'title' => 'solicitud_compra_create',
            ],
            [
                'id'    => 1006,
                'title' => 'solicitud_compra_edit',
            ],
            [
                'id'    => 1007,
                'title' => 'solicitud_compra_show',
            ],
            [
                'id'    => 1008,
                'title' => 'solicitud_compra_delete',
            ],
            [
                'id'    => 1009,
                'title' => 'solicitud_compra_access',
            ],
            [
                'id'    => 1010,
                'title' => 'politica_cotizacion_create',
            ],
            [
                'id'    => 1011,
                'title' => 'politica_cotizacion_edit',
            ],
            [
                'id'    => 1012,
                'title' => 'politica_cotizacion_show',
            ],
            [
                'id'    => 1013,
                'title' => 'politica_cotizacion_delete',
            ],
            [
                'id'    => 1014,
                'title' => 'politica_cotizacion_access',
            ],


        ];

        Permission::insert($permissions);
    }
}
