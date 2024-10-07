<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSellerCategoryResource;

class ListProductSellerCategories extends ListRecords
{
    protected static string $resource = ProductSellerCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
