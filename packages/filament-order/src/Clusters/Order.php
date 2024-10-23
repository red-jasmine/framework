<?php

namespace RedJasmine\FilamentOrder\Clusters;

use Filament\Clusters\Cluster;

class Order extends Cluster
{

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';


    public static function getNavigationLabel() : string
    {

        return __('red-jasmine-filament-order::order.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('red-jasmine-filament-order::order.label');
    }


}
