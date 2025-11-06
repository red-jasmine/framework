<?php

namespace RedJasmine\FilamentRegion\Clusters\Regions\Resources\RegionResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentRegion\Clusters\Regions\Resources\RegionResource;

class EditRegion extends EditRecord
{
    use ResourcePageHelper;

    protected static string $resource = RegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

