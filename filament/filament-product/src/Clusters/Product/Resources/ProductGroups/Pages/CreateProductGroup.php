<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups\ProductGroupResource;

class CreateProductGroup extends CreateRecord
{
    protected static string $resource = ProductGroupResource::class;

    use ResourcePageHelper;
}
