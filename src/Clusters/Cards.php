<?php

namespace RedJasmine\FilamentCard\Clusters;

use Filament\Clusters\Cluster;

class Cards extends Cluster
{

    protected static ?string $navigationIcon = 'heroicon-o-ticket';


    public static function getNavigationLabel() : string
    {
        return '卡密';
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return '卡密';
    }


}
