<?php

namespace RedJasmine\FilamentCard;

use Filament\Contracts\Plugin;
use Filament\Panel;
use RedJasmine\FilamentCard\Clusters\Card\Resources\CardResource;

class FilamentCardPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-card';
    }

    public function register(Panel $panel): void
    {

        $panel->discoverClusters(in: __DIR__ . '/Clusters/', for: 'RedJasmine\\FilamentCard\\Clusters');
//        $panel->resources([
//
//            CardResource::class
//                          ]);
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
