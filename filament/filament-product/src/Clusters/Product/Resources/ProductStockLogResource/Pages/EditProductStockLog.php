<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogResource;

class EditProductStockLog extends EditRecord
{
    protected static string $resource = ProductStockLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
