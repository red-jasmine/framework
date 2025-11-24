<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogs\Pages;

use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogs\ProductStockLogResource;

class ListProductStockLogs extends ListRecords
{
    use ResourcePageHelper;

    protected static string $resource = ProductStockLogResource::class;
}
