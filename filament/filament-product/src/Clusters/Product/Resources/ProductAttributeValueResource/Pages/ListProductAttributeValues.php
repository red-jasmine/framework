<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValueResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValueResource;

class ListProductAttributeValues extends ListRecords
{
    protected static string $resource = ProductAttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
