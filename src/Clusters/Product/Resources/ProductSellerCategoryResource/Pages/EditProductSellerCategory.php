<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSellerCategoryResource;

class EditProductSellerCategory extends EditRecord
{
    protected static string $resource = ProductSellerCategoryResource::class;


    protected function getHeaderActions() : array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    use ResourcePageHelper;
}
