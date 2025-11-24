<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogs\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogs\ProductStockLogResource;

class EditProductStockLog extends EditRecord
{
    protected static string $resource = ProductStockLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
