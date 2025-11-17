<?php

namespace RedJasmine\FilamentWarehouse\Clusters;

use Filament\Clusters\Cluster;

class Warehouse extends Cluster
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    public static function getNavigationLabel(): string
    {
        return __('red-jasmine-warehouse::warehouse.label');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('red-jasmine-warehouse::warehouse.label');
    }
}

