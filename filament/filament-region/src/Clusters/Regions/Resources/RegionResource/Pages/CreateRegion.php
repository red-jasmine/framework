<?php

namespace RedJasmine\FilamentRegion\Clusters\Regions\Resources\RegionResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentRegion\Clusters\Regions\Resources\RegionResource;

class CreateRegion extends CreateRecord
{
    use ResourcePageHelper;

    protected static string $resource = RegionResource::class;
}

