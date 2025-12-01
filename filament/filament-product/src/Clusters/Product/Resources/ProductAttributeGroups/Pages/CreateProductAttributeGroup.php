<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\ProductAttributeGroupResource;

class CreateProductAttributeGroup extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = ProductAttributeGroupResource::class;
}
