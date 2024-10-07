<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource;

class ViewProductPropertyValue extends ViewRecord
{
    protected static string $resource = ProductPropertyValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    use ResourcePageHelper;
}
