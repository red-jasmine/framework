<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValues\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValues\ProductAttributeValueResource;

class ListProductAttributeValues extends ListRecords
{
    protected static string $resource = ProductAttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
