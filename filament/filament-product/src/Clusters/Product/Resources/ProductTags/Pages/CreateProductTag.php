<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTags\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTags\ProductTagResource;

class CreateProductTag extends CreateRecord
{
    protected static string $resource = ProductTagResource::class;
    use ResourcePageHelper;
}
