<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyResource;

class CreateProductProperty extends CreateRecord
{
    protected static string $resource = ProductPropertyResource::class;
    use ResourcePageHelper;
}
