<?php

namespace RedJasmine\FilamentUser\Clusters;


use Filament\Clusters\Cluster;

class Users extends Cluster
{

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';


    public static function getNavigationLabel() : string
    {

        return __('red-jasmine-filament-user::user.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('red-jasmine-filament-user::user.label');
    }

}
