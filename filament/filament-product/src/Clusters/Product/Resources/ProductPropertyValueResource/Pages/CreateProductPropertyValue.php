<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource;

class CreateProductPropertyValue extends CreateRecord
{
    protected static string $resource = ProductPropertyValueResource::class;
    use ResourcePageHelper;
}
