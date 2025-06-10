<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['id' => 581, 'title' => 'base_recibidor_create'],
            ['id' => 582, 'title' => 'base_recibidor_edit'],
            ['id' => 583, 'title' => 'base_recibidor_show'],
            ['id' => 584, 'title' => 'base_recibidor_delete'],
            ['id' => 585, 'title' => 'base_recibidor_access'],
            ['id' => 586, 'title' => 'instructivo_access'],
            ['id' => 587, 'title' => 'base_contacto_create'],
            ['id' => 588, 'title' => 'base_contacto_edit'],
            ['id' => 589, 'title' => 'base_contacto_show'],
            ['id' => 590, 'title' => 'base_contacto_delete'],
            ['id' => 591, 'title' => 'base_contacto_access'],
            ['id' => 592, 'title' => 'agente_aduana_create'],
            ['id' => 593, 'title' => 'agente_aduana_edit'],
            ['id' => 594, 'title' => 'agente_aduana_show'],
            ['id' => 595, 'title' => 'agente_aduana_delete'],
            ['id' => 596, 'title' => 'agente_aduana_access'],
            ['id' => 597, 'title' => 'puerto_correo_create'],
            ['id' => 598, 'title' => 'puerto_correo_edit'],
            ['id' => 599, 'title' => 'puerto_correo_show'],
            ['id' => 600, 'title' => 'puerto_correo_delete'],
            ['id' => 601, 'title' => 'puerto_correo_access'],
            ['id' => 602, 'title' => 'embarcador_create'],
            ['id' => 603, 'title' => 'embarcador_edit'],
            ['id' => 604, 'title' => 'embarcador_show'],
            ['id' => 605, 'title' => 'embarcador_delete'],
            ['id' => 606, 'title' => 'embarcador_access'],
            ['id' => 607, 'title' => 'chofer_create'],
            ['id' => 608, 'title' => 'chofer_edit'],
            ['id' => 609, 'title' => 'chofer_show'],
            ['id' => 610, 'title' => 'chofer_delete'],
            ['id' => 611, 'title' => 'chofer_access'],
            ['id' => 612, 'title' => 'planta_carga_create'],
            ['id' => 613, 'title' => 'planta_carga_edit'],
            ['id' => 614, 'title' => 'planta_carga_show'],
            ['id' => 615, 'title' => 'planta_carga_delete'],
            ['id' => 616, 'title' => 'planta_carga_access'],
            ['id' => 617, 'title' => 'peso_embalaje_create'],
            ['id' => 618, 'title' => 'peso_embalaje_edit'],
            ['id' => 619, 'title' => 'peso_embalaje_show'],
            ['id' => 620, 'title' => 'peso_embalaje_delete'],
            ['id' => 621, 'title' => 'peso_embalaje_access'],
            ['id' => 622, 'title' => 'naviera_create'],
            ['id' => 623, 'title' => 'naviera_edit'],
            ['id' => 624, 'title' => 'naviera_show'],
            ['id' => 625, 'title' => 'naviera_delete'],
            ['id' => 626, 'title' => 'naviera_access'],
            ['id' => 627, 'title' => 'condpago_create'],
            ['id' => 628, 'title' => 'condpago_edit'],
            ['id' => 629, 'title' => 'condpago_show'],
            ['id' => 630, 'title' => 'condpago_delete'],
            ['id' => 631, 'title' => 'condpago_access'],
            ['id' => 632, 'title' => 'correoalso_air_create'],
            ['id' => 633, 'title' => 'correoalso_air_edit'],
            ['id' => 634, 'title' => 'correoalso_air_show'],
            ['id' => 635, 'title' => 'correoalso_air_delete'],
            ['id' => 636, 'title' => 'correoalso_air_access'],
            ['id' => 637, 'title' => 'instructivo_embarque_create'],
            ['id' => 638, 'title' => 'instructivo_embarque_edit'],
            ['id' => 639, 'title' => 'instructivo_embarque_show'],
            ['id' => 640, 'title' => 'instructivo_embarque_delete'],
            ['id' => 641, 'title' => 'instructivo_embarque_access'],
            ['id' => 642, 'title' => 'tipoflete_create'],
            ['id' => 643, 'title' => 'tipoflete_edit'],
            ['id' => 644, 'title' => 'tipoflete_show'],
            ['id' => 645, 'title' => 'tipoflete_delete'],
            ['id' => 646, 'title' => 'tipoflete_access'],
            ['id' => 647, 'title' => 'emision_bl_create'],
            ['id' => 648, 'title' => 'emision_bl_edit'],
            ['id' => 649, 'title' => 'emision_bl_show'],
            ['id' => 650, 'title' => 'emision_bl_delete'],
            ['id' => 651, 'title' => 'emision_bl_access'],
            ['id' => 652, 'title' => 'forma_pago_create'],
            ['id' => 653, 'title' => 'forma_pago_edit'],
            ['id' => 654, 'title' => 'forma_pago_show'],
            ['id' => 655, 'title' => 'forma_pago_delete'],
            ['id' => 656, 'title' => 'forma_pago_access'],
            ['id' => 657, 'title' => 'mod_ventum_create'],
            ['id' => 658, 'title' => 'mod_ventum_edit'],
            ['id' => 659, 'title' => 'mod_ventum_show'],
            ['id' => 660, 'title' => 'mod_ventum_delete'],
            ['id' => 661, 'title' => 'mod_ventum_access'],
            ['id' => 662, 'title' => 'clausula_ventum_create'],
            ['id' => 663, 'title' => 'clausula_ventum_edit'],
            ['id' => 664, 'title' => 'clausula_ventum_show'],
            ['id' => 665, 'title' => 'clausula_ventum_delete'],
            ['id' => 666, 'title' => 'clausula_ventum_access'],
            ['id' => 667, 'title' => 'moneda_create'],
            ['id' => 668, 'title' => 'moneda_edit'],
            ['id' => 669, 'title' => 'moneda_show'],
            ['id' => 670, 'title' => 'moneda_delete'],
            ['id' => 671, 'title' => 'moneda_access'],
            ['id' => 672, 'title' => 'profile_password_edit'],
            [
                'id'    => 673,
                'title' => 'confeccion_liquidacion_access',
            ],
            [
                'id'    => 674,
                'title' => 'grupo_create',
            ],
            [
                'id'    => 675,
                'title' => 'grupo_edit',
            ],
            [
                'id'    => 676,
                'title' => 'grupo_show',
            ],
            [
                'id'    => 677,
                'title' => 'grupo_delete',
            ],
            [
                'id'    => 678,
                'title' => 'grupo_access',
            ],
            [
                'id'    => 679,
                'title' => 'productor_create',
            ],
            [
                'id'    => 680,
                'title' => 'productor_edit',
            ],
            [
                'id'    => 681,
                'title' => 'productor_show',
            ],
            [
                'id'    => 682,
                'title' => 'productor_delete',
            ],
            [
                'id'    => 683,
                'title' => 'productor_access',
            ],
            [
                'id'    => 684,
                'title' => 'conjunto_create',
            ],
            [
                'id'    => 685,
                'title' => 'conjunto_edit',
            ],
            [
                'id'    => 686,
                'title' => 'conjunto_show',
            ],
            [
                'id'    => 687,
                'title' => 'conjunto_delete',
            ],
            [
                'id'    => 688,
                'title' => 'conjunto_access',
            ],
            [
                'id'    => 689,
                'title' => 'valor_flete_create',
            ],
            [
                'id'    => 690,
                'title' => 'valor_flete_edit',
            ],
            [
                'id'    => 691,
                'title' => 'valor_flete_show',
            ],
            [
                'id'    => 692,
                'title' => 'valor_flete_delete',
            ],
            [
                'id'    => 693,
                'title' => 'valor_flete_access',
            ],
            [
                'id'    => 694,
                'title' => 'valor_dolar_create',
            ],
            [
                'id'    => 695,
                'title' => 'valor_dolar_edit',
            ],
            [
                'id'    => 696,
                'title' => 'valor_dolar_show',
            ],
            [
                'id'    => 697,
                'title' => 'valor_dolar_delete',
            ],
            [
                'id'    => 698,
                'title' => 'valor_dolar_access',
            ],
            [
                'id'    => 699,
                'title' => 'valor_envase_create',
            ],
            [
                'id'    => 700,
                'title' => 'valor_envase_edit',
            ],
            [
                'id'    => 701,
                'title' => 'valor_envase_show',
            ],
            [
                'id'    => 702,
                'title' => 'valor_envase_delete',
            ],
            [
                'id'    => 703,
                'title' => 'valor_envase_access',
            ],
            [
                'id'    => 704,
                'title' => 'anticipo_create',
            ],
            [
                'id'    => 705,
                'title' => 'anticipo_edit',
            ],
            [
                'id'    => 706,
                'title' => 'anticipo_show',
            ],
            [
                'id'    => 707,
                'title' => 'anticipo_delete',
            ],
            [
                'id'    => 708,
                'title' => 'anticipo_access',
            ],
            [
                'id'    => 709,
                'title' => 'interes_anticipo_create',
            ],
            [
                'id'    => 710,
                'title' => 'recepcion_create',
            ],
            [
                'id'    => 711,
                'title' => 'recepcion_edit',
            ],
            [
                'id'    => 712,
                'title' => 'recepcion_show',
            ],
            [
                'id'    => 713,
                'title' => 'recepcion_delete',
            ],
            [
                'id'    => 714,
                'title' => 'recepcion_access',
            ],
            [
                'id'    => 715,
                'title' => 'proceso_create',
            ],
            [
                'id'    => 716,
                'title' => 'proceso_edit',
            ],
            [
                'id'    => 718,
                'title' => 'proceso_show',
            ],
            [
                'id'    => 719,
                'title' => 'proceso_delete',
            ],
            [
                'id'    => 720,
                'title' => 'proceso_access',
            ],
            [
                'id'    => 721,
                'title' => 'interes_anticipo_create',
            ],
            [
                'id'    => 722,
                'title' => 'interes_anticipo_edit',
            ],
            [
                'id'    => 723,
                'title' => 'interes_anticipo_show',
            ],
            [
                'id'    => 724,
                'title' => 'interes_anticipo_delete',
            ],
            [
                'id'    => 725,
                'title' => 'interes_anticipo_access',
            ],
             [
                'id'    => 730,
                'title' => 'analisi_create',
            ],
            [
                'id'    => 731,
                'title' => 'analisi_edit',
            ],
            [
                'id'    => 732,
                'title' => 'analisi_show',
            ],
            [
                'id'    => 733,
                'title' => 'analisi_delete',
            ],
            [
                'id'    => 734,
                'title' => 'analisi_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
