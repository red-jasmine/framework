<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValues\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValues\ProductAttributeValueResource;

class ViewProductAttributeValue extends ViewRecord
{
    protected static string $resource = ProductAttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
    use ResourcePageHelper;
}
