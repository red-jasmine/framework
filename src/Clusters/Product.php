<?php

namespace RedJasmine\FilamentProduct\Clusters;


use Filament\Clusters\Cluster;

class Product extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';


    public static function getNavigationLabel() : string
    {

        return __('filament-product::product.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('filament-product::product.label');
    }

}
