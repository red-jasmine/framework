<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\ProductResource;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions() : array
    {
        return [
            EditAction::make(),
        ];
    }

    use ResourcePageHelper;


}
