<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups\ProductGroupResource;

class ListProductGroups extends ListRecords
{
    protected static string $resource = ProductGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

}
