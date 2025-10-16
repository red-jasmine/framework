<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource\Pages;

use Filament\Actions\EditAction;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource;

class ViewProductAttributeGroup extends ViewRecord
{
    protected static string $resource = ProductAttributeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
