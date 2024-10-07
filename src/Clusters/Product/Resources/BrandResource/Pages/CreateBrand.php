<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\BrandResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\BrandResource;

class CreateBrand extends CreateRecord
{

    use ResourcePageHelper;

    protected static string $resource = BrandResource::class;


}


