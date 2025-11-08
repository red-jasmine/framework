<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\ProductSeriesResource;

class ViewProductSeries extends ViewRecord
{

    use ResourcePageHelper;
    protected static string $resource = ProductSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
