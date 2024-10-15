<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductSeries extends CreateRecord
{
    protected static string $resource = ProductSeriesResource::class;

    use ResourcePageHelper;



}
