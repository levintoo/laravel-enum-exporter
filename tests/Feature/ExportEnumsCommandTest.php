<?php

use Levintoo\LaravelEnumExporter\Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->loadTestEnums();
});

test('exports a single enum to TypeScript', function () {
    $this->artisan('export:enum', [
        'class' => 'Role',
    ])->assertExitCode(0);

    $outputFile = base_path('resources/js/enums/role.ts');

    expect(File::exists($outputFile))->toBeTrue();

    $fileContents = File::get($outputFile);

    expect($fileContents)->toContain('export enum Role')
        ->and($fileContents)->toContain("Admin = 'admin'")
        ->and($fileContents)->toContain("User = 'user'")
        ->and($fileContents)->toContain("Guest = 'guest'");
});

test('prompts to export all enums and exports when user confirms with yes', function () {
    $tsOutputDir = base_path('resources/js/enums');

    $this->artisan('export:enum')
        ->expectsQuestion('No enum class provided. Do you want to export all enums instead?', 'yes')
        ->assertExitCode(0);

    expect(File::exists("{$tsOutputDir}/role.ts"))->toBeTrue()
        ->and(File::exists("{$tsOutputDir}/system-role.ts"))->toBeTrue()
        ->and(File::exists("{$tsOutputDir}/user-status-enum.ts"))->toBeTrue();
});

test('skips exporting if the ts file already exists', function () {
    $tsFile = base_path('resources/js/enums/role.ts');

    File::ensureDirectoryExists(dirname($tsFile));
    File::put($tsFile, '// dummy content');

    $this->artisan('export:enum', ['class' => 'Role'])
        ->expectsOutputToContain('⚠️ Skipped role.ts, file already exists.')
        ->assertExitCode(0);

    expect(File::get($tsFile))->toBe('// dummy content');
});

test('warns if app/Enums directory does not exist', function () {
    File::deleteDirectory(app_path('Enums'));

    $this->artisan('export:enum', [
        '--all' => true,
    ])
        ->expectsOutputToContain('⚠️ No app/Enums directory found.')
        ->assertExitCode(0);

    File::ensureDirectoryExists(app_path('Enums'));
});

test('handles file without a namespace or enum gracefully', function () {
    $invalidEnumPath = app_path('Enums/InvalidEnum.php');
    File::put($invalidEnumPath, '<?php // invalid enum file');

    $this->artisan('export:enum', [
        'class' => 'app/Enums/InvalidEnum.php',
    ])
        ->expectsOutputToContain("❌ Unable to process 'app/Enums/InvalidEnum.php'.")
        ->expectsOutputToContain('💡 Make sure it refers to a valid PHP enum (class name or file path).')
        ->assertExitCode(0);

    $this->artisan('export:enum', [
        'class' => 'NotAnEnum',
    ])
        ->expectsOutputToContain("❌ Unable to process 'NotAnEnum'.")
        ->expectsOutputToContain('💡 Make sure it refers to a valid PHP enum (class name or file path).')
        ->assertExitCode(0);

    File::delete($invalidEnumPath);
});
