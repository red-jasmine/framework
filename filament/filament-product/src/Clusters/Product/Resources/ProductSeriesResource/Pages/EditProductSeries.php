<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
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
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

}
