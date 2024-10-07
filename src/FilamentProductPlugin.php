<?php

namespace RedJasmine\FilamentProduct;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentProductPlugin implements Plugin
{
    public static function make() : static
    {
        return app(static::class);
    }

    public static function get() : static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId() : string
    {
        return 'filament-product';
    }

    public function register(Panel $panel) : void
    {


        $panel->discoverClusters(in: __DIR__ . '/Clusters/', for: 'RedJasmine\\FilamentProduct\\Clusters');

    }

    public function boot(Panel $panel) : void
    {
        //
    }
}
