<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories\ProductCategoryResource;

class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
