<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTags\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTags\ProductTagResource;

class ListProductTags extends ListRecords
{
    use ResourcePageHelper;
    protected static string $resource = ProductTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
