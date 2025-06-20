<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockResource\Pages;


use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockResource;

class ListProductStocks extends ListRecords
{
    protected static string $resource = ProductStockResource::class;

    public function infolist(Infolist $infolist) : Infolist
    {

        return parent::infolist($infolist); 
    }


}
