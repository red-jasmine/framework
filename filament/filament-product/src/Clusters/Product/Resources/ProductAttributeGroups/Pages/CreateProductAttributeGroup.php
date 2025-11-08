<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\ProductAttributeGroupResource;

class CreateProductAttributeGroup extends CreateRecord
{
    protected static string $resource = ProductAttributeGroupResource::class;
}
