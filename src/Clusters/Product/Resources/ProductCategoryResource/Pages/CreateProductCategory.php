<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategoryResource;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    use ResourcePageHelper;
}
