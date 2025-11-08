<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\ProductSeriesResource;

class EditProductSeries extends EditRecord
{
    use ResourcePageHelper;

    protected static string $resource = ProductSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

}
