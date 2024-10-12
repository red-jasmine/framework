<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductTag extends CreateRecord
{
    protected static string $resource = ProductTagResource::class;
    use ResourcePageHelper;
}
