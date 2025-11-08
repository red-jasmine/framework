<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributes\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributes\ProductAttributeResource;

class ViewProductAttribute extends ViewRecord
{
    protected static string $resource = ProductAttributeResource::class;
    use ResourcePageHelper;
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

}
