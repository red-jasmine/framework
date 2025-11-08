<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories\ProductCategoryResource;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    use ResourcePageHelper;
}
