<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource\Pages;

use Filament\Actions\EditAction;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

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
