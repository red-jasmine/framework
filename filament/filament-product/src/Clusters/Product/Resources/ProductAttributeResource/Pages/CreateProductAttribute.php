<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource;

class CreateProductAttribute extends CreateRecord
{
    protected static string $resource = ProductAttributeResource::class;
    use ResourcePageHelper;
}
