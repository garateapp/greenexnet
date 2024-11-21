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
                'id'    => 428,
                'title' => 'recibe_master_create',
            ],
            [
                'id'    => 429,
                'title' => 'recibe_master_edit',
            ],
            [
                'id'    => 430,
                'title' => 'recibe_master_show',
            ],
            [
                'id'    => 431,
                'title' => 'recibe_master_delete',
            ],
            [
                'id'    => 432,
                'title' => 'recibe_master_access',
            ],


        ];

        Permission::insert($permissions);
    }
}
