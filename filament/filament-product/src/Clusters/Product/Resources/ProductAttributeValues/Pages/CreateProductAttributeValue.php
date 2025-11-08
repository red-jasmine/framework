<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValues\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeValues\ProductAttributeValueResource;

class CreateProductAttributeValue extends CreateRecord
{
    protected static string $resource = ProductAttributeValueResource::class;
    use ResourcePageHelper;
}
