<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServiceResource\Pages;

use Filament\Actions\CreateAction;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
