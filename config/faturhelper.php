<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    */

    'models' => [
        // 'permission' => \Ajifatur\FaturHelper\Models\Permission::class,
        // 'role' => \Ajifatur\FaturHelper\Models\Role::class,
        // 'user' => \Ajifatur\Campusnet\Models\User::class,

        'menuheader' => \Ajifatur\FaturHelper\Models\MenuHeader::class,
        'menuitem' => \Ajifatur\FaturHelper\Models\MenuItem::class,
        'permission' => \Ajifatur\FaturHelper\Models\Permission::class,
        'role' => \App\Models\Role::class,
        'user' => \Ajifatur\Campusnet\Models\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Package
    |--------------------------------------------------------------------------
    |
    */

    'package' => [
        'view' => ''
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    |
    */

    'auth' => [
        'non_admin_can_login' => false
    ],
    
];