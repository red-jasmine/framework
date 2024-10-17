<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServiceResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductService extends CreateRecord
{
    protected static string $resource = ProductServiceResource::class;
    use ResourcePageHelper;
}
