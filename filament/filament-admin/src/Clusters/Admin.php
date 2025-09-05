<?php

namespace RedJasmine\FilamentAdmin\Clusters;

use Filament\Clusters\Cluster;

class Admin extends Cluster
{


    protected static ?string $navigationIcon = 'heroicon-o-shield-check';


    public static function getNavigationLabel() : string
    {

        return __('red-jasmine-filament-admin::admin.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('red-jasmine-filament-admin::admin.label');
    }

}