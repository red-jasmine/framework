<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\ProductAttributeGroupResource;

class ViewProductAttributeGroup extends ViewRecord
{
    use ResourcePageHelper;
    protected static string $resource = ProductAttributeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
