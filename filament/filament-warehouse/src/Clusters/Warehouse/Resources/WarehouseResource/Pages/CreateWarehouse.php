<?php

namespace RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource;

class CreateWarehouse extends CreateRecord
{
    use ResourcePageHelper;

    protected static string $resource = WarehouseResource::class;
}

