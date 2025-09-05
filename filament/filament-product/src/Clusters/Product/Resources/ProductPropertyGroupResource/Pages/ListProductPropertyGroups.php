<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource;

class ListProductPropertyGroups extends ListRecords
{
    protected static string $resource = ProductPropertyGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
