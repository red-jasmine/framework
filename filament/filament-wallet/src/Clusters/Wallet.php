<?php

namespace RedJasmine\FilamentWallet\Clusters;


use Filament\Clusters\Cluster;

class Wallet extends Cluster
{



    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';


    public static function getNavigationLabel() : string
    {

        return __('red-jasmine-filament-wallet::wallet.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('red-jasmine-filament-wallet::wallet.label');
    }

}
