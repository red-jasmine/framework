<?php

namespace RedJasmine\FilamentCoupon;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentCouponPlugin implements Plugin
{
    public function getId(): string
    {
        return 'red-jasmine-filament-coupon';
    }

    public function register(Panel $panel): void
    {
        $panel->discoverClusters(in: __DIR__ . '/Clusters/', for: 'RedJasmine\\FilamentCoupon\\Clusters');
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