<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource;

class ViewProductPropertyGroup extends ViewRecord
{
    protected static string $resource = ProductPropertyGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
