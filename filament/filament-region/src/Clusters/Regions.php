<?php

namespace RedJasmine\FilamentRegion\Clusters;

use Filament\Clusters\Cluster;

class Regions extends Cluster
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map';

    public static function getNavigationLabel(): string
    {
        return __('red-jasmine-filament-region::region.label');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('red-jasmine-filament-region::region.label');
    }
}

