<?php

namespace VentoMichael\Crud;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use VentoMichael\Crud\Commands\CreateCrudCommand;

class CrudServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            CreateCrudCommand::class,
        ]);
    }

    public function boot()
    {
    }
}