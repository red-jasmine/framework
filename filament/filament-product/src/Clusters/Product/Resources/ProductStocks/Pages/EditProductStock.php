<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStocks\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStocks\ProductStockResource;

class EditProductStock extends EditRecord
{
    use ResourcePageHelper;

    protected static string $resource = ProductStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
