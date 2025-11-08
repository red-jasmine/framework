<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeries\ProductSeriesResource;

class CreateProductSeries extends CreateRecord
{
    protected static string $resource = ProductSeriesResource::class;

    use ResourcePageHelper;



}
