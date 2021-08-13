
# Laravel Feature Toggle for Lumen

[![Latest Version on Packagist](https://img.shields.io/packagist/v/charlgottschalk/feature-toggle-lumen.svg?style=flat-square)](https://packagist.org/packages/charlgottschalk/feature-toggle-lumen)
[![Total Downloads](https://img.shields.io/packagist/dt/charlgottschalk/feature-toggle-lumen.svg?style=flat-square)](https://packagist.org/packages/charlgottschalk/feature-toggle-lumen)

Feature toggling is a coding strategy used along with source control to make it easier to continuously integrate and deploy. 
The idea of the toggles essentially prevents sections of code from executing if a feature is disabled.

---

_* This is a work in progress, but is stable and working_

LFT provides a simple package for implementing feature toggles allowing you to switch features on and off using Artisan commands.

Oh, and it supports user roles, your user roles.

## Installation

---

#### 1. Install the package using composer:
```
$ composer require charlgottschalk\feature-toggle-lumen
```

The package should be auto-discovered by Lumen, but if it's not, simply register the service provider in your `bootstrap/app.php`:
```php
$app->register(CharlGottschalk\FeatureToggleLumen\FeatureToggleServiceProvider::class);
```

#### 2. Enable Eloquent:

If it isn't already, enable Eloquent in your `bootstrap/app.php` by uncommenting this line:

```php
$app->withEloquent();
```

#### 3. Enable Facades:

If it isn't already, enable Facades in your `bootstrap/app.php` by uncommenting this line:

```php
$app->withFacades();
```

#### 4. Migrate
Run `$ php artisan migrate` to create the feature toggle tables.

## Usage

---

LFT provides a few easy to use helper functions and middleware to determine if features are enabled.

#### Facade

LFT provides a facade to easily check feature toggles in controllers etc.

To check if a feature is enabled - no roles checked
```php
use CharlGottschalk\FeatureToggleLumen\Facades\Feature;

if (Feature::enabled('feature_name')) {
    // Feature is enabled
}
````

To check if a feature is enabled, including if the `$request` user has permission via a role
```php
use CharlGottschalk\FeatureToggleLumen\Facades\Feature;

if (Feature::enabledFor('feature_name')) {
    // Feature is enabled and user has permission
}
```
LFT will attempt to retrieve the user's role via the property and roles configuration (see config) when determining if a user has permission to access a feature.


#### Helpers

LFT provides helper functions for checking feature toggles.
```php
if (feature_enabled('feature_name')) {
    // Feature is enabled
}

if (enabled_for('feature_name')) {
    // Feature is enabled and user has permission
}
```

#### Middleware

LFT provides middleware for disabling routes if a feature is disabled or the user does not have permission.

Add the middleware to your `bootstrap\app.php`.

```php
$app->routeMiddleware([
    // Other middleware
    'feature' => \CharlGottschalk\FeatureToggleLumen\Http\Middleware\CheckFeature::class,
    'feature.role' => \CharlGottschalk\FeatureToggleLumen\Http\Middleware\CheckFeatureRole::class,
]);
```

Add the middleware to your required routes.
```php
$router->get('some/url', ['middleware' => 'feature:feature_name', function () {
    //
}]);

$router->get('another/url', ['middleware' => 'feature.role:feature_name', function () {
    //
}]);
```

The middleware will `abort(404)` if the given feature is disabled or the user does not have permission.

## Config

---

```php
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

```

## ToDo:

---

- [ ] Validation Rules
- [ ] Scheduling Checks

## License

---

The MIT License (MIT). Please see [License File](LICENSE) for more information.
