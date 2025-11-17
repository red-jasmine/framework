<?php

namespace RedJasmine\FilamentWarehouse;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentWarehousePlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'red-jasmine-filament-warehouse';
    }

    public function register(Panel $panel): void
    {
        $panel->discoverClusters(
            in: __DIR__ . '/Clusters/',
            for: 'RedJasmine\\FilamentWarehouse\\Clusters'
        );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}

