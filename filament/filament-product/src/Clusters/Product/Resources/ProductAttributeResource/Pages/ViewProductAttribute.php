<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource\Pages;

use Filament\Actions\EditAction;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource;

class ViewProductAttribute extends ViewRecord
{
    protected static string $resource = ProductAttributeResource::class;
    use ResourcePageHelper;
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

}
