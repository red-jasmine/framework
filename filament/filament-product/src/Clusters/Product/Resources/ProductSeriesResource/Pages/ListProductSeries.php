<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource\Pages;

use Filament\Actions\CreateAction;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductSeries extends ListRecords
{
    use ResourcePageHelper;
    protected static string $resource = ProductSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
