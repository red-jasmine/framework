<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockResource;

class EditProductStock extends EditRecord
{
    protected static string $resource = ProductStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
