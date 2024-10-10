<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyResource;

class ViewProductProperty extends ViewRecord
{
    protected static string $resource = ProductPropertyResource::class;
    use ResourcePageHelper;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

}
