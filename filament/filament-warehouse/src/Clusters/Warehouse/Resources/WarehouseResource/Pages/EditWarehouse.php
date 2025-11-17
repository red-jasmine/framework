<?php

namespace RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource;

class EditWarehouse extends EditRecord
{
    use ResourcePageHelper;

    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

