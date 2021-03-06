<?php

namespace Geekyants\Sharedo;



use Geekyants\Sharedo\Middleware\RestrictEntities;
use Illuminate\Support\ServiceProvider;
use Geekyants\Sharedo\Middleware\ShareInertiaData;
use Laravel\Ui\UiCommand;
use Geekyants\Sharedo\SharedoPreset;

class SharedoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {



        UiCommand::macro('sharedo', function ($command) {
            SharedoPreset::install();

            $command->info('Sharedo scaffolding installed successfully.');
            $command->info('Please run "composer update, npm install && npm run dev" to compile your fresh scaffolding.');
        });



        $this->bootInertia();




        $this->loadViewsFrom(__DIR__ . '/./resources/views', 'sharedo');
        $this->loadMigrationsFrom(__DIR__ . '/./migrations');
        $this->loadRoutesFrom(__DIR__ . '/./routes/web.php');
        app('router')->aliasMiddleware('restrict-entities', RestrictEntities::class);


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/./config/config.php' => config_path('sharedo.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__ . '/./resources/views/mail' => resource_path('views/vendor/sharedo/mail'),
            ], 'mail');



            // Registering package commands.

        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {



        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/./config/config.php', 'sharedo');




        // Register the main class to use with the facade
        $this->app->singleton('sharedo', function () {
            return new Sharedo;
        });
    }
    protected function bootInertia()
    {

        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', ShareInertiaData::class);
    }
}
