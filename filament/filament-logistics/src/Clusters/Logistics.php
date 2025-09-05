<?php

namespace RedJasmine\FilamentLogistics\Clusters;

use Filament\Clusters\Cluster;

class Logistics extends Cluster
{

    protected static ?string $navigationIcon = 'heroicon-o-truck';


    public static function getNavigationLabel() : string
    {
        return __('red-jasmine-filament-logistics::logistics.cluster.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('red-jasmine-filament-logistics::logistics.cluster.label');
    }

}