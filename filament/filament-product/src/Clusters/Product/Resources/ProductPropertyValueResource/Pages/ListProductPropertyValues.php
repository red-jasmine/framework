<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource;

class ListProductPropertyValues extends ListRecords
{
    protected static string $resource = ProductPropertyValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
