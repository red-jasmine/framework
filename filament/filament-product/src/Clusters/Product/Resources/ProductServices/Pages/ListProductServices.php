<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\ProductServiceResource;

class ListProductServices extends ListRecords
{
    protected static string $resource = ProductServiceResource::class;
    use ResourcePageHelper;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
