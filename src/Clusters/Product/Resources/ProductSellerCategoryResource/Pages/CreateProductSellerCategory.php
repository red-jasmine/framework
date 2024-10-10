<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSellerCategoryResource;

class CreateProductSellerCategory extends CreateRecord
{
    protected static string $resource = ProductSellerCategoryResource::class;

    use ResourcePageHelper;
}
