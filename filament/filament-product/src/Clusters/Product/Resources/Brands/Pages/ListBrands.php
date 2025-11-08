<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands\BrandResource;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
