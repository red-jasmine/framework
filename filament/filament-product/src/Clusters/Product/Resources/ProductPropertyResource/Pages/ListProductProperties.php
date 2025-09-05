<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyResource;

class ListProductProperties extends ListRecords
{
    protected static string $resource = ProductPropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
