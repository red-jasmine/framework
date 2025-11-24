<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogs\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogs\ProductStockLogResource;

class CreateProductStockLog extends CreateRecord
{
    protected static string $resource = ProductStockLogResource::class;
}
