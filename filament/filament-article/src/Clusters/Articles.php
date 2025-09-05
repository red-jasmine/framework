<?php

namespace RedJasmine\FilamentArticle\Clusters;


use Filament\Clusters\Cluster;

class Articles extends Cluster
{



    protected static ?string $navigationIcon = 'heroicon-c-document-text';


    public static function getNavigationLabel() : string
    {

        return __('red-jasmine-filament-article::article.label');
    }


    public static function getClusterBreadcrumb() : ?string
    {
        return __('red-jasmine-filament-article::article.label');
    }

}
