<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\ProductSeriesResource;

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
