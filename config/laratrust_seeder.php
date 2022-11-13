<?php
/**
 * Laratrust seeder
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superadministrator'=>[
            'adminMenu'=>'c,r',
            'users'=>'c,r,u,d',
            'roles'=>'c,r,u,d',
            'permissions'=>'c,r,u,d',
            'clients'=>'c,r,u,d',
            'settingsMenu'=>'r',
            'accessoriesSetting'=>'c,r,u,d',
            'apartmentsSetting'=>'c,r,u,d',
            'benefitsSetting'=>'c,r,u,d',
            'contractsSetting'=>'c,r,u,d',
            'dependenciesSetting'=>'c,r,u,d',
            'companyMenu'=>'r',
            'company'=>'c,r,u,d',
            'building'=>'c,r,u,d',
            'apartment'=>'c,r,u,d',
            'dependency'=>'c,r,u,d',
            'accessory'=>'c,r,u,d',
            'employee'=>'c,r,u,d',
            'contract'=>'c,r,u,d',
            'leaseMenu'=>'c,r,u,d',
            'lease'=>'c,r,u,d',
            'tenant'=>'c,r,u,d',
            'bill'=>'c,r,u,d',
            'payment'=>'c,r,u,d',
            'manteinanceMenu'=>'r',
            'ticket'=>'c,r,u,d',
            'communication'=>'c,r,u,d',
        ],
        'owner'=>[
            'settingsMenu'=>'r',
            'accessoriesSetting'=>'c,r,u,d',
            'apartmentsSetting'=>'c,r,u,d',
            'benefitsSetting'=>'c,r,u,d',
            'contractsSetting'=>'c,r,u,d',
            'dependenciesSetting'=>'c,r,u,d',
            'companyMenu'=>'r',
            'company'=>'r,u',
            'building'=>'c,r,u,d',
            'apartment'=>'c,r,u,d',
            'dependency'=>'c,r,u,d',
            'accessory'=>'c,r,u,d',
            'employee' =>'c,r,u,d',
            'contract'=>'c,r,u,d',
            'leaseMenu'=>'r',
            'lease'=>'c,r,u,d',
            'tenant'=>'c,r,u,d',
            'bill'=>'c,r,u,d',
            'payment'=>'c,r,u,d',
            'manteinanceMenu'=>'r',
            'ticket'=>'c,r,u,d',
            'communication'=>'c,r,u,d',
        ],
        'manager'=>[
            'settingsMenu'=>'r',
            'accessoriesSetting'=>'r',
            'apartmentsSetting'=>'r',
            'benefitsSetting'=>'c,r,u,d',
            'contractsSetting'=>'r,u',
            'dependenciesSetting'=>'r',
            'companyMenu'=>'r',
            'company'=>'r,u',
            'building'=>'c,r,u,d',
            'apartment'=>'c,r,u,d',
            'dependency'=>'c,r,u,d',
            'accessory'=>'c,r,u,d',
            'employee'=>'c,r,u,d',
            'contract'=>'c,r,u,d',
            'leaseMenu'=>'r',
            'lease'=>'c,r,u,d',
            'tenant'=>'c,r,u,d',
            'bill'=>'c,r,u,d',
            'payment'=>'c,r,u,d',
            'manteinanceMenu'=>'r',
            'ticket'=>'c,r,u,d',
            'communication'=>'c,r,u,d',
        ],
        'janitor'=>[
            'companyMenu'=>'r',
            'building'=>'r',
            'apartment'=>'r',
            'dependency'=>'r',
            'accessory'=>'c,r,u',
            'leaseMenu'=>'r',
            'lease'=>'r',
            'tenant'=>'r',
            'manteinanceMenu'=>'r',
            'ticket'=>'c,r,u,d',
            'communication'=> 'r'
        ],
        'tenant'=>[
            'leaseMenu'=>'r',
            'lease'=>'r',
            'payment'=>'c,r,u,d',
            'manteinanceMenu'=>'r',
            'ticket'=>'c,r,u,d',
            'communication'=> 'r'
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
