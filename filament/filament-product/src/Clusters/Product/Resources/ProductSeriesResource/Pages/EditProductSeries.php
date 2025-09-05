<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductSeries extends EditRecord
{
    use ResourcePageHelper;

    protected static string $resource = ProductSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

}
