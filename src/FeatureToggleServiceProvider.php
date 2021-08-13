<?php

namespace CharlGottschalk\FeatureToggleLumen;

use CharlGottschalk\FeatureToggleLumen\Console\AddFeature;
use CharlGottschalk\FeatureToggleLumen\Console\AddRoleToFeature;
use CharlGottschalk\FeatureToggleLumen\Console\DisableFeature;
use CharlGottschalk\FeatureToggleLumen\Console\EnableFeature;
use CharlGottschalk\FeatureToggleLumen\Console\RemoveFeature;
use CharlGottschalk\FeatureToggleLumen\Console\RemoveRoleFromFeature;
use CharlGottschalk\FeatureToggleLumen\Console\ToggleFeature;
use CharlGottschalk\FeatureToggleLumen\Http\Middleware\SanitizeInput;
use Illuminate\Support\ServiceProvider;

class FeatureToggleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('feature', function($app) {
            return new Feature();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'features');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->registerCommands();
//            $this->registerPublish();
        }
    }

    protected function registerCommands() {
        $this->commands([
            AddFeature::class,
            AddRoleToFeature::class,
            DisableFeature::class,
            EnableFeature::class,
            RemoveFeature::class,
            RemoveRoleFromFeature::class,
            ToggleFeature::class
        ]);
    }

    protected function registerPublish() {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('features.php'),
        ], 'config');
    }

    protected function registerRoutes()
    {
        $this->app->router->group($this->routeConfiguration(), function () {
            include __DIR__.'/../routes/web.php';
        });
    }

    protected function routeConfiguration(): array
    {
        $middleware = [
            SanitizeInput::class
        ];
        $configMiddleware = config('features.route.middleware');

        if(!empty($configMiddleware) && is_array($configMiddleware)) {
            array_merge($middleware, $configMiddleware);
        }

        if(!empty($configMiddleware) && is_string($configMiddleware)) {
            $middleware[] = $configMiddleware;
        }

        return [
            'prefix' => config('features.route.prefix'),
            'middleware' => $middleware,
        ];
    }
}
