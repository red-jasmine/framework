<?php

namespace RedJasmine\FilamentLogistics;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentLogisticsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-logistics';
    }

    public function register(Panel $panel): void
    {
        $panel->discoverClusters(in: __DIR__ . '/Clusters/', for: 'RedJasmine\\FilamentLogistics\\Clusters');
    }

    public function boot(Panel $panel): void
    {
        //
    }

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
}
