<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\ProductServiceResource;

class CreateProductService extends CreateRecord
{
    protected static string $resource = ProductServiceResource::class;
    use ResourcePageHelper;
}
