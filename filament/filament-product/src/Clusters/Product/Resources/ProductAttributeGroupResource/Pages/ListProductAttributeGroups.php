<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource;

class ListProductAttributeGroups extends ListRecords
{
    protected static string $resource = ProductAttributeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
