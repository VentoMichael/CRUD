<?php

namespace VentoMichael\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateCrudCommand extends Command
{
    protected $signature = 'crud:{model}';
    protected $description = 'Creates a migration and controller for a model';

    public function handle()
    {
        $modelName = Str::studly($this->argument('model'));

        $this->createMigration($modelName);
        $this->createController($modelName);
    }

    private function createMigration(string $modelName)
    {
        $migrationName = Str::snake($modelName) . '_table';
        $migrationPath = database_path('migrations/' . $migrationName . '.php');

        if (file_exists($migrationPath)) {
            $this->error('The migration ' . $migrationName . ' already exists.');
            return;
        }

        $this->call('make:migration', ['--name' => $migrationName]);

    }

    private function createController(string $modelName)
    {
        $controllerName = $modelName . 'Controller';
        $controllerPath = app_path('Http/Controllers/' . $controllerName . '.php');

        if (file_exists($controllerPath)) {
            $this->error('The controller ' . $controllerName . ' already exists.');
            return;
        }

        $this->call('make:controller', ['--name' => $controllerName]);

        $controllerContent = file_get_contents($controllerPath);

        $methods = [
            'index' => "public function index()
        {
            // Implement logic to retrieve all {$modelName} records
            \${$modelName}s = {$modelName}::all();
            return view('{$modelName}s.index', compact('{$modelName}s'));
        }",
            'show' => "public function show({$modelName} \${$modelName})
        {
            // Implement logic to retrieve a specific {$modelName} record
            return view('{$modelName}s.show', compact('{$modelName}'));
        }",
        ];

        $controllerContent = str_replace(
            '//',
            implode("\n\n", $methods),
            $controllerContent
        );

        file_put_contents($controllerPath, $controllerContent);
    }

}