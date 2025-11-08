<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributes\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributes\ProductAttributeResource;

class ListProductAttributes extends ListRecords
{
    protected static string $resource = ProductAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
