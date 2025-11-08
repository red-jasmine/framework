<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands\BrandResource;

class CreateBrand extends CreateRecord
{

    use ResourcePageHelper;

    protected static string $resource = BrandResource::class;


}


