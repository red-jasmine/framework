<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockResource\Pages;


use Filament\Schemas\Schema;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockResource;

class ListProductStocks extends ListRecords
{
    protected static string $resource = ProductStockResource::class;

    public function infolist(Schema $schema) : Schema
    {

        return parent::infolist($schema); 
    }


}
