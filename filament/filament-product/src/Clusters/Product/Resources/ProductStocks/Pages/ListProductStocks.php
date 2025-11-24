<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStocks\Pages;

use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStocks\ProductStockResource;

class ListProductStocks extends ListRecords
{
    use ResourcePageHelper;

    protected static string $resource = ProductStockResource::class;
}
