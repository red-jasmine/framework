<?php

namespace RedJasmine\FilamentRegion\Clusters\Regions\Resources\RegionResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentRegion\Clusters\Regions\Resources\RegionResource;

class ListRegions extends ListRecords
{
    use ResourcePageHelper;

    protected static string $resource = RegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

