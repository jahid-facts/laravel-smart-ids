<?php

namespace IdGenerator;

use Illuminate\Support\ServiceProvider;

class IdGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IdGenerator::class, fn() => new IdGenerator());
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );
    }
}
