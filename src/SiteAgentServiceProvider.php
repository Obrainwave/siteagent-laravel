<?php

namespace ZuqoLab\SiteAgent;

use Illuminate\Support\ServiceProvider;
use ZuqoLab\SiteAgent\Console\InstallCommand;

class SiteAgentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/siteagent.php', 'siteagent');

        $this->app->singleton(StateManager::class, function ($app) {
            return new StateManager();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../config/siteagent.php' => config_path('siteagent.php'),
            ], 'siteagent-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/siteagent'),
            ], 'siteagent-views');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'siteagent');
        $this->registerRoutes();
    }

    /**
     * Register control routes.
     */
    protected function registerRoutes(): void
    {
        \Illuminate\Support\Facades\Route::post('api/system/control', [Http\Controllers\SiteControlController::class, 'handle'])
            ->middleware('api');
    }
}
