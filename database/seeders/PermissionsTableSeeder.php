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
                'id'    => 750,
                'title' => 'confeccion_liquidacion_access',
            ],
            [
                'id'    => 751,
                'title' => 'grupo_create',
            ],
            [
                'id'    => 752,
                'title' => 'grupo_edit',
            ],
            [
                'id'    => 753,
                'title' => 'grupo_show',
            ],
            [
                'id'    => 754,
                'title' => 'grupo_delete',
            ],
            [
                'id'    => 755,
                'title' => 'grupo_access',
            ],
            [
                'id'    => 756,
                'title' => 'productor_create',
            ],
            [
                'id'    => 757,
                'title' => 'productor_edit',
            ],
            [
                'id'    => 758,
                'title' => 'productor_show',
            ],
            [
                'id'    => 759,
                'title' => 'productor_delete',
            ],
            [
                'id'    => 760,
                'title' => 'productor_access',
            ],
            [
                'id'    => 761,
                'title' => 'conjunto_create',
            ],
            [
                'id'    => 762,
                'title' => 'conjunto_edit',
            ],
            [
                'id'    => 763,
                'title' => 'conjunto_show',
            ],
            [
                'id'    => 764,
                'title' => 'conjunto_delete',
            ],
            [
                'id'    => 765,
                'title' => 'conjunto_access',
            ],
            [
                'id'    => 766,
                'title' => 'valor_flete_create',
            ],
            [
                'id'    => 767,
                'title' => 'valor_flete_edit',
            ],
            [
                'id'    => 768,
                'title' => 'valor_flete_show',
            ],
            [
                'id'    => 769,
                'title' => 'valor_flete_delete',
            ],
            [
                'id'    => 770,
                'title' => 'valor_flete_access',
            ],
            [
                'id'    => 771,
                'title' => 'valor_dolar_create',
            ],
            [
                'id'    => 772,
                'title' => 'valor_dolar_edit',
            ],
            [
                'id'    => 773,
                'title' => 'valor_dolar_show',
            ],
            [
                'id'    => 774,
                'title' => 'valor_dolar_delete',
            ],
            [
                'id'    => 775,
                'title' => 'valor_dolar_access',
            ],
            [
                'id'    => 776,
                'title' => 'valor_envase_create',
            ],
            [
                'id'    => 777,
                'title' => 'valor_envase_edit',
            ],
            [
                'id'    => 778,
                'title' => 'valor_envase_show',
            ],
            [
                'id'    => 779,
                'title' => 'valor_envase_delete',
            ],
            [
                'id'    => 780,
                'title' => 'valor_envase_access',
            ],
            [
                'id'    => 781,
                'title' => 'anticipo_create',
            ],
            [
                'id'    => 782,
                'title' => 'anticipo_edit',
            ],
            [
                'id'    => 783,
                'title' => 'anticipo_show',
            ],
            [
                'id'    => 784,
                'title' => 'anticipo_delete',
            ],
            [
                'id'    => 785,
                'title' => 'anticipo_access',
            ],
            [
                'id'    => 786,
                'title' => 'interes_anticipo_create',
            ],
            [
                'id'    => 787,
                'title' => 'interes_anticipo_edit',
            ],
            [
                'id'    => 788,
                'title' => 'interes_anticipo_show',
            ],
            [
                'id'    => 789,
                'title' => 'interes_anticipo_delete',
            ],
            [
                'id'    => 790,
                'title' => 'interes_anticipo_access',
            ],
            [
                'id'    => 791,
                'title' => 'recepcion_create',
            ],
            [
                'id'    => 792,
                'title' => 'recepcion_edit',
            ],
            [
                'id'    => 793,
                'title' => 'recepcion_show',
            ],
            [
                'id'    => 794,
                'title' => 'recepcion_delete',
            ],
            [
                'id'    => 795,
                'title' => 'recepcion_access',
            ],
            [
                'id'    => 796,
                'title' => 'proceso_create',
            ],
            [
                'id'    => 797,
                'title' => 'proceso_edit',
            ],
            [
                'id'    => 798,
                'title' => 'proceso_show',
            ],
            [
                'id'    => 799,
                'title' => 'proceso_delete',
            ],
            [
                'id'    => 800,
                'title' => 'proceso_access',
            ],
            [
                'id'    => 801,
                'title' => 'analisi_create',
            ],
            [
                'id'    => 802,
                'title' => 'analisi_edit',
            ],
            [
                'id'    => 803,
                'title' => 'analisi_show',
            ],
            [
                'id'    => 804,
                'title' => 'analisi_delete',
            ],
            [
                'id'    => 805,
                'title' => 'analisi_access',
            ],


        ];

        Permission::insert($permissions);
    }
}
