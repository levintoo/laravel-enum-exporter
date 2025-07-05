<?php

use Levintoo\LaravelEnumExporter\Tests\ProductionTestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

uses(ProductionTestCase::class);

test('the export:enum command is not registered in production', function () {
    $this->expectException(CommandNotFoundException::class);

    $this->artisan('export:enum', ['--all' => true])
        ->assertExitCode(1);
});
