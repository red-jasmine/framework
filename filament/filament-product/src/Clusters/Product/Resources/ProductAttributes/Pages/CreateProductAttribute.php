<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributes\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributes\ProductAttributeResource;

class CreateProductAttribute extends CreateRecord
{
    protected static string $resource = ProductAttributeResource::class;
    use ResourcePageHelper;
}
