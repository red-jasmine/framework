<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories\ProductCategoryResource;

class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions() : array
    {
        return [
            DeleteAction::make(),
        ];
    }

    use ResourcePageHelper;
}
