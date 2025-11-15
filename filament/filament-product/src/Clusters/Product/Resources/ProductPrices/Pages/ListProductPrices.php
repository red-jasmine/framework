<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\ProductPriceResource;

class ListProductPrices extends ListRecords
{
    use ResourcePageHelper;
    
    protected static string $resource = ProductPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

