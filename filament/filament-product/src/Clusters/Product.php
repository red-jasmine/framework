<?php

namespace RedJasmine\FilamentProduct\Clusters;


use Filament\Clusters\Cluster;

class Product extends Cluster
{



    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';


    public static function getNavigationLabel() : string
    {

        return __('red-jasmine-filament-product::product.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('red-jasmine-filament-product::product.label');
    }

}
