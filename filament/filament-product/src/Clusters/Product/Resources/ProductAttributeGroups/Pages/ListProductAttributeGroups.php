<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\ProductAttributeGroupResource;

class ListProductAttributeGroups extends ListRecords
{
    protected static string $resource = ProductAttributeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
