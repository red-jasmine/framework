<?php

namespace RedJasmine\FilamentArticle;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentArticlePlugin implements Plugin
{
    public function getId(): string
    {
        return 'red-jasmine-filament-article';
    }

    public function register(Panel $panel): void
    {
        $panel->discoverClusters(in: __DIR__ . '/Clusters/', for: 'RedJasmine\\FilamentArticle\\Clusters');
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
