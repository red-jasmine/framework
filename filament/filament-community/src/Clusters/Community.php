<?php

namespace RedJasmine\FilamentCommunity\Clusters;


use Filament\Clusters\Cluster;

class Community extends Cluster
{



    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';


    public static function getNavigationLabel() : string
    {

        return __('red-jasmine-filament-community::community.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('red-jasmine-filament-community::community.label');
    }

}
