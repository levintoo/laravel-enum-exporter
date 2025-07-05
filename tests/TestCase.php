<?php

namespace Levintoo\LaravelEnumExporter\Tests;

use Illuminate\Support\Facades\File;
use Levintoo\LaravelEnumExporter\EnumExporterServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            EnumExporterServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app->basePath(realpath(__DIR__.'/fixtures'));
        $app['env'] = 'local';
    }

    protected function loadTestEnums(): void
    {
        $sandboxEnumPath = app_path('Enums');
        $sandboxTsEnumPath = base_path('resources/js/enums');

        if (File::isDirectory($sandboxTsEnumPath)) {
            File::deleteDirectory($sandboxTsEnumPath);
        }

        if (File::isDirectory($sandboxEnumPath)) {
            File::deleteDirectory($sandboxEnumPath);
        }

        File::copyDirectory(
            __DIR__.'/fixtures/app/Enums',
            $sandboxEnumPath
        );

        foreach (File::allFiles($sandboxEnumPath) as $file) {
            require_once $file->getPathname();
        }
    }
}
