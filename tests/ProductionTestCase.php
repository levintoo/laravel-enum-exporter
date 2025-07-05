<?php

namespace Levintoo\LaravelEnumExporter\Tests;

abstract class ProductionTestCase extends TestCase
{
    protected function defineEnvironment($app): void
    {
        $app['env'] = 'production';
    }
}
