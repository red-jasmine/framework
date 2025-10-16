<?php

namespace RedJasmine\FilamentOrder\Clusters;

use Filament\Clusters\Cluster;

class Order extends Cluster
{

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';


    public static function getNavigationLabel() : string
    {

        return __('red-jasmine-filament-order::order.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('red-jasmine-filament-order::order.label');
    }


}
