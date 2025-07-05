<?php

namespace Levintoo\LaravelEnumExporter;

use Illuminate\Support\ServiceProvider;
use Levintoo\LaravelEnumExporter\Commands\ExportEnumsCommand;

class EnumExporterServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        // bind any helper classes or singletons here if needed later
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                ExportEnumsCommand::class,
            ]);
        }
    }
}
