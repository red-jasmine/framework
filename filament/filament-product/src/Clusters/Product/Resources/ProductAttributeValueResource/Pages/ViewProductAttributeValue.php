<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValueResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValueResource;

class ViewProductAttributeValue extends ViewRecord
{
    protected static string $resource = ProductAttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    use ResourcePageHelper;
}
