<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Toggle switch
    |--------------------------------------------------------------------------
    |
    | This option controls if Feature Toggle is switched on or off.
    | If switched on (true), feature toggle will check if the given feature is enabled.
    | If switched off (false), feature toggle will return whether all features are enabled ('all_on').
    |
    */
    'on' => true,

    /*
    |--------------------------------------------------------------------------
    | Features switch
    |--------------------------------------------------------------------------
    |
    | This option controls if all features are 'enabled' or 'disabled' based on the 'on' option.
    | If 'all_on' => true, then all features will be 'enabled'.
    | If 'all_on' => false, then all features will be 'disabled'.
    |
    */
    'all_on' => true,

    /*
    |--------------------------------------------------------------------------
    | Database connection
    |--------------------------------------------------------------------------
    |
    | Specify which database connection to use for migrations and models.
    | Add a connection if you want to separate the feature toggles from your main
    | database.
    |
    */
    'connection' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | User roles
    |--------------------------------------------------------------------------
    |
    | This option is used to assist with role based checks.
    |
    */
    'roles' => [
        /*
        |--------------------------------------------------------------------------
        | User role property
        |--------------------------------------------------------------------------
        |
        | This option is used to determine the property on the user model
        | that retrieves the user's role.
        | Feature Toggle will check if the property exists and use it, otherwise
        | check if a function with the name exists and use that.
        |
        */
        'property' => 'role',

        /*
        |--------------------------------------------------------------------------
        | User roles table
        |--------------------------------------------------------------------------
        |
        | This option is used to determine the table containing the user roles.
        |
        */
        'model' => \App\Models\Role::class,

        /*
        |--------------------------------------------------------------------------
        | User roles table name column
        |--------------------------------------------------------------------------
        |
        | This option is used to determine the name of the roles table column containing
        | the name of roles.
        |
        */
        'column' => 'name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | This option is used to assist with authentication of feature management UI.
    |
    */
    'auth' => [
        /*
        |--------------------------------------------------------------------------
        | User role
        |--------------------------------------------------------------------------
        |
        | The role an authenticated user needs in order to access the feature toggle UI.
        |
        */
        'role' => 'developer',
    ],

    'route' => [
        /*
        |--------------------------------------------------------------------------
        | Auth middleware
        |--------------------------------------------------------------------------
        |
        | The middleware to use for feature toggle routes.
        |
        */
        'middleware' => [],

        /*
        |--------------------------------------------------------------------------
        | Prefix
        |--------------------------------------------------------------------------
        |
        | The route prefix to use for feature toggle routes.
        |
        */
        'prefix' => 'features',
    ],
];
