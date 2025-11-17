<?php

namespace RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource;

class ListWarehouses extends ListRecords
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

