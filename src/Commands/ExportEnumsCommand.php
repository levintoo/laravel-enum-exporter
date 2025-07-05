<?php

namespace Levintoo\LaravelEnumExporter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ReflectionEnum;

class ExportEnumsCommand extends Command
{
    protected $signature = 'export:enum {class? : Fully qualified enum class name} {--all : Export all enums in app/Enums}';

    protected $description = 'Export PHP enums to TypeScript for frontend use';

    public function handle(): void
    {
        $enumClass = $this->argument('class');
        $exportAll = $this->option('all');

        if ($exportAll) {
            $this->exportAllEnums();

            return;
        }

        if ($enumClass) {
            $this->exportSingleEnum($enumClass);

            return;
        }

        if ($this->confirm('No enum class provided. Do you want to export all enums instead?')) {
            $this->exportAllEnums();
        } else {
            $this->warn('❌ No action taken. Provide a class or use --all.');
        }
    }

    /**
     * Export a single enum by class name.
     */
    protected function exportSingleEnum(string $input): void
    {
        $input = str_replace(['\\', '//'], '/', $input);

        try {
            $enumClass = $this->resolveEnumClass($input);

            if (! enum_exists($enumClass)) {
                throw new \RuntimeException;
            }

            $this->exportEnumToTypeScript($enumClass);
            $this->info("✅ Exported enum: {$enumClass}");
        } catch (\Throwable) {
            $this->error("❌ Unable to process '{$input}'.");
            $this->line('💡 Make sure it refers to a valid PHP enum (class name or file path).');
            $this->line('Example: php artisan export:enum app/Enums/Role.php');
        }
    }

    /**
     * Resolves the fully qualified enum class name from a user-provided input.
     */
    protected function resolveEnumClass(string $input): string
    {
        if (str_ends_with($input, '.php')) {
            $absolutePath = base_path($input);

            if (! file_exists($absolutePath)) {
                throw new \RuntimeException;
            }

            $enumClass = $this->getClassFromFile($absolutePath);

            if (! $enumClass) {
                throw new \RuntimeException;
            }

            return $enumClass;
        }

        $enumClass = "App\\Enums\\{$input}";
        if (! class_exists($enumClass)) {
            throw new \RuntimeException;
        }

        return $enumClass;
    }

    /**
     * Export all enums in app/Enums directory.
     */
    protected function exportAllEnums(): void
    {
        $enumPath = app_path('Enums');

        if (! is_dir($enumPath)) {
            $this->warn('⚠️ No app/Enums directory found.');

            return;
        }

        $files = glob("{$enumPath}/*.php");

        foreach ($files as $file) {
            $className = $this->getClassFromFile($file);

            if ($className && enum_exists($className)) {
                $this->exportEnumToTypeScript($className);
                $this->info("✅ Exported enum: {$className}");
            }
        }
    }

    /**
     * Export a PHP enum class to a TypeScript file.
     */
    protected function exportEnumToTypeScript(string $enumClass): void
    {
        $reflection = new ReflectionEnum($enumClass);

        $cases = array_map(
            fn ($case) => $reflection->isBacked()
                ? ($reflection->getBackingType()->getName() === 'int'
                    ? "    {$case->name} = {$case->getBackingValue()},"
                    : "    {$case->name} = '{$case->getBackingValue()}',")
                : "    {$case->name} = '{$case->name}',",
            $reflection->getCases()
        );

        $enumName = class_basename($enumClass);

        $stub = file_get_contents(__DIR__.'/../stubs/enum.stub');
        $tsContent = str_replace(
            ['{{ enumName }}', '{{ cases }}'],
            [$enumName, implode("\n", $cases)],
            $stub
        );

        $fileName = Str::kebab($enumName).'.ts';
        $outputPath = base_path("resources/js/enums/{$fileName}");

        if (file_exists($outputPath)) {
            $this->warn("⚠️ Skipped {$fileName}, file already exists.");

            return;
        }

        if (! is_dir(dirname($outputPath))) {
            mkdir(dirname($outputPath), recursive: true);
        }

        file_put_contents($outputPath, $tsContent);
    }

    /**
     * Resolve fully qualified class name from file path.
     */
    protected function getClassFromFile(string $file): ?string
    {
        $contents = file_get_contents($file);

        if (preg_match('/namespace (.*);/', $contents, $namespaceMatch) &&
            preg_match('/enum (\w+)/', $contents, $classMatch)) {
            return "{$namespaceMatch[1]}\\{$classMatch[1]}";
        }

        return null;
    }
}
