<?php

namespace RedJasmine\FilamentOrder;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentOrderPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-order';
    }

    public function register(Panel $panel): void
    {
        $panel->discoverClusters(in: __DIR__ . '/Clusters/', for: 'RedJasmine\\FilamentOrder\\Clusters');

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
